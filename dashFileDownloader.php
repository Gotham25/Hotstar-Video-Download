<?php

require_once "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

$downloadRetries=0;

function downloadDashFilesBatch($uniqueId, $videoID, $dashAVFormatCode, $playbackURL, $initURL, $streamURL, $totalSegments) {
    $dashFiles=[];
    $segmentUrls=[];
    $segmentFileNames=[];
    $tempFolder = sprintf("temp_%s_%s", $videoID, $dashAVFormatCode);
    $tempDir = getcwd().DIRECTORY_SEPARATOR.$tempFolder;

    sendToClient($videoID, PHP_EOL."Downloading dash files to temp directory, '$tempDir'", $uniqueId);
   
    sendToClient($videoID, PHP_EOL."Creating temp directory, '$tempDir'", $uniqueId);
   
    //remove if directory exists already
    if (file_exists($tempDir) || is_dir($tempDir)) {
        sendToClient($videoID, PHP_EOL."Temp directory, '$tempDir' exists from previous run. Removing it...", $uniqueId);
        rrmdir($tempDir);
        sendToClient($videoID, PHP_EOL."Temp directory, '$tempDir' from previous run deleted successfully.", $uniqueId);
    }
   
    if (mkdir($tempDir, 0777, true)) {
        sendToClient($videoID, PHP_EOL."Successfully created temp directory, '$tempDir'", $uniqueId);
    } else {
        sendToClient($videoID, PHP_EOL."Error occurred in creating temp directory, '$tempDir'", $uniqueId);
    }
   
    $initSegmentURL = getSegmentURL($playbackURL, $initURL);
    $lastSlashIndex = strrpos($initURL, '/');
    $initSegmentFileName = (($lastSlashIndex === false) ? $initURL : substr($initURL, $lastSlashIndex + 1));
    $initFilePath = $tempFolder . DIRECTORY_SEPARATOR . $initSegmentFileName;
    $dashFiles[] = $initFilePath;
    $segmentUrls[] = $initSegmentURL;
    $segmentFileNames[] = $initSegmentFileName;
    //downloadDashFile($uniqueId, $videoID, $initSegmentURL, $initFilePath, $initSegmentFileName);
    $totalSegments = $totalSegments;
    for ($segmentIndex=1; $segmentIndex <= $totalSegments; $segmentIndex++) {
        $modifiedStreamURL = str_ireplace("\$Number\$", "$segmentIndex", $streamURL);
        $lastSlashIndex = strrpos($modifiedStreamURL, '/');
        $segmentFileName = ($lastSlashIndex === false) ? $modifiedStreamURL : substr($modifiedStreamURL, $lastSlashIndex + 1);
        $segmentFilePath = $tempFolder . DIRECTORY_SEPARATOR . $segmentFileName;
        $segmentURL = getSegmentURL($playbackURL, $modifiedStreamURL);
        $dashFiles[] = $segmentFilePath;
        $segmentUrls[] = $segmentURL;
        $segmentFileNames[] = $segmentFileName;
        $downloadRetries=0;
        ///downloadDashFile($uniqueId, $videoID, $segmentURL, $segmentFilePath, $segmentFileName);
    }
    downloadDashFile2($uniqueId, $videoID, $segmentUrls, $dashFiles, $segmentFileNames);
    return $dashFiles;
}

function downloadDashFile2($uniqueId, $videoID, $segmentUrls, $dashFiles, $segmentFileNames) {
    $guzzleClient = new GuzzleHttp\Client();

    $requests = function () use ($guzzleClient, $uniqueId, $videoID, $segmentUrls, $dashFiles, $segmentFileNames) {
        foreach ($segmentUrls as $segmentIndex => $segmentUrl) {
            sendToClient($videoID, PHP_EOL."Added dash file, ".$segmentFileNames[$segmentIndex]." to download pool", $uniqueId);
            yield function ($poolOpts) use ($guzzleClient, $segmentIndex, $dashFiles, $segmentUrl) {
                $reqOpts = [
               //sink option specifies the download path for this file
               "sink" => $dashFiles[$segmentIndex]
            ];
                if (is_array($poolOpts) && count($poolOpts) > 0) {
                    $reqOpts = array_merge($poolOpts, $reqOpts);
                }
                return $guzzleClient->getAsync($segmentUrl, $reqOpts);
            };
        }
    };

    $pool = new GuzzleHttp\Pool($guzzleClient, $requests(), [
      "concurrency" => 25,
      "fulfilled" => function (Response $response, $index) use ($uniqueId, $videoID, $segmentFileNames) {
          $dashFileSize = $response->getBody()->getSize();
          sendToClient($videoID, PHP_EOL."Dash file, ".$segmentFileNames[$index]." of size $dashFileSize bytes downloaded successfully", $uniqueId);
      },
      "rejected" => function (Exception $e, $index) use ($uniqueId, $videoID, $segmentFileNames) {
          sendToClient($videoID, PHP_EOL."Error occurred. ".PHP_EOL."Error message: ".$e->getMessage(), $uniqueId);
          exit($e->getMessage());
      },
   ]);

    $pool->promise()->wait();
}

function getSegmentURL($playbackURL, $streamID) {
    return str_ireplace("master.mpd", $streamID, $playbackURL);
}


function downloadDashFile($uniqueId, $videoID, $dashFileUrl, $tmpDirPath, $initSegmentFileName) {
    try {
        sendToClient($videoID, PHP_EOL."Downloading dash file, $initSegmentFileName from $dashFileUrl", $uniqueId);
        $dashFileContent = file_get_contents($dashFileUrl);
        if ($dashFileContent === false) {
            throw new Exception(PHP_EOL."Error occurred in downloading dash file, $dashFileUrl. Aborting script...".PHP_EOL);
        }
        $dashFileSize = file_put_contents($tmpDirPath, $dashFileContent);
        if ($dashFileSize === false) {
            throw new Exception(PHP_EOL."Error occurred in writing to dash file, $initSegmentFileName. Aborting script...".PHP_EOL);
        } else {
            sendToClient($videoID, PHP_EOL."Dash file, $initSegmentFileName of size $dashFileSize bytes downloaded successfully", $uniqueId);
        }
    } catch (Exception $e) {
        if ($downloadRetries++ <= 13) {
            sendToClient($videoID, PHP_EOL."Error occurred retrying..... retry count $downloadRetries", $uniqueId);
            downloadDashFile($dashFileUrl, $tmpDirPath, $initSegmentFileName);
        } else {
            exit(PHP_EOL . $e->getMessage() . PHP_EOL);
            throw new Exception($e->getMessage());
        }
    }
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir."/".$object)) {
                    rrmdir($dir."/".$object);
                } else {
                    unlink($dir."/".$object);
                }
            }
        }
        rmdir($dir);
    }
}

function sendToClient($videoId, $data, $uniqueId) {
    $progress = [];
    $progress['videoId'] = $videoId;
    $progress['data'] = nl2br($data);
    sendDashDownloadProgressToClient($progress, $uniqueId);
}

function sendDashDownloadProgressToClient($progress, $ipAddr_userAgent) {
    $options = [
       'cluster' => 'ap2',
       'encrypted' => true
   ];

    $pusher = new Pusher\Pusher('a44d3a9ebac525080cf1', '37da1edfa06cf988f19f', '505386', $options);

    $message['message'] = $progress;

    $pusher->trigger('hotstar-video-download-v1', $ipAddr_userAgent, $message);
}

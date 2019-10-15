<?php

require_once "dashFileDownloader.php";
require_once "vendor/autoload.php";
use Symfony\Component\Process\Process;

ini_set("max_execution_time", 30 * 60); //increase the max_execution_time to max of dyno inactivity time interval
if (isset($_POST['videoType'])) {
    if (extract($_POST)<=0) {
        die("Invalid POST parameters passed...");
    }

    $videoMetadataJson = json_decode($videoMetadata, true);

    //Send the response to client and proceed with video generation
    respondOK();

    if (strcmp($videoType, "video") === 0 || strcmp($videoType, "dash-webm-audio") === 0 || strcmp($videoType, "dash-webm-video") === 0) {
        //For normal video and dash webm video

        $videoGenerationCommand = [];
        array_push($videoGenerationCommand, getcwd() . DIRECTORY_SEPARATOR . "ffmpeg");
        array_push($videoGenerationCommand, "-i");
        array_push($videoGenerationCommand, $streamUrl);
    
        $outputFileName = "$videoId.mp4";

        if (strcmp($videoType, "dash-webm-audio") === 0 || strcmp($videoType, "dash-webm-video") === 0) {
            $outputFileName = "$videoId-$videoType.mp4";
        }
    
        foreach ($videoMetadataJson as $metaDataName => $metaDataValue) {
            if ($metaDataName == "title") {
                if (strcmp($videoType, "dash-webm-audio") === 0 || strcmp($videoType, "dash-webm-video") === 0) {
                    $outputFileName = "$videoId-$videoType-" . removeSpecialChars($metaDataValue) . ".mp4";
                } else {
                    $outputFileName = "$videoId-" . removeSpecialChars($metaDataValue) . ".mp4";
                }
            }
            array_push($videoGenerationCommand, "-metadata");
            array_push($videoGenerationCommand, "$metaDataName=\"$metaDataValue\"");
        }
    
        $videoZipCommand = [];
        array_push($videoZipCommand, "zip");
        array_push($videoZipCommand, "-D");
        array_push($videoZipCommand, "-m");
        array_push($videoZipCommand, "-9");
        array_push($videoZipCommand, "-v");
        array_push($videoZipCommand, "$videoId.zip");
        array_push($videoZipCommand, $outputFileName);
    
        if (strcmp($videoType, "dash-webm-audio") === 0) {
            array_push($videoGenerationCommand, "-strict");
            array_push($videoGenerationCommand, "-2");
        }
        array_push($videoGenerationCommand, "-c");
        array_push($videoGenerationCommand, "copy");
        array_push($videoGenerationCommand, $outputFileName);
    
        $process = new Process($videoGenerationCommand);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
    
        foreach ($process as $type => $data) {
            $progress = [];
            $progress['videoId'] = $videoId;
            $progress['data'] = nl2br($data);
            sendProgressToClient($progress, $uniqueId);
        }
    
        $process = new Process($videoZipCommand);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
    
        foreach ($process as $type => $data) {
            $progress = [];
            $progress['videoId'] = $videoId;
            $progress['data'] = nl2br($data);
            sendProgressToClient($progress, $uniqueId);
        }
    
        $progress = [];
        $progress['videoId'] = $videoId;
        $progress['data'] = nl2br("\nVideo generation complete...");
    
        sendProgressToClient($progress, $uniqueId);
    } elseif ((strcmp($videoType, "dash-audio")===0) || (strcmp($videoType, "dash-video")===0)) {
        //For DASH video (or) DASH audio
        
        $tempDashFileDirectory = sprintf("temp_%s_%s", $videoId, $videoFormat);
        $dashFiles = downloadDashFilesBatch($uniqueId, $videoId, $videoFormat, $playbackUrl, $initUrl, $streamUrl, $totalSegments);

        $dashAVGenerationCommand = [];
        array_push($dashAVGenerationCommand, getcwd() . DIRECTORY_SEPARATOR . "ffmpeg");
        array_push($dashAVGenerationCommand, "-i");
        $ffmpegInput = "concat:";
        foreach ($dashFiles as $index => $dashFile) {
            if ($index !== 0) {
                $ffmpegInput .= "|";
            }
            $ffmpegInput .= $dashFile;
        }

        array_push($dashAVGenerationCommand, $ffmpegInput);

        $outputFileName = "$videoId\__$videoType.mp4";
    
        foreach ($videoMetadataJson as $metaDataName => $metaDataValue) {
            if ($metaDataName == "title") {
                $outputFileName = $videoId."_". removeSpecialChars($metaDataValue) . "__$videoType.mp4";
            }
            array_push($dashAVGenerationCommand, "-metadata");
            array_push($dashAVGenerationCommand, "$metaDataName=\"$metaDataValue\"");
        }
        array_push($dashAVGenerationCommand, "-c");
        array_push($dashAVGenerationCommand, "copy");
        array_push($dashAVGenerationCommand, "-y");
        array_push($dashAVGenerationCommand, $outputFileName);

        $process = new Process($dashAVGenerationCommand);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
        
        foreach ($process as $type => $data) {
            $progress = [];
            $progress['videoId'] = $videoId;
            $progress['data'] = nl2br(PHP_EOL.$data);
            sendProgressToClient($progress, $uniqueId);
        }

        //remove the temp directory as we have the final DASH audio/video file
        $progress = [];
        $progress['videoId'] = $videoId;
        $progress['data'] = nl2br("\nRemoving temp directory $tempDashFileDirectory");
        sendProgressToClient($progress, $uniqueId);

        rrmdir($tempDashFileDirectory);
        
        $progress = [];
        $progress['videoId'] = $videoId;
        $progress['data'] = nl2br("\nTemp directory $tempDashFileDirectory, successfully removed");
        sendProgressToClient($progress, $uniqueId);

        $dashAVZipCommand = [];
        array_push($dashAVZipCommand, "zip");
        array_push($dashAVZipCommand, "-D");
        array_push($dashAVZipCommand, "-m");
        array_push($dashAVZipCommand, "-9");
        array_push($dashAVZipCommand, "-v");
        array_push($dashAVZipCommand, "$videoId.zip");
        array_push($dashAVZipCommand, $outputFileName);

        $process = new Process($dashAVZipCommand);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
        
        foreach ($process as $type => $data) {
            $progress = [];
            $progress['videoId'] = $videoId;
            $progress['data'] = nl2br(PHP_EOL.$data);
            sendProgressToClient($progress, $uniqueId);
        }

        $progress = [];
        $progress['videoId'] = $videoId;
        $progress['data'] = nl2br("\nDASH audio/video generation complete...");
        sendProgressToClient($progress, $uniqueId);
    } else {
        die("video format type unknown");
    }
} else {
    echo "Invalid script invocation";
    die("Invalid script invocation");
}

function removeSpecialChars($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with underscore.
    return preg_replace('/[^A-Za-z0-9_]/', '', $string); // Removes special chars.
}

/**
 * Respond 200 OK with an optional
 * This is used to return an acknowledgement response indicating that the request has been accepted and then the script can continue processing
 *
 * @param null $text
 */
function respondOK($text = null) {
    // check if fastcgi_finish_request is callable
    if (is_callable('fastcgi_finish_request')) {
        if ($text !== null) {
            echo $text;
        }
        /*
         * http://stackoverflow.com/a/38918192
         * This works in Nginx but the next approach not
        */
        session_write_close();
        fastcgi_finish_request();

        return;
    }

    ignore_user_abort(true);

    ob_start();

    if ($text !== null) {
        echo $text;
    }

    $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
    header($serverProtocol . ' 200 OK');
    // Disable compression (in case content length is compressed).
    header('Content-Encoding: none');
    header('Content-Length: ' . ob_get_length());

    // Close the connection.
    header('Connection: close');

    ob_end_flush();
    ob_flush();
    flush();
}

function sendProgressToClient($progress, $ipAddr_userAgent) {
    $options = [
        'cluster' => 'ap2',
        'encrypted' => true
    ];

    $pusher = new Pusher\Pusher('a44d3a9ebac525080cf1', '37da1edfa06cf988f19f', '505386', $options);

    $message['message'] = $progress;

    $pusher->trigger('hotstar-video-download-v1', $ipAddr_userAgent, $message);
}

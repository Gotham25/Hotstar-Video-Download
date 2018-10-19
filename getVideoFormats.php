<?php

require './../vendor/autoload.php';
use Symfony\Component\Process\Process;

$videoUrl = $_POST['url'];

$splittedVideoURL = explode("/", $videoUrl);

$splitCount = count($splittedVideoURL);
$formats    = array();

if ($splitCount > 0) {
    $videoId = $splittedVideoURL[$splitCount - 1];
    
    $formatsQuery = "./youtube-dl -F " . $videoUrl;
    
    exec("chmod a+rx youtube-dl");
	exec("./youtube-dl --update");
    
    $process = new Process($formatsQuery);
    $process->start();
    
    $videoFormatBuffer = "";
    $playlistId        = 0;
    $videoAvailability = false;
    foreach ($process as $type => $data) {
        if (strpos($data, $videoId) !== false || $videoAvailability) {
            //encountered video in playlist
            $videoAvailability = true;
            $videoFormatBuffer = $videoFormatBuffer . $data;
        }
        if ($videoAvailability && (strpos($data, "[download]") !== false)) {
            $videoAvailability = false;
            break;
        }
    }
    
    if (preg_match_all('/\[[a-z]+\]\s[A-Z][a-z]+\s[a-z]+\s([\d]+)\s[a-z]+\s[\d]+/', $videoFormatBuffer, $playlistResult, PREG_SET_ORDER)) {
        foreach ($playlistResult as $key => $value) {
            $playlistId = $value[1] - 1;
        }
    }
    
    if (preg_match_all("/(hls-[0-9]+)[\s]*mp4[\s]*([0-9]+x[0-9]+)/", $videoFormatBuffer, $formatResult, PREG_SET_ORDER)) {
        
        //creating available formats associative array
        $formats["status"]     = "available";
        $formats["playlistId"] = $playlistId;
        foreach ($formatResult as $key => $value) {
            $formats[$value[1]] = $value[2];
        }
        
    } else {
        $formats["status"] = "Video not found in playlist";
    }
} else {
    $formats["status"] = "Can't fetch video ID. Invalid URL";
}

echo json_encode($formats);

?>
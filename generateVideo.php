<?php

require 'vendor/autoload.php';
use Symfony\Component\Process\Process;

if (isset($_POST['src'])) {
    
    $src = $_POST['src'];
    
    exec("chmod a+rx youtube-dl");
	exec("./youtube-dl --update");
    exec("chmod +x ffmpeg");
    
    $ipAddr_userAgent = $_POST['uniqueId'];
    $videoUrl         = $_POST['videoUrl'];
    $playlistId       = $_POST['playlistId'];
    $videoId          = $_POST['videoId'];
    
    respondOK();
    
    if ($src === "ydl") {
        
        $videoFormat = $_POST['videoFormat'];
        $ffmpegLocation = getcwd() . DIRECTORY_SEPARATOR . "ffmpeg";
        $downloadVideoAndZipQuery = "./youtube-dl -f " . $videoFormat . " --playlist-items " . $playlistId . " " . $videoUrl . " --add-metadata --ffmpeg-location ". $ffmpegLocation ." --no-warnings --exec 'zip -D -m -9 -v " . $videoId . ".zip {}'";
        
        $process = new Process($downloadVideoAndZipQuery);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
        
        foreach ($process as $type => $data) {
            $progress            = array();
            $progress['videoId'] = $videoId;
            $progress['data']    = nl2br($data);
            sendProgressToClient($progress, $ipAddr_userAgent);
        }
        
    } else {
        
        $videoTitle       = $_POST['title'];
        $videoDescription = $_POST['description'];
        
        $outputFileName = $videoId . ".ts";
        
        $zipOutputQuery = "zip -D -m -9 -v " . $videoId . ".zip " . $outputFileName;
        
        $videoStreamQuery = "./ffmpeg -i \"" . $videoUrl . "\" -c copy -metadata title=\"" . $videoTitle . "\" -metadata episode_id=\"" . $playlistId . "\" -metadata track=\"" . $videoId . "\" -metadata description=\"" . $videoDescription . "\" -metadata synopsis=\"" . $videoDescription . "\" " . $outputFileName;
        
        $process = new Process($videoStreamQuery);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
        
        foreach ($process as $type => $data) {
            $progress            = array();
            $progress['videoId'] = $videoId;
            $progress['data']    = nl2br($data);
            sendProgressToClient($progress, $ipAddr_userAgent);
        }
        
        $process = new Process($zipOutputQuery);
        $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
        $process->start();
        
        foreach ($process as $type => $data) {
            $progress            = array();
            $progress['videoId'] = $videoId;
            $progress['data']    = nl2br($data);
            sendProgressToClient($progress, $ipAddr_userAgent);
        }
        
    }
    
    $progress                = array();
    $progress['videoId']     = $videoId;
    $progress['data']        = nl2br("\nVideo generation complete...");
    $progress['hasProgress'] = 'false';
    
    sendProgressToClient($progress, $ipAddr_userAgent);
    
} else {
    
    echo "Invalid script invocation";
    $ipAddr_userAgent        = $_POST['uniqueId'];
    $progress                = array();
    $progress['hasProgress'] = 'false';
    $progress['data']        = nl2br("Error occurred in receiving the post form data from the client");
    
    sendProgressToClient($progress, $ipAddr_userAgent);
    
}

/**
 * Respond 200 OK with an optional
 * This is used to return an acknowledgement response indicating that the request has been accepted and then the script can continue processing
 *
 * @param null $text
 */
function respondOK($text = null)
{
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



function sendProgressToClient($progress, $ipAddr_userAgent)
{
    
    $options = array(
        'cluster' => 'ap2',
        'encrypted' => true
    );
    
    $pusher = new Pusher\Pusher('a44d3a9ebac525080cf1', '37da1edfa06cf988f19f', '505386', $options);
    
    $message['message'] = $progress;
    
    $pusher->trigger('hotstar-video-download-v1', $ipAddr_userAgent, $message);
    
}

?>
<?php

ini_set('memory_limit', -1); //unlimited memory usage
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Process\Process;
use GuzzleHttp\Client as GuzzleHttpClient;
use Krizalys\Onedrive\Client as KrizalysOnedriveClient;
use Microsoft\Graph\Graph as MicrosoftGraph;

if (isset($_POST)) {
    $authCode = urldecode($_POST["authCode"]);
    $videoFileName = $_POST["fileName"];
    $ipAddr_userAgent = $_POST['uniqueId'];
    $videoId = $_POST['video_id'];

    //Send the response to client and proceed with video file upload
    respondOK("Upload task submitted successfully");

    $process = new Process(["php", "onedriveUpload.php", $authCode, $videoFileName]);
    $process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
    $process->start();

    foreach ($process as $type => $data) {
        $progress = [];
        $progress['videoId'] = $videoId;
        $progress['data'] = nl2br($data);
        sendProgressToClient($progress, $ipAddr_userAgent);
    }
} else {
    die("Invalid script invocation. Error : No POST data given");
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

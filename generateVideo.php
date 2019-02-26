<?php
require 'vendor/autoload.php';
use Symfony\Component\Process\Process;

ini_set("max_execution_time", 30 * 60); //increase the max_execution_time to max of dyno inactivity time interval

if (isset($_POST['videoUrl']))
{
	$videoUrl = $_POST['videoUrl'];
	$streamUrl = $_POST['streamUrl'];
	$videoMetadata = $_POST['videoMetadata'];
	$videoId = $_POST['videoId'];
	$selectedFormat = $_POST['videoFormat'];
	$ipAddr_userAgent = $_POST['uniqueId'];
	
	$videoMetadataJson = json_decode($videoMetadata, true);
	
	//Send the response to client and proceed with video generation
	respondOK();
	
	$videoGenerationCommand = array();
	array_push($videoGenerationCommand, getcwd()."/ffmpeg");
	array_push($videoGenerationCommand, "-i");
	array_push($videoGenerationCommand, $streamUrl);
	
	$outputFileName = $videoId . ".mp4";
	
	foreach( $videoMetadataJson as $metaDataName => $metaDataValue) {
		if($metaDataName == "title"){
			$outputFileName = removeSpecialChars($metaDataValue).".mp4";
		}
		array_push($videoGenerationCommand, "-metadata");
		array_push($videoGenerationCommand, $metaDataName."=\"".$metaDataValue."\"");
	}

	
	$videoZipCommand = array();
	array_push($videoZipCommand, "zip");
	array_push($videoZipCommand, "-D");
	array_push($videoZipCommand, "-m");
	array_push($videoZipCommand, "-9");
	array_push($videoZipCommand, "-v");
	array_push($videoZipCommand, $videoId.".zip");
	array_push($videoZipCommand, $outputFileName);
	
	array_push($videoGenerationCommand, "-c");
	array_push($videoGenerationCommand, "copy");
	array_push($videoGenerationCommand, $outputFileName);
	
	$process = new Process($videoGenerationCommand);
	$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
	$process->start();

	foreach ($process as $type => $data) {
			$progress = array();
			$progress['videoId'] = $videoId;
			$progress['data'] = nl2br($data);
			sendProgressToClient($progress, $ipAddr_userAgent);
	}

	$process = new Process($videoZipCommand);
	$process->setTimeout(30 * 60); //wait for atleast dyno inactivity time for the process to complete
	$process->start();

	foreach ($process as $type => $data) {
			$progress = array();
			$progress['videoId'] = $videoId;
			$progress['data'] = nl2br($data);
			sendProgressToClient($progress, $ipAddr_userAgent);
	}

	$progress = array();
	$progress['videoId'] = $videoId;
	$progress['data'] = nl2br("\nVideo generation complete...");

	sendProgressToClient($progress, $ipAddr_userAgent);
	
} else {
		echo "Invalid script invocation";
		$ipAddr_userAgent = $_POST['uniqueId'];
		$progress = array();
		$progress['hasProgress'] = 'false';
		$progress['data'] = nl2br("Error occurred in receiving the post form data from the client");

		sendProgressToClient($progress, $ipAddr_userAgent);
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
function respondOK($text = null)
{
		// check if fastcgi_finish_request is callable
		if (is_callable('fastcgi_finish_request'))
		{
				if ($text !== null)
				{
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

		if ($text !== null)
		{
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
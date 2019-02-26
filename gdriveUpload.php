<?php
include 'vendor/autoload.php';

function readVideoChunk ($handle, $chunkSize) {
    $byteCount = 0;
    $giantChunk = "";
    while (!feof($handle)) {
        // fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
        $chunk = fread($handle, 8192);
        $byteCount += strlen($chunk);
        $giantChunk .= $chunk;
        if ($byteCount >= $chunkSize) {
            return $giantChunk;
        }
    }
    return $giantChunk;
}

if($argc === 3){

	$authCode = $argv[1];
	$videoFileName = $argv[2];
	$client = new Google_Client();
	
	try {
			$client->setAuthConfig('client_secrets.json');
			$client->setAccessType("offline");
			$client->setIncludeGrantedScopes(true);
			$client->addScope(Google_Service_Drive::DRIVE);
			$client->setRedirectUri('https://hotstardownload.herokuapp.com/redirect_google.php');
			$auth_url = $client->createAuthUrl();
			$sanitized_auth_url = filter_var($auth_url, FILTER_SANITIZE_URL);
			$client->authenticate($authCode);
			
			$service = new Google_Service_Drive($client);
			
			$file = new Google_Service_Drive_DriveFile();
			$file->name = $videoFileName;
			$chunkSizeBytes = 1 * 1024 * 1024;

			// Call the API with the media upload, defer so it doesn't immediately return.
			$client->setDefer(true);
			$request = $service->files->create($file);

			// Create a media file upload to represent our upload process.
			$media = new Google_Http_MediaFileUpload(
				$client,
				$request,
				'application/zip',
				null,
				true,
				$chunkSizeBytes
			);
			$videoFileSize = filesize($videoFileName);
			$media->setFileSize($videoFileSize);

			// Upload the various chunks. $status will be false until the process is complete.
			$status = false;
			$handle = fopen($videoFileName, "rb");
			$bytesRead = 0;
			while (!$status && !feof($handle)) {
				// read until you get $chunkSizeBytes from $videoFileName
				// fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
				// An example of a read buffered file is when reading from a URL
				$chunk = readVideoChunk($handle, $chunkSizeBytes);
				$bytesRead += strlen($chunk);
				$status = $media->nextChunk($chunk);
			}
			
			// The final value of status will be the data from the API for the object
			// that has been uploaded.
			$result = false;
			
			if ($status != false) {
				$result = $status;
			}
			
			fclose($handle);

			$client->revokeToken();
			
			if($result == true) {
				echo PHP_EOL."File uploaded successfully".PHP_EOL;
			} else {
				echo PHP_EOL."Error occurred in uploading file to drive".PHP_EOL;
			}
			
	} catch(Exception $e) {
		$client->revokeToken();
		echo "Error occurred. Error Message : ".$e->getMessage();
	}
	
}else{
	die("Invalid invocation of script");
}
<?php

ini_set('memory_limit', -1); //unlimited memory usage
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleHttpClient;
use Krizalys\Onedrive\Client as KrizalysOnedriveClient;
use Microsoft\Graph\Graph as MicrosoftGraph;

if ($argc === 3) {
    $authCode = $argv[1];
    $videoFile = $argv[2];

    $onedriveClientId = getenv("ONEDRIVE_CLIENT_ID");
    $onedriveClientSecret = getenv("ONEDRIVE_CLIENT_SECRET");
    $onedriveRedirectUri = getenv("ONEDRIVE_REDIRECT_URI");

    try {
        $client = new KrizalysOnedriveClient($onedriveClientId, new MicrosoftGraph(), new GuzzleHttpClient());

        // Gets a log in URL with sufficient privileges from the OneDrive API.
        $url = $client->getLogInUrl(['files.read', 'files.read.all', 'files.readwrite', 'files.readwrite.all', 'offline_access', ], $onedriveRedirectUri);

        $_SESSION['onedrive.client.state'] = $client->getState();

        $client->obtainAccessToken($onedriveClientSecret, $authCode);

        $drives = $client->getDrives();
        $personalDrive = null;
        foreach ($drives as $driveVal) {
            if ($driveVal->driveType == "personal") {
                $personalDrive = $driveVal;
                break;
            }
        }

        if ($personalDrive != null) {
            $personalDriveRoot = $personalDrive->getRoot();
            $children = $personalDriveRoot->getChildren();
            $hotstarVideosRoot = null;

            foreach ($children as $child) {
                if ($child->folder != null && $child->name == "Hotstar videos") {
                    $hotstarVideosRoot = null;
                    break;
                }
            }

            if ($hotstarVideosRoot == null) {
                $hotstarVideosRoot = $personalDriveRoot->createFolder("Hotstar videos", ["description" => "Video folder created by Hotstar Video Downloader web app"]);
            }

            $fileContents = file_get_contents($videoFile);
            $optimalChunkSize = (int)round(filesize($videoFile) * 0.13); //13% of original file
            $uploadSession = $hotstarVideosRoot->startUpload($videoFile, $fileContents, ["range_size" => $optimalChunkSize]);
            $uploadedFile = $uploadSession->complete();
            echo PHP_EOL . "File $videoFile uploaded successfully to Onedrive" . PHP_EOL;
        } else {
            throw new Exception("No personal drive named as 'personal' found");
        }
    } catch (Exception $e) {
        echo "Error occurred for Onedrive Upload. Error Message : " . $e->getMessage();
    }
} else {
    die("Invalid invocation of script");
}

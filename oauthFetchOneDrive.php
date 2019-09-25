<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleHttpClient;
use Krizalys\Onedrive\Client;
use Microsoft\Graph\Graph;

$onedriveClientId = getenv("ONEDRIVE_CLIENT_ID");
$onedriveRedirectUri = getenv("ONEDRIVE_REDIRECT_URI");

// Instantiates a OneDrive client bound to your OneDrive application.
$client = new Client($onedriveClientId, new Graph(), new GuzzleHttpClient());

// Gets a log in URL with sufficient privileges from the OneDrive API.
$url = $client->getLogInUrl(['files.read', 'files.read.all', 'files.readwrite', 'files.readwrite.all', 'offline_access', ], $onedriveRedirectUri);

session_start();

// Persist the OneDrive client' state for next API requests.
$_SESSION['onedrive.client.state'] = $client->getState();

// Redirect the user to the log in URL.
header('HTTP/1.1 302 Found', true, 302);
header("Location: $url");

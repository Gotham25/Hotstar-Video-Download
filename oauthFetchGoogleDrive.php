<?php

include 'vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName(getenv("GOOGLE_DRIVE_APPLICATION_NAME"));
$client->setClientId(getenv("GOOGLE_DRIVE_CLIENT_ID"));
$client->setClientSecret(getenv("GOOGLE_DRIVE_CLIENT_SECRET"));
$client->setRedirectUri(getenv("GOOGLE_DRIVE_REDIRECT_URI"));
$client->setAccessType(getenv("GOOGLE_DRIVE_ACCESS_TYPE"));
$client->addScope(Google_Service_Drive::DRIVE);
$client->setIncludeGrantedScopes(true);
$auth_url = $client->createAuthUrl();
$sanitized_auth_url = filter_var($auth_url, FILTER_SANITIZE_URL);
header('Location: ' . $sanitized_auth_url);

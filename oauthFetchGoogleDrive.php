<?php
	include 'vendor/autoload.php';
	
	$client = new Google_Client();
	$client->setAuthConfig('client_secrets.json');
	$client->setAccessType("offline");
	$client->setIncludeGrantedScopes(true);
	$client->addScope(Google_Service_Drive::DRIVE);
	$client->setRedirectUri('https://hotstardownload.herokuapp.com/redirect_google.php');
	$auth_url = $client->createAuthUrl();
	$sanitized_auth_url = filter_var($auth_url, FILTER_SANITIZE_URL);
	header('Location: ' . $sanitized_auth_url);
?>
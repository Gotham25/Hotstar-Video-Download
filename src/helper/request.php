<?php

//Download the certificates for curl
function downloadCurlCertificates() {
	if(!file_exists("cacert.pem")) {
	    //Certificate doesn't exists downloading one
	    file_put_contents("cacert.pem", file_get_contents("https://curl.haxx.se/ca/cacert.pem"));
	}
}


function request($url, $headers) {
	$ch = curl_init();

	downloadCurlCertificates();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
	curl_setopt($ch, CURLOPT_CAPATH, "cacert.pem");
	//curl_setopt($ch, CURLOPT_USERAGENT, 'curl/7.61.1');
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);

	return $result;
}


?>
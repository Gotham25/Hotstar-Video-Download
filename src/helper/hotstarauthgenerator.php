<?php

function generateHotstarAuth() {
	$startTime   = round(microtime(true));
	$expiryTime  = $startTime + 6000;
	$auth = "st=$startTime~exp=$expiryTime~acl=/*";
	$string = mb_convert_encoding($auth, "UTF-8");
	$secret = array(
	    0x05,
	    0xfc,
	    0x1a,
	    0x01,
	    0xca,
	    0xc9,
	    0x4b,
	    0xc4,
	    0x12,
	    0xfc,
	    0x53,
	    0x12,
	    0x07,
	    0x75,
	    0xf9,
	    0xee
	);

	$key = "";
	for ($i = 0; $i < sizeof($secret); $i++) {
	    $key .= chr($secret[$i]);
	}
	$sig = hash_hmac("sha256", $string, $key);
	$auth .= "~hmac=" . $sig;
	return $auth;
}

?>
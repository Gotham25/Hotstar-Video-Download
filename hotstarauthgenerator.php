<?php

error_log(json_encode($_SERVER));

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Type'); 
  return 0;
}

function generateHotstarAuth($microTime) {
    $st = round($microTime);
    $exp = $st + 6000;
    $auth = "st=$st~exp=$exp~acl=/*";
    $string = mb_convert_encoding($auth, "UTF-8");
    $secret = [
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
    ];

    $key = "";
    for ($i = 0;$i < sizeof($secret);$i++) {
        $key .= chr($secret[$i]);
    }
    $sig = hash_hmac("sha256", $string, $key);
    $auth .= "~hmac=" . $sig;
    return $auth;
}

$microTime = microtime(true);
$timestamp = (int) ($microTime * 1000);

$generatedAuth = generateHotstarAuth($microTime);
$ipAddress = $_SERVER['REMOTE_ADDR'];

/*
$link = mysqli_connect("localhost", "131954", "Gotham25", "131954");

// Check connection
if($link === false) {
   die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt insert query execution
$sql = "INSERT INTO auth_requests (ip_address, generated_auth) VALUES ('$ipAddress', '$generatedAuth')";

mysqli_query($link, $sql);

// Close connection 
mysqli_close($link);
*/
$response = array();
$response["auth"] = $generatedAuth;
$response["timestamp"] = $timestamp;

$allowedOrigin = $_SERVER['ORIGIN'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

echo json_encode($response);

//echo "{\"auth\":\"" . $generatedAuth . "\",\"timestamp\":$timestamp}";

?>

<?php

if (!isset($_GET['videoId'])) {
    die('Invalid invocation of file');
}

$filename = $_GET['videoId'] . ".zip";
$filepath = "/app/";

// http headers for zip downloads 

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/zip, application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($filepath . $filename));
ob_end_flush();
@readfile($filepath . $filename);

?>
<?php

if (isset($_POST)) {
    $authCode = urldecode($_POST["authCode"]);
    $videoFileName = $_POST["fileName"];
    $gdriveUploadCommand = "php gdriveUpload.php " . $authCode . " " . $videoFileName;

    $gdriveUploadOutput = shell_exec($gdriveUploadCommand);

    echo $gdriveUploadOutput;
} else {
    die("Invalid script invocation. Error : No POST data given");
}

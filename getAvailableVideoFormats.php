<?php
include('src/VideoFormats.php');

if (!isset($_POST['url'])) {
    die("Error no POST url data given");
}

$videoFormats = new VideoFormats($_POST['url']);

echo $videoFormats->getAvailableFormats();

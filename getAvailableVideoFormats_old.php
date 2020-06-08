<?php
include('src/VideoFormats.php');

// if (!isset($_POST['url'])) {
//     die("Error no POST url data given");
// }

// $videoFormats = new VideoFormats($_POST['url']);

$videoFormats = new VideoFormats("https://www.hotstar.com/in/tv/ayudha-ezhuthu/s-2213/bad-times-for-kaliammal/1100037155");

echo $videoFormats->getAvailableFormats();

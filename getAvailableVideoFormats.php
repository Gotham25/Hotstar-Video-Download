<?php
	include('src/VideoFormats.php');
	
	//Download the binaries necessary for downloading video
	shell_exec('php installBinaries.php');
	
	if (!isset($_POST['url'])) {
		die("Error no POST url data given");
	}
	
	$videoUrl = $_POST['url'];
	$videoFormats = new VideoFormats();
	$formats = $videoFormats->isAvailable($videoUrl);
	
	echo json_encode($formats, true);
?>
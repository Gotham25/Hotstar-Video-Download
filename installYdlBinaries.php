<?php
	$ydlReleaseUrl = "https://github.com/rg3/youtube-dl/releases/latest";
	$ydlDownloadPath = str_ireplace("tag", "download", shell_exec("curl --silent ".$ydlReleaseUrl." | grep -Po '(?<=href=\")[^\"]*' | tr -d '\n'") )."/youtube-dl";
	echo "Downloading youtube-dl binaries...";
	shell_exec("wget -q ".$ydlDownloadPath);
	echo "youtube-dl binaries downloaded...";
?>
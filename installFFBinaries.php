<?php
	echo "Installing ffmpeg and ffprobe binaries...";
    shell_exec("chmod +x installFFBinaries.sh");
	shell_exec("sh installFFBinaries.sh");
	echo "ffmpeg and ffprobe binaries installed...";
?>
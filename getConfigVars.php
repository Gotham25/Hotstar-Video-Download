<?php
	$dbApiKey = getenv('DROPBOX_API_KEY');
	$configs = array('dbKey' => $dbApiKey);
	echo json_encode($configs);
?>
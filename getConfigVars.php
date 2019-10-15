<?php
exec("nohup setsid php analytics.php " . $_GET["ip"] . " > /dev/null 2>&1 &");
$dbApiKey = getenv('DROPBOX_API_KEY');
$configs = [
    'dbKey' => $dbApiKey,
];
echo json_encode($configs);

<?php

$url      = 'http://en.fetchfile.net/fetch/';
$videoUrl = $argv[1];

$data = array(
    'url' => $videoUrl,
    'action' => 'homePure'
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);

$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    /* Handle error */
}

$jsonResponse = json_decode($result, true);

$formats = $jsonResponse['formats'];

if ($formats != null) {
    
    $videoMetadata   = array();
    $fetchedVideoUrl = "";
    
    $videoMetadata['videoId']         = $jsonResponse['display_id'];
    $videoMetadata['episode']         = $jsonResponse['episode'];
    $videoMetadata['episodeNumber']   = $jsonResponse['episode_number'];
    $videoMetadata['description']     = $jsonResponse['description'];
    $videoMetadata['hasVideoFormats'] = 'false';
    
    foreach ($formats as $item) {
        $videoMetadata['hasVideoFormats'] = 'true';
        $videoMetadata[$item['format']]   = $item['url'];
    }
    
    echo json_encode($videoMetadata);
    
} else {
    echo "Invalid Response. Please wait...";
}

?>
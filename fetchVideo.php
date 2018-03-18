<?php

$url = 'http://en.fetchfile.net/fetch/'; 
$videoUrl = $argv[1];

$data = array(
  'url' => $videoUrl, 
  'action' => 'homePure'
  ); 

// use key 'http' even if you send the request to https://... 

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
   die("Error in fetching url contents");
} 

$jsonResponse = json_decode($result, true);

$formats = $jsonResponse['formats'];

if($formats != null){
  $i=0;
  $fetchedVideoUrl="";
  
  foreach($formats as $item){
     $videoFormat = $item['format'];
     if(stripos($videoFormat,"640x360")!==false){
         $fetchedVideoUrl=$item['url'];
     }
     $i++;
  }
  echo $fetchedVideoUrl;
}else{
  echo "Invalid Response. Please wait...";
}

?>
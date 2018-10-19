	<?php
	
	require 'vendor/autoload.php';
 use Symfony\Component\Process\Process;

	
	if($argc === 5){
		
		  $videoUrl=$argv[1];
		  $playlistId=$argv[2];
		  $videoId=$argv[3];
		  $videoFormat=$argv[4];
		  $totalDurationStr="";
		  $totalDuration=0;
		  $isTotalDuration='true';
		  
		  exec("rm -rf *.mp4");
		  exec("rm -rf *.part");
		  exec("rm -rf *.zip");
		  
		  exec("chmod a+rx youtube-dl");
		  exec("./youtube-dl --update");
		  
		  $downloadVideoAndZipQuery = "./youtube-dl -f ".$videoFormat." --playlist-items ".$playlistId." ".$videoUrl." --add-metadata --ffmpeg-location /app/ffmpeg --no-warnings --exec 'zip -D -m -9 -v ".$videoId.".zip {}'";
		  
		  $process = new Process($downloadVideoAndZipQuery);
	   $process->start();
	   $isErrorInDownload=FALSE;
	   
	   foreach ($process as $type => $data) {
	   	
	   	   echo $data;
			    
			    /*
			    $progress = array();
			    $progress['data'] = $data;
			    $progress['hasProgress']='false';
			    
			    if(preg_match_all("/(\d{2})\:(\d{2})\:(\d{2})\.(\d{2})/", $data, $res, PREG_SET_ORDER)){
			    	   $hours = $res[0][1] * 1;
			    	   $minutes = $res[0][2] * 1;
			    	   $seconds = $res[0][3] * 1;
			    	   $microseconds = $res[0][4] * 1;
			    	   
			    	   $totalSeconds = $hours * 60 * 60 + $minutes * 60 + $seconds + ($microseconds/1000000);
			    	   
			    	   $completionPercentage=1;
			    	   
			    	   if($isTotalDuration ==='true'){
			    	   	   $isTotalDuration='false';
			    	   	   $totalDurationStr=$res[0][0];
			    	   	   $totalDuration=$totalSeconds;
			    	   }else{
			    	   	   //Calculate completion of the task for 1 less than 100
			    	   	   $completionPercentage = round((($totalSeconds/$totalDuration)*99), 2);
			    	   }
			    	   
			    	   $progress['completionPercentage']=$completionPercentage;
			    	   $progress['hasProgress']='true';
			    	   
			    }
			    
			    sendProgressToClient($progress);
			    */
			    
			    if(is_int(stripos($data, "not available"))){
			    	  $isErrorInDownload=TRUE;
			    	  break;
			    }
			    
	   	}
	   	
	   	/*
	   	$progress = array();
	   	$progress['hasProgress']='false';
	   	
	   	if($isErrorInDownload === TRUE)
	      	$progress['data'] = "\n\nError occurred in downloading the file";
	   	else
	      	$progress['data'] = "\n\nDownload complete...";
	      	
	   sendProgressToClient($progress);
	   */
		  
	   	if($isErrorInDownload === TRUE)
	      	echo "\n\nError occurred in downloading the file";
	   	else
	      	echo "\nVideo generation complete...";
	   
	}else if(($argc==2) && ((strcasecmp($argv[1], "--help") === 0) || (strcasecmp($argv[1], "/h")))){
		  scriptUsage();
	}else{
		  echo "\nInvalid arguments entered\n";
		  scriptUsage();
	}
	
	function scriptUsage(){
		  $usage = "";
		  $usage .= "\nUsage: php downloadHotstarVideo.php URL ID1 ID2 FORMAT";
		  $usage .= "\nwhere\n\n  URL - url of the video to be downloaded\n\n  ID1 - playlist id for the given video url\n\n  ID2 - video id for the given video url\n\n  FORMAT - resolution of the video to be downloaded for the given video url\n\n";
		  
		  echo $usage;
	}
	
	/*
	function sendProgressToClient($progress){
		   //TODO: Check if the variable values are set fine
		   echo PHP_EOL."Progress : ".PHP_EOL;
		   var_dump($progress);
		   echo PHP_EOL;
	}
	*/
	
	?>
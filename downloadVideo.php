<?php

	$action = $_POST['action'];
	
	switch($action){
		case 'downloadVideo': $videoUrl=$_POST['url'];
		                      $fetchVideoScriptQuery = "php fetchVideo.php ".$videoUrl;

                          $result="Invalid Response";
   
                          while(stripos($result,"Invalid")!==false){
                            $result=exec($fetchVideoScriptQuery);
                          }
                          
                          exec("cp /app/files.tar.gz /app/");
                          exec("tar xvzf files.tar.gz");
                          $videoStreamQuery = "./ffmpeg -i \"".$result."\" -c copy Video.ts";
                          exec($videoStreamQuery);

                          
                          $filePath = "/app/Downloads/";
                          
                          mkdir($filePath, 0777, true);
                          exec("zip -9 -v -T -m -j /app/Downloads/Video.zip /app/Video.ts");
                          
                          echo "done";

				                     break;
				                    
		default: echo "Invalid script invocation";
		         die("Invalid script invocation");
	}
	
?>
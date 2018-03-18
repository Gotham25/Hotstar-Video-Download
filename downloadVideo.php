<?php

	$action = $_POST['action'];
	
	switch($action){
		case 'downloadVideo': $videoUrl=$_POST['url'];
		                      $fetchVideoScriptQuery = "php fetchVideo.php ".$videoUrl;

                          $result="Invalid Response";
   
                          while(stripos($result,"Invalid")!==false){
                            $result=exec($fetchVideoScriptQuery);
                          }
                          
                          exec("cp /app/files.tar.gz /app/hotstarTest/");
                          exec("tar xvzf files.tar.gz");
                          $videoStreamQuery = "./ffmpeg -i \"".$result."\" -c copy Video.ts";
                          exec($videoStreamQuery);

                          
                          $filePath = "/app/Downloads/";
                          
                          mkdir($filePath, 0777, true);
                          exec("zip -9 -v -T -m -j /app/Downloads/Video.zip /app/hotstarTest/Video.ts");
                          
                          echo "done";

				                     break;
				                    
				               
		case 'test': echo "\n\n".`ls -lh /app/hotstarTest`;
		             break;
	
		default: echo "Invalid script invocation";
		         die("Invalid script invocation");
	}
	
?>
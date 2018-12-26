<?php

require 'vendor/autoload.php';
use Symfony\Component\Process\Process;

class VideoFormats
{
    
    public function __construct()
    {
        exec("chmod a+rx youtube-dl");
    }
    
    
    public function isAvailable($videoUrl)
    {
		$ydlLocation = getcwd() . DIRECTORY_SEPARATOR . "youtube-dl";
		
		$process = new Process(array($ydlLocation, "--restrict-filenames", "-j", "--flat-playlist", $videoUrl));
		
		$process->start();
		
		while ($process->isRunning()) {
           // waiting for process to finish
        }
		
		$output = $process->getOutput();
		
        $errorOutput = $process->getErrorOutput();
		
        //$output = shell_exec("./youtube-dl -j --flat-playlist " . $videoUrl);
        
        $endCurlySearch = '}
		]';
        
        $endCurlyReplace = '}]';
        
        $output = str_replace($endCurlySearch, $endCurlyReplace, "[" . $output . "]");
        
        $jsonOutput = str_replace(", ]", "]", str_replace("\n", ", ", $output));
        
        $jsonArray = json_decode($jsonOutput, true);
        
        $modifiedVideoUrl = $videoUrl;
        
        if (strcasecmp($videoUrl[strlen($videoUrl) - 1], "/") === 0) {
            //Remove the '/' in the end of url if present
            $modifiedVideoUrl = substr($modifiedVideoUrl, 0, -1);
        }
        
        $modifiedVideoUrlArray = preg_split('/\//', $modifiedVideoUrl);
        
        $videoId      = end($modifiedVideoUrlArray);
        $availability = 'false';
        $playlistId   = 0;
        $formats      = array();
        
        if (!is_numeric($videoId)) {
            $formats["status"]       = $availability;
            $formats["errorMessage"] = "Invalid video ID fetched from URL";
        } else {
            foreach ($jsonArray as $key => $value) {
                if (strcmp($videoId, strval($value['id'])) == 0) {
                    $availability = 'true';
                    $playlistId   = $key + 1;
                    break;
                }
            }
            $formats['status'] = $availability;
        }
        
        if ($availability === 'true') {
            
            //Fetch available video formats
            $formats['source']     = "ydl";
            $formats['videoId']    = $videoId;
            $formats['playlistId'] = $playlistId;
            $formatsQuery          = "./youtube-dl -F " . $videoUrl . " --playlist-items " . $playlistId;
            $formatsBuffer         = shell_exec($formatsQuery);
            if (preg_match_all("/(hls-[0-9]+)[\s]*mp4[\s]*([0-9]+x[0-9]+)/", $formatsBuffer, $formatsResult, PREG_SET_ORDER)) {
                foreach ($formatsResult as $key => $value) {
                    $formats[$value[1]] = $value[2];
                }
            } else {
				$formats['playlistId'] = $playlistId;
                $formats["errorMessage"] = $errorOutput;
            }
            
        } else{
			$formats['playlistId'] = -1;
			$formats["errorMessage"] = $errorOutput;
		} 
		
		
		/* else {
            
            //$formats["errorMessage"]="Can't fetch video ID or Invalid URL";
            //Fetching video formats and url through api request
            
            $result = "Invalid Response";
            $tries  = 0;
            
            //Try to fetch the stream url for the given video URL for a certain time
            while (stripos($result, "Invalid") !== false) {
                $result = $this->getFormatsThroughApi($videoUrl);
                //echo "\ntries #".($tries+1)." api result : ".$result;
                if (++$tries > 100) {
                    break;
                }
            }
            
            if (stripos($result, "Invalid") !== false) {
                $formats['status']       = 'false';
                $formats["errorMessage"] = "Can't fetch video ID or Invalid URL";
            } else {
                $formats['status']        = 'true';
                $metadata                 = json_decode($result, true);
                $formats['source']        = "api";
                $formats['videoId']       = $metadata['videoId'];
                $formats['episodeNumber'] = $metadata['episodeNumber'];
                $formats['title']         = $metadata['episode'];
                $formats['description']   = $metadata['description'];
                
                foreach ($metadata as $key => $value) {
                    if (stripos($key, "hls") !== false) {
                        $formats[$key] = $value;
                    }
                }
            }
        } */
        
        return $formats;
        
    }
    
    public function getFormatsThroughApi($videoUrl)
    {
        $url  = 'http://en.fetchfile.net/fetch/';
        //echo "\nvideoUrl : ".$videoUrl;
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
        
		if(!isset($jsonResponse['formats'])){
			return "Invalid Response. Please wait...";
		}
        
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
            
            return json_encode($videoMetadata);
            
        } else {
            return "Invalid Response. Please wait...";
        }
    }
    
}

?>
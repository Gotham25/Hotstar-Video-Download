<?php
	
	class VideoFormats
	{
		private $videoUrl;
		private $playbackUri;
		private $appState;
		private $appStateJson;
		private $headers;
		private $playbackUrlData;
		
		public function __construct($videoUrl) {
			//include all files under helper package
			foreach (glob("src/helper/*.php") as $helperFile) {
				include_once $helperFile;
			}

			$this->videoUrl = $videoUrl;
			$this->playbackUri = null;
			$this->appState = null;
			$this->appStateJson = null;
			$this->headers   = array();
			$this->headers[] = 'Hotstarauth: ' . generateHotstarAuth();
			$this->headers[] = 'X-Country-Code: IN';
			$this->headers[] = 'X-Platform-Code: JIO'; 
		}
		
		private function isValidHotstarUrl() {
			
			if(preg_match('/((https?:\/\/)?(www\.)?)?hotstar.com\/(?:.+?[\/-])+(?P<videoId>\d{10})([\/|\w]*)/', $this->videoUrl, $match)){
				return $match["videoId"];
			}

			return "";
		}

		private function getKForm($num) {
		    if($num < 1000){
		        return $num;
		    }

		    return intval($num/1000);
		}
		
		private function getVideoMetadata($content) {
		   $metaData = array();
		   foreach ($content as $contentName => $contentValue) {
		      
		      switch($contentName) {
				  
				case "title":
						$metaData[$contentName]=$contentValue;
						break;
				case "genre":
						$metaData[$contentName]=$contentValue;
						break;
				case "description":
						$metaData[$contentName]=$contentValue;
						$metaData["synopsis"]=$contentValue;
						$metaData["comment"]=$contentValue;
						break;
				case "actors":
						$actors = "";
						foreach($contentValue as $i => $actor) {
							if(strlen($actors) !=0 ) {
								$actors .= "/";
							}
							$actors .= $actor;
						}
						$metaData["artist"]=$actors;
						$metaData["composer"]=$actors;
						$metaData["album_artist"]=$actors;
						break;
				case "broadcastDate": 
						date_default_timezone_set("Asia/Calcutta");
						$metaData["creation_time"]="".date("Y-m-d H:i:s", $contentValue);
						$metaData["year"]="".date("Y", $contentValue);
						$metaData["date"]="".date("Y", $contentValue);
						break;
				case "drmProtected":
						$metaData[$contentName]=$contentValue;
						break;
				
				//below meta-data may/may not be honored by ffmpeg
		         case "channelName": 
		                  $metaData["copyright"]="©copyright ".$contentValue;
		                  break;
		         case "episodeNo": 
		                  $metaData["episode_id"]=$contentValue;
		                  $metaData["track"]=$contentValue;
		                  break;
		         case "showName": 
		                  $metaData["show"]=$contentValue;
		                  break;
		         case "seasonNo": 
		                  $metaData["season_number"]=$contentValue;
		                  break;
		         case "playbackUri": 
		                  $metaData[$contentName]=$contentValue;
		                  break;
		         default: 
		                  //add nothing
		                  break;
		         }
		   }
		   
		   return $metaData;
		}

		private function getUrlFormats($playbackUrl, $playbackUrlresponse) {
			$url_formats = array();
			$infoArray = null;
			foreach ( preg_split("/((\r?\n)|(\r\n?))/", $playbackUrlresponse) as $line ) {
			    if (substr($line, 0, 18) === "#EXT-X-STREAM-INF:") {
			        $m3u8InfoCsv = str_replace("#EXT-X-STREAM-INF:", "", $line);
			        $m3u8InfoArray = preg_split('/,(?=(?:[^"]*"[^"]*")*[^"]*$)/', $m3u8InfoCsv);
			        foreach ($m3u8InfoArray as $m3u8Info) {
			            if($infoArray === null){
			                $infoArray = array();
			            }
			            $info = explode("=", $m3u8Info);
			            $infoArray[ $info[0] ] = $info[1];
			        }
			    }elseif (substr($line, 0, 6) === "master" || substr($line, 0, 4) === "http") {
			        
			        $kFormAverageBandwidthOrBandwidth = $this->getKForm(isset($infoArray["AVERAGE-BANDWIDTH"]) ? $infoArray["AVERAGE-BANDWIDTH"] : $infoArray["BANDWIDTH"]);
			        $formatCode = "hls-".$kFormAverageBandwidthOrBandwidth; //eg hls-281 for 281469
			        $streamUrl = (substr($line, 0, 4) === "http") ? $line : str_replace("master.m3u8", $line, $playbackUrl); //if starts with http then it's direct url
					if (strpos($streamUrl, '~acl=/*~hmac') === false) { //check if the url data contains hmac. If not, add it from playback url
						$streamUrl .= "&".$this->playbackUrlData;
					}
					$infoArray["STREAM-URL"] = $streamUrl;

			        $url_formats[$formatCode] = $infoArray;

			        //Reset m3u8InfoArray for next layer
			        $infoArray = null;
			    }else {
			        //do nothing
			    }
			}

			return $url_formats;
		}
		
		private function getPlaybackUrlData($playbackUrl) {
			$playbackUrlDataPieces = explode("?", $playbackUrl);
			return count($playbackUrlDataPieces) === 2 ? $playbackUrlDataPieces[1] : "";
		}

		public function getAvailableFormats() {
			
			$url_formats = array();
			$videoMetadata = array();

			try {
				
				//remove extra / at last if present
				if( strrpos($this->videoUrl, "/") == strlen($this->videoUrl)-1 ) {
				    $this->videoUrl = substr($this->videoUrl, 0, strlen($this->videoUrl)-1);
				}
				
				$videoId = $this->isValidHotstarUrl();
					
				if(empty($videoId)) {
					throw new Exception("Invalid Hotstar video URL");
				}
				
				$metaDataRootKey = str_replace('hotstar.com', '', substr($this->videoUrl, strpos($this->videoUrl, 'hotstar.com')));
				
				$fileContents = file_get_contents($this->videoUrl);

				if (preg_match('%<script>window.APP_STATE=(.*?)</script>%', $fileContents, $match)) {
				    $this->appState = $match[1];
				} else {
					throw new Exception("APP_STATE JSON metadata not present in site");
				}

				if ($this->appState != null) {
				    $this->appStateJson = json_decode($this->appState, true);
				}
				
				foreach ($this->appStateJson as $key => $value) {

				    $keyParts = explode("/", $key);
					
					if ($key == $metaDataRootKey || in_array($videoId, $keyParts)) {
						
						$videoMetadata = $this->getVideoMetadata($value["initialState"]["contentData"]["content"]);
				    
						if ($videoMetadata["drmProtected"]) {
							$url_formats["isError"] = true;
							$url_formats["errorMessage"] = "The video is DRM Protected";
							return json_encode($url_formats, true);
						}
						
				        $this->playbackUri = $videoMetadata["playbackUri"];
				        break;
				    }

				}
				$url = $this->playbackUri."&tas=10000";
				$playbackUriResponse = request($url, $this->headers);
				$playbackUriResponseJson = json_decode($playbackUriResponse, true);
				if ($playbackUriResponseJson["statusCodeValue"] != 200) {
					throw new Exception("Error processing request for playbackUri");
				}
				$playbackUrl = $playbackUriResponseJson["body"]["results"]["item"]["playbackUrl"];
				$this->playbackUrlData = $this->getPlaybackUrlData($playbackUrl);
				$playbackUrlresponse = request($playbackUrl, $this->headers);
				$url_formats = $this->getUrlFormats($playbackUrl, $playbackUrlresponse);
				$url_formats["metadata"]=$videoMetadata;
				$url_formats["videoId"] = $videoId;
				$url_formats["isError"] = false;

			} catch (Exception $e) {
				$url_formats["isError"] = true;
				$url_formats["errorMessage"] = $e->getMessage();
			}

			return json_encode($url_formats, true);
		}

	}

?>
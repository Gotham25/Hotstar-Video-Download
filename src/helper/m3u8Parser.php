<?php
	require_once("utils.php");
	
	function getUrlFormats($infoArray, $line, $playbackUrl, $playbackUrlData) {
		$kFormAvgBwOrBw = getKForm( (int) (isset($infoArray["AVERAGE-BANDWIDTH"]) ? $infoArray["AVERAGE-BANDWIDTH"] : $infoArray["BANDWIDTH"]) );
		$formatCode = "hls-$kFormAvgBwOrBw"; //eg hls-281 for 281469
		$streamUrl = startsWith($line, "http") ? $line : str_replace("master.m3u8", $line, $playbackUrl); //if starts with http then it's direct url
		
		if (strpos($streamUrl, "~acl=/*~hmac") === false) { //check if the url data contains hmac. If not, add it from playback url
			if(strpos($streamUrl, "?") === false){ //checking if URL does have any query string already
				$streamUrl .= "?";
			}
			$streamUrl .= "&$playbackUrlData";
		}
		$infoArray["STREAM-URL"] = $streamUrl;
		
		return array($formatCode, $infoArray);
	}

	function parseM3u8Content($m3u8Content, $playbackUrl, $playbackUrlData) {

		$urlFormats = array();
		$infoArray = NULL;

		foreach ( preg_split("/((\r?\n)|(\r\n?))/", $m3u8Content) as $line ) {

			if (startsWith($line, "#EXT-X-STREAM-INF:")) {

				$m3u8InfoCsv = str_replace("#EXT-X-STREAM-INF:", "", $line);
				$m3u8InfoArray = preg_split("/,(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/", $m3u8InfoCsv);
				foreach ($m3u8InfoArray as $m3u8Info) {
					if($infoArray === NULL){
						$infoArray = array();
					}
					$info = explode("=", $m3u8Info);
					$infoArray[ $info[0] ] = $info[1];
				}
			} elseif (strpos($line, ".m3u8")) { //check if line has extension m3u8 anywhere

				if(!empty($infoArray)){ //add info to urlFormats only if info array is not null (or) empty
					$urlFormatsWithCode = getUrlFormats($infoArray, $line, $playbackUrl, $playbackUrlData);
					$urlFormats[$urlFormatsWithCode[0]] = $urlFormatsWithCode[1];
				}

				//Reset m3u8InfoArray for next layer
				$infoArray = NULL;
			}
			
		}

		return $urlFormats;
	}
?>
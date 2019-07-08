<?php

require_once (realpath(dirname(__FILE__) . '/..') . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException; 


class VideoFormats {
    private $videoUrl;
    private $playbackUri;
    private $appState;
    private $appStateJson;
    private $headers;
    private $playbackUrlData;

    public function __construct($videoUrl) {
        //include all files under helper package
        foreach (glob("src/helper/*.php") as $helperFile) {
            require_once ($helperFile);
        }

        $this->videoUrl = $videoUrl;
        $this->playbackUri = null;
        $this->appState = null;
        $this->appStateJson = null;
        $this->headers = ['Hotstarauth' => generateHotstarAuth() , 'X-Country-Code' => 'IN', 'X-Platform-Code' => 'JIO'];
    }

    private function isValidHotstarUrl() {

        if (preg_match('/((https?:\/\/)?(www\.)?)?hotstar.com\/(?:.+?[\/-])+(?P<videoId>\d{10})([\/|\w]*)/', $this->videoUrl, $match)) {
            return $match["videoId"];
        }

        return "";
    }

    private function getVideoMetadata($content) {
        $metaData = array();
        foreach ($content as $contentName => $contentValue) {

            switch ($contentName) {

                case "title":
                    $metaData[$contentName] = $contentValue;
                break;
                case "genre":
                    $metaData[$contentName] = $contentValue;
                break;
                case "description":
                    $metaData[$contentName] = $contentValue;
                    $metaData["synopsis"] = $contentValue;
                    $metaData["comment"] = $contentValue;
                break;
                case "actors":
                    $actors = "";
                    foreach ($contentValue as $i => $actor) {
                        if (strlen($actors) != 0) {
                            $actors .= "/";
                        }
                        $actors .= $actor;
                    }
                    $metaData["artist"] = $actors;
                    $metaData["composer"] = $actors;
                    $metaData["album_artist"] = $actors;
                break;
                case "broadcastDate":
                    date_default_timezone_set("Asia/Calcutta");
                    $metaData["creation_time"] = "" . date("Y-m-d H:i:s", $contentValue);
                    $metaData["year"] = "" . date("Y", $contentValue);
                    $metaData["date"] = "" . date("Y", $contentValue);
                break;
                case "drmProtected":
                    $metaData[$contentName] = $contentValue;
                break;

                    //below meta-data may/may not be honored by ffmpeg
                    
                case "channelName":
                    $metaData["copyright"] = "©copyright " . $contentValue;
                break;
                case "episodeNo":
                    $metaData["episode_id"] = $contentValue;
                    $metaData["track"] = $contentValue;
                break;
                case "showName":
                    $metaData["show"] = $contentValue;
                break;
                case "seasonNo":
                    $metaData["season_number"] = $contentValue;
                break;
                case "playbackUri":
                    $metaData[$contentName] = $contentValue;
                break;
                default:
                    //add nothing
                    
                break;
            }
        }

        return $metaData;
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
            if (strrpos($this->videoUrl, "/") == strlen($this->videoUrl) - 1) {
                $this->videoUrl = substr($this->videoUrl, 0, strlen($this->videoUrl) - 1);
            }

            $videoId = $this->isValidHotstarUrl();

            if (empty($videoId)) {
                throw new Exception("Invalid Hotstar video URL");
            }

            $metaDataRootKey = str_replace('hotstar.com', '', substr($this->videoUrl, strpos($this->videoUrl, 'hotstar.com')));

            $fileContents = file_get_contents($this->videoUrl);
            file_put_contents("page.html", $fileContents);

            if (preg_match('%<script>window.APP_STATE=(.*?)</script>%', $fileContents, $match)) {
                $this->appState = $match[1];
            }
            else {
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
            
            $url = $this->playbackUri . "&tas=10000";
            $url2 = "https://api.hotstar.com/h/v2/play/in/contents/" . $videoId . "?";
            $url2 .= "desiredConfig=encryption:plain;ladder:phone,tv;package:hls,dash&";
            $url2 .= "client=mweb&";
            $url2 .= "clientVersion=6.18.0&";
            $url2 .= "deviceId=" . Ramsey\Uuid\Uuid::uuid4()->toString() . "&";
            $url2 .= "osName=Windows&";
            $url2 .= "osVersion=10";
            $playbackUriResponse = make_get_request($url2, $this->headers);
            $playbackUriResponseJson = json_decode($playbackUriResponse, true);
            if ($playbackUriResponseJson["statusCodeValue"] != 200) {
                throw new Exception("Error processing request for playbackUri");
            }
            $playBackSets = $playbackUriResponseJson["body"]["results"]["playBackSets"];
            foreach($playBackSets as $playBackSet) {
                if(strpos($playBackSet["playbackUrl"], "master.m3u8") !== false) {
                    $playbackUrlresponse = make_get_request($playBackSet["playbackUrl"], $this->headers);
                    $playbackUrlData = $this->getPlaybackUrlData($playBackSet["playbackUrl"]);
                    $url_formats = array_merge_recursive($url_formats, parseM3u8Content($playbackUrlresponse, $playBackSet["playbackUrl"], $playbackUrlData));
                }
            }
            
            $tmp_url_formats = array();
            foreach($url_formats as $url_formats_key => $url_formats_value)  {
                if(is_array($url_formats_value["STREAM-URL"])) {
                    $size = count($url_formats_value["STREAM-URL"]);
                    for($i=0; $i<$size; $i++) {
                        $tmpIndex = "$url_formats_key-$i";
                        $tmp_url_formats[$tmpIndex] = array();
                        foreach($url_formats_value as $k => $v) {
                            $tmp_url_formats[$tmpIndex][$k] = $v[$i];
                        }
                    }
                } else {
                    $tmp_url_formats[$url_formats_key] = $url_formats_value;
                }
            }
            $url_formats = $tmp_url_formats;            
            
            /*$playbackUrl = $playbackUriResponseJson["body"]["results"]["item"]["playbackUrl"];
            echo PHP_EOL.PHP_EOL."playbackUrl :".$playbackUrl.PHP_EOL.PHP_EOL;
            $this->playbackUrlData = $this->getPlaybackUrlData($playbackUrl);
            echo PHP_EOL.PHP_EOL."playbackUrlData :".$this->playbackUrlData.PHP_EOL.PHP_EOL;
            $playbackUrlData = $this->getPlaybackUrlData($playbackUrl);
            $playbackUrlresponse = make_get_request($playbackUrl, $this->headers);
            echo PHP_EOL.PHP_EOL."playbackUrlresponse :".$playbackUrlresponse.PHP_EOL.PHP_EOL;
            $url_formats = parseM3u8Content($playbackUrlresponse, $playbackUrl, $playbackUrlData); */
           
            $url_formats["metadata"] = $videoMetadata;
            $url_formats["videoId"] = $videoId;
            $url_formats["isError"] = false;
            
        }
        catch(Exception $e) {
            $url_formats["isError"] = true;
            $url_formats["errorMessage"] = $e->getMessage();
        }

        return json_encode($url_formats, true);
    }

}

?>

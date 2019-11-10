<?php

require_once(realpath(dirname(__FILE__) . '/..') . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class VideoFormats {
    private $videoUrl;
    private $playbackUri;
    private $appState;
    private $appStateJson;
    private $headers;
    private $playbackUrlData;
    private $downloadRetries;

    public function __construct($videoUrl) {
        //include all files under helper package
        foreach (glob("src/helper/*.php") as $helperFile) {
            require_once($helperFile);
        }
        require_once "mpdDashParser.php";

        $this->videoUrl = $videoUrl;
        $this->playbackUri = null;
        $this->appState = null;
        $this->appStateJson = null;
        $this->headers = ['Hotstarauth' => generateHotstarAuth() , 'X-Country-Code' => 'IN', 'X-Platform-Code' => 'JIO', 'Referer' => $videoUrl];
        $this->downloadRetries = 0;
    }

    private function getDownloadRetries() {
        return $this->downloadRetries++;
    }

    private function isValidHotstarUrl() {
        if (preg_match('/((https?:\/\/)?(www\.)?)?hotstar.com\/(?:.+?[\/-])+(?P<videoId>\d{10})([\/|\w]*)/', $this->videoUrl, $match)) {
            return $match["videoId"];
        }

        return "";
    }

    private function getVideoMetadata($content) {
        $metaData = [];
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
                    $dtFromBrodcastDate = new DateTime(date("Y-m-d H:i:s", $contentValue*1));
                    if (!$dtFromBrodcastDate) {
                        throw new \UnexpectedValueException("Could not parse the date with milliseconds, $contentValue");
                    }
                    $metaData["creation_time"] = $dtFromBrodcastDate->format("Y-m-d\TH:i:s.u\Z");
                    $metaData["year"] = $dtFromBrodcastDate->format("Y");
                    $metaData["date"] = $dtFromBrodcastDate->format("Y");
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
        $url_formats = [];
        $videoMetadata = [];

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

            if (preg_match('%<script>window.APP_STATE=(.*?)</script>%', $fileContents, $match)) {
                $this->appState = $match[1];
            } else {
                if ($this->getDownloadRetries() <= 5) {
                    return $this->getAvailableFormats();
                }
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
                if ($this->getDownloadRetries() <= 5) {
                    return $this->getAvailableFormats();
                }
                throw new Exception("Error processing request for playbackUri");
            }
            $playBackSets = $playbackUriResponseJson["body"]["results"]["playBackSets"];
            $dashAudioFormats = [];
            $dashVideoFormats = [];
            $dashWebmAudioFormats = [];
            $dashWebmVideoFormats = [];
            foreach ($playBackSets as $playBackSet) {
                if (strpos($playBackSet["playbackUrl"], "master.m3u8") !== false) {
                    $playbackUrlresponse = make_get_request($playBackSet["playbackUrl"], $this->headers);
                    $playbackUrlData = $this->getPlaybackUrlData($playBackSet["playbackUrl"]);
                    $url_formats = array_merge_recursive($url_formats, parseM3u8Content($playbackUrlresponse, $playBackSet["playbackUrl"], $playbackUrlData));
                } else {
                    $playbackUrlresponse = make_get_request($playBackSet["playbackUrl"], $this->headers);
                    $dashAudioOrVideoFormats = getDashAudioOrVideoFormats($playbackUrlresponse, $playBackSet["playbackUrl"]);
                    foreach ($dashAudioOrVideoFormats as $dashAvKey => $dashAvValue) {
                        if (strcmp($dashAvKey, "video") === 0) {
                            //Handle video DASH formats here
                            foreach ($dashAvValue as $dashVideoFormatId => $dashVideoFormatInfo) {
                                if (!array_key_exists($dashVideoFormatId, $dashVideoFormats)) {
                                    $dashVideoFormats[$dashVideoFormatId] = [];
                                }
                                $dashVideoFormats[$dashVideoFormatId][] = $dashVideoFormatInfo;
                            }
                        } elseif (strcmp($dashAvKey, "audio") === 0) {
                            //Handle audio DASH formats here
                            foreach ($dashAvValue as $dashAudioFormatId => $dashAudioFormatInfo) {
                                if (!array_key_exists($dashAudioFormatId, $dashAudioFormats)) {
                                    $dashAudioFormats[$dashAudioFormatId] = [];
                                }
                                $dashAudioFormats[$dashAudioFormatId][] = $dashAudioFormatInfo;
                            }
                        } elseif (strcmp($dashAvKey, "webm-video") === 0) {
                            //Handle DASH Webm video formats here
                            foreach ($dashAvValue as $dashVideoFormatId => $dashVideoFormatInfo) {
                                if (!array_key_exists($dashVideoFormatId, $dashWebmVideoFormats)) {
                                    $dashWebmVideoFormats[$dashVideoFormatId] = [];
                                }
                                $dashWebmVideoFormats[$dashVideoFormatId][] = $dashVideoFormatInfo;
                            }
                        } elseif (strcmp($dashAvKey, "webm-audio") === 0) {
                            //Handle DASH Webm video formats here
                            foreach ($dashAvValue as $dashAudioFormatId => $dashAudioFormatInfo) {
                                if (!array_key_exists($dashAudioFormatId, $dashWebmAudioFormats)) {
                                    $dashWebmAudioFormats[$dashAudioFormatId] = [];
                                }
                                $dashWebmAudioFormats[$dashAudioFormatId][] = $dashAudioFormatInfo;
                            }
                        } else {
                            throw new Exception("Invalid dashAvKey $dashAvKey");
                        }
                    }
                }
            }
            
            $tmp_url_formats = [];
            //Iterate video formats
            foreach ($url_formats as $url_formats_key => $url_formats_value) {
                if (is_array($url_formats_value["STREAM-URL"])) {
                    $size = count($url_formats_value["STREAM-URL"]);
                    for ($i=0; $i<$size; $i++) {
                        $tmpIndex = "$url_formats_key-$i";
                        $tmp_url_formats[$tmpIndex] = [];
                        foreach ($url_formats_value as $k => $v) {
                            $tmp_url_formats[$tmpIndex][$k] = $v[$i];
                        }
                    }
                } else {
                    $tmp_url_formats[$url_formats_key] = $url_formats_value;
                }
            }
            
            $url_formats = [];
            
            $url_formats["video"] = $tmp_url_formats;
            
            //Iterate dash video formats
            $tmp_url_formats = [];
            foreach ($dashAudioFormats as $dashAFormats) {
                $formatPrefix ="dash-audio";
                $kFormNumber = $dashAFormats[0]["K-FORM-NUMBER"];
                if (count($dashAFormats) > 1) {
                    $fCounter = 0;
                    foreach ($dashAFormats as $dashAFormatInfo) {
                        $tmp_url_formats["$formatPrefix-$kFormNumber-$fCounter"] = $dashAFormatInfo;
                        $fCounter++;
                    }
                } else {
                    $tmp_url_formats["$formatPrefix-$kFormNumber"] = $dashAFormats[0];
                }
            }
            $url_formats["dash-audio"] = $tmp_url_formats;
            
            //Iterate dash video formats
            $tmp_url_formats = [];
            foreach ($dashVideoFormats as $dashVFormats) {
                $formatPrefix ="dash-video";
                $kFormNumber = $dashVFormats[0]["K-FORM-NUMBER"];
                if (count($dashVFormats) > 1) {
                    $fCounter = 0;
                    foreach ($dashVFormats as $dashVFormatInfo) {
                        $tmp_url_formats["$formatPrefix-$kFormNumber-$fCounter"] = $dashVFormatInfo;
                        $fCounter++;
                    }
                } else {
                    $tmp_url_formats["$formatPrefix-$kFormNumber"] = $dashVFormats[0];
                }
            }
            $url_formats["dash-video"] = $tmp_url_formats;
            
            //Iterate dash webm audio formats
            $tmp_url_formats = [];
            foreach ($dashWebmAudioFormats as $dashWebmAFormats) {
                $formatPrefix ="dash-webm-audio";
                $kFormNumber = $dashWebmAFormats[0]["ID"];
                if (count($dashWebmAFormats) > 1) {
                    $fCounter = 0;
                    foreach ($dashWebmAFormats as $dashWebmAFormatInfo) {
                        $tmp_url_formats["$formatPrefix-$kFormNumber-$fCounter"] = $dashWebmAFormatInfo;
                        $fCounter++;
                    }
                } else {
                    $tmp_url_formats["$formatPrefix-$kFormNumber"] = $dashWebmAFormats[0];
                }
            }
            $url_formats["dash-webm-audio"] = $tmp_url_formats;
            
            //Iterate dash webm video formats
            $tmp_url_formats = [];
            foreach ($dashWebmVideoFormats as $dashWebmVFormats) {
                $formatPrefix ="dash-webm-video";
                $kFormNumber = $dashWebmVFormats[0]["ID"];
                if (count($dashWebmVFormats) > 1) {
                    $fCounter = 0;
                    foreach ($dashWebmVFormats as $dashWebmVFormatInfo) {
                        $tmp_url_formats["$formatPrefix-$kFormNumber-$fCounter"] = $dashWebmVFormatInfo;
                        $fCounter++;
                    }
                } else {
                    $tmp_url_formats["$formatPrefix-$kFormNumber"] = $dashWebmVFormats[0];
                }
            }
            $url_formats["dash-webm-video"] = $tmp_url_formats;
                    
            //API v1.0
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
        } catch (Exception $e) {
            $url_formats["isError"] = true;
            $url_formats["errorMessage"] = $e->getMessage();
        }

        return json_encode($url_formats, true);
    }
}

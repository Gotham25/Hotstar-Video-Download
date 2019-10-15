<?php

require_once("utils.php");

function parseM3u8Content($m3u8Content, $playbackUrl, $playbackUrlData) {
    $url_formats = [];
    $infoArray = null;

    foreach (preg_split("/((\r?\n)|(\r\n?))/", $m3u8Content) as $line) {
        if (startsWith($line, "#EXT-X-STREAM-INF:")) {
            $m3u8InfoCsv = str_replace("#EXT-X-STREAM-INF:", "", $line);
            $m3u8InfoArray = preg_split("/,(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/", $m3u8InfoCsv);
            foreach ($m3u8InfoArray as $m3u8Info) {
                if ($infoArray === null) {
                    $infoArray = [];
                }
                $info = explode("=", $m3u8Info);
                $infoArray[$info[0]] = $info[1];
            }
        } elseif (strpos($line, ".m3u8")) { //check if line has extension m3u8 anywhere
            if (!empty($infoArray)) { //add info to url_formats only if info array is not null (or) empty
                $kFormAverageBandwidthOrBandwidth = getKForm((int)(isset($infoArray["AVERAGE-BANDWIDTH"]) ? $infoArray["AVERAGE-BANDWIDTH"] : $infoArray["BANDWIDTH"]));
                $formatCode = "hls-$kFormAverageBandwidthOrBandwidth"; //eg hls-281 for 281469
                $streamUrl = startsWith($line, "http") ? $line : str_replace("master.m3u8", $line, $playbackUrl); //if starts with http then it's direct url
                if (strpos($streamUrl, "~acl=/*~hmac") === false) { //check if the url data contains hmac. If not, add it from playback url
                    if (strpos($streamUrl, "?") === false) { //checking if URL does have any query string already
                        $streamUrl .= "?";
                    }
                    $streamUrl .= "&$playbackUrlData";
                }
                $infoArray["STREAM-URL"] = $streamUrl;
                $url_formats[$formatCode] = $infoArray;
            }

            //Reset m3u8InfoArray for next layer
            $infoArray = null;
        } else {
            //do nothing
        }
    }

    return $url_formats;
}

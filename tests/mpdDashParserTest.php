<?php

use PHPUnit\Framework\TestCase;

require_once("src".DIRECTORY_SEPARATOR."mpdDashParser.php");

class MpdDashParserTest extends TestCase {
    protected static $mpdTestCasesContents;

    protected static $playbackUrl;

    public static function setUpBeforeClass(): void {
        self::$mpdTestCasesContents = [];
        self::$playbackUrl = "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629";
        $contentCount = 0;
        //Get all testCase xml files in resources directory
        foreach (glob(dirname(__FILE__).DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."*.xml") as $mpdDashFile) {
            $xmlContents = file_get_contents($mpdDashFile);
            self::$mpdTestCasesContents[$contentCount++] = $xmlContents;
        }
    }

    private function getExpectedDashFormat1() {
        return [
            "video" => [
                "109k" => [
                    "BANDWIDTH" => 109860,
                    "K-FORM-NUMBER" => "109",
                    "CODECS" => "mp4_dash container, avc1.42C00C",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "320x180",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 109k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/1/init.mp4",
                    "STREAM-URL" => "video/avc1/1/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "185k" => [
                    "BANDWIDTH" => 185964,
                    "K-FORM-NUMBER" => "185",
                    "CODECS" => "mp4_dash container, avc1.42C015",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "426x240",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 185k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/2/init.mp4",
                    "STREAM-URL" => "video/avc1/2/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "259k" => [
                    "BANDWIDTH" => 259292,
                    "K-FORM-NUMBER" => "259",
                    "CODECS" => "mp4_dash container, avc1.4D401E",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "640x360",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 259k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/3/init.mp4",
                    "STREAM-URL" => "video/avc1/3/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "488k" => [
                    "BANDWIDTH" => 488447,
                    "K-FORM-NUMBER" => "488",
                    "CODECS" => "mp4_dash container, avc1.4D401F",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "854x480",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 488k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/4/init.mp4",
                    "STREAM-URL" => "video/avc1/4/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "908k" => [
                    "BANDWIDTH" => 908121,
                    "K-FORM-NUMBER" => "908",
                    "CODECS" => "mp4_dash container, avc1.640028",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1280x720",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 908k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/5/init.mp4",
                    "STREAM-URL" => "video/avc1/5/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "1935k" => [
                    "BANDWIDTH" => 1935478,
                    "K-FORM-NUMBER" => "1935",
                    "CODECS" => "mp4_dash container, avc1.640028",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1920x1080",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 1935k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/6/init.mp4",
                    "STREAM-URL" => "video/avc1/6/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
            ],
            "audio" => [
                "33k" => [
                    "BANDWIDTH" => 33803,
                    "K-FORM-NUMBER" => "33",
                    "CODECS" => "mp4_dash container, mp4a.40.2",
                    "MIME-TYPE" => "audio/mp4",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH audio 33k",
                    "RESOLUTION" => "(48000 Hz)",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "audio/und/mp4a/1/init.mp4",
                    "STREAM-URL" => "audio/und/mp4a/1/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "65k" => [
                    "BANDWIDTH" => 65594,
                    "K-FORM-NUMBER" => "65",
                    "CODECS" => "mp4_dash container, mp4a.40.2",
                    "MIME-TYPE" => "audio/mp4",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH audio 65k",
                    "RESOLUTION" => "(48000 Hz)",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "audio/und/mp4a/2/init.mp4",
                    "STREAM-URL" => "audio/und/mp4a/2/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
            ],
        ];
    }

    private function getExpectedDashFormat2() {
        return [
            "video" => [
                "175k" => [
                    "BANDWIDTH" => 175588,
                    "K-FORM-NUMBER" => "175",
                    "CODECS" => "mp4_dash container, avc1.42C015",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "426x240",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 175k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/1/init.mp4",
                    "STREAM-URL" => "video/avc1/1/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "315k" => [
                    "BANDWIDTH" => 315475,
                    "K-FORM-NUMBER" => "315",
                    "CODECS" => "mp4_dash container, avc1.4D401E",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "640x360",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 315k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/2/init.mp4",
                    "STREAM-URL" => "video/avc1/2/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "584k" => [
                    "BANDWIDTH" => 584996,
                    "K-FORM-NUMBER" => "584",
                    "CODECS" => "mp4_dash container, avc1.4D401F",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "854x480",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 584k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/3/init.mp4",
                    "STREAM-URL" => "video/avc1/3/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "1172k" => [
                    "BANDWIDTH" => 1172515,
                    "K-FORM-NUMBER" => "1172",
                    "CODECS" => "mp4_dash container, avc1.640028",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1280x720",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 1172k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/4/init.mp4",
                    "STREAM-URL" => "video/avc1/4/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "2563k" => [
                    "BANDWIDTH" => 2563637,
                    "K-FORM-NUMBER" => "2563",
                    "CODECS" => "mp4_dash container, avc1.640028",
                    "MIME-TYPE" => "video/mp4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1920x1080",
                    "FRAME-RATE" => "25",
                    "K-FORM" => "DASH video 2563k",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "video/avc1/5/init.mp4",
                    "STREAM-URL" => "video/avc1/5/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
            ],
            "audio" => [
                "33k" => [
                    "BANDWIDTH" => 33803,
                    "K-FORM-NUMBER" => "33",
                    "CODECS" => "mp4_dash container, mp4a.40.2",
                    "MIME-TYPE" => "audio/mp4",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH audio 33k",
                    "RESOLUTION" => "(48000 Hz)",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "audio/und/mp4a/1/init.mp4",
                    "STREAM-URL" => "audio/und/mp4a/1/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
                "65k" => [
                    "BANDWIDTH" => 65594,
                    "K-FORM-NUMBER" => "65",
                    "CODECS" => "mp4_dash container, mp4a.40.2",
                    "MIME-TYPE" => "audio/mp4",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH audio 65k",
                    "RESOLUTION" => "(48000 Hz)",
                    "TOTAL-SEGMENTS" => 327,
                    "INIT-URL" => "audio/und/mp4a/2/init.mp4",
                    "STREAM-URL" => "audio/und/mp4a/2/seg-\$Number\$.m4s",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629",
                ],
            ],
        ];
    }
    
    public function testgetDashAudioOrVideoFormats1_VideoFormats_ProducesCorrectVideoFormats() {
        $expectedDashFormat = $this->getExpectedDashFormat1();
        $actualDashFormat = getDashAudioOrVideoFormats(self::$mpdTestCasesContents[0], self::$playbackUrl);
        $this->assertEquals($expectedDashFormat, $actualDashFormat);
    }
    
    public function testgetDashAudioOrVideoFormats2_VideoFormats_ProducesCorrectVideoFormats() {
        $expectedDashFormat = $this->getExpectedDashFormat2();
        $actualDashFormat = getDashAudioOrVideoFormats(self::$mpdTestCasesContents[1], self::$playbackUrl);
        $this->assertEquals($expectedDashFormat, $actualDashFormat);
    }
}

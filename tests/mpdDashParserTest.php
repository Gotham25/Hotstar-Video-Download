<?php

use PHPUnit\Framework\TestCase;

require_once("src".DIRECTORY_SEPARATOR."mpdDashParser.php");

class MpdDashParserTest extends TestCase {
    protected static $mpdTestCasesContents;

    protected static $playbackUrl;
    protected static $playbackWebmUrl;

    public static function setUpBeforeClass(): void {
        self::$mpdTestCasesContents = [];
        self::$playbackUrl = "https://hses1.akamaized.net/videos/vijay_hd/ayutha_ezhuthu/b2f43183e7/57/1100027262/1568917929031/80a7077169517f73ec95efaa3bf0ad40/master.mpd?ladder=phone&hdnea=st=1569344736~exp=1569348336~acl=/*~hmac=ac0904136784be6a7c096e9cc0cf00595454811f010de48b351862d8c5ff2629";
        self::$playbackWebmUrl = "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/master.mpd?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb";
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
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
                    'WEBMFORMAT' => false,
                ],
            ],
        ];
    }

    private function getExpectedDashWebmFormat() {
        return [
            "webm-video" => [
                "373k" => [
                    "BANDWIDTH" => 373152,
                    "K-FORM-NUMBER" => "373",
                    "CODECS" => "webm_dash container, vp09.00.21.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "0",
                    "STREAM" => "video only",
                    "RESOLUTION" => "640x360",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 373k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_2.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_2.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "572k" => [
                    "BANDWIDTH" => 572712,
                    "K-FORM-NUMBER" => "572",
                    "CODECS" => "webm_dash container, vp09.00.11.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "3",
                    "STREAM" => "video only",
                    "RESOLUTION" => "320x180",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 572k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_0.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_0.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "684k" => [
                    "BANDWIDTH" => 684002,
                    "K-FORM-NUMBER" => "684",
                    "CODECS" => "webm_dash container, vp09.00.30.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "4",
                    "STREAM" => "video only",
                    "RESOLUTION" => "854x480",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 684k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_3.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_3.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "881k" => [
                    "BANDWIDTH" => 881626,
                    "K-FORM-NUMBER" => "881",
                    "CODECS" => "webm_dash container, vp09.00.20.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "5",
                    "STREAM" => "video only",
                    "RESOLUTION" => "426x240",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 881k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_1.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_1.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "1300k" => [
                    "BANDWIDTH" => 1300096,
                    "K-FORM-NUMBER" => "1300",
                    "CODECS" => "webm_dash container, vp09.00.31.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "6",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1280x720",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 1300k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_4.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_4.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "2795k" => [
                    "BANDWIDTH" => 2795682,
                    "K-FORM-NUMBER" => "2795",
                    "CODECS" => "webm_dash container, vp09.00.40.08.01.01.01.02.01",
                    "MIME-TYPE" => "video/webm",
                    "ID" => "7",
                    "STREAM" => "video only",
                    "RESOLUTION" => "1920x1080",
                    "FRAME-RATE" => 25,
                    "K-FORM" => "DASH webm video 2795k",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_5.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/video_5.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
            ],
            "webm-audio" => [
                "52k" => [
                    "BANDWIDTH" => 52792,
                    "K-FORM-NUMBER" => "52",
                    "CODECS" => "webm_dash container, opus",
                    "MIME-TYPE" => "audio/webm",
                    "ID" => "1",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH webm audio 52k",
                    "RESOLUTION" => "(48000 Hz)",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/audio_0.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/audio_0.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
                "100k" => [
                    "BANDWIDTH" => 100290,
                    "K-FORM-NUMBER" => "100",
                    "CODECS" => "webm_dash container, opus",
                    "MIME-TYPE" => "audio/webm",
                    "ID" => "2",
                    "STREAM" => "audio only",
                    "SAMPLING-RATE" => "(48000 Hz)",
                    "K-FORM" => "DASH webm audio 100k",
                    "RESOLUTION" => "(48000 Hz)",
                    "STREAM-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/audio_1.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "PLAYBACK-URL" => "https://hses1.akamaized.net/videos/vijay_hd/super_singer/4f883cf7d9/46/1100027680/1569699018054/25484e0503dacf222899253abd2e53b0/audio_1.webm?hdnea=st=1571070705~exp=1571074305~acl=/*~hmac=5beb6181dbd0662b652e5788e75c55a54abed66fd186bb95125845f17a79d9fb",
                    "WEBMFORMAT" => true,
                ],
            ],
        ];
    }

    public function testGetDashAudioOrVideoFormats1_VideoFormats_ProducesCorrectVideoFormats() {
        $expectedDashFormat = $this->getExpectedDashFormat1();
        $actualDashFormat = getDashAudioOrVideoFormats(self::$mpdTestCasesContents[0], self::$playbackUrl);
        $this->assertEquals($expectedDashFormat, $actualDashFormat);
    }
    
    public function testGetDashAudioOrVideoFormats2_VideoFormats_ProducesCorrectVideoFormats() {
        $expectedDashFormat = $this->getExpectedDashFormat2();
        $actualDashFormat = getDashAudioOrVideoFormats(self::$mpdTestCasesContents[1], self::$playbackUrl);
        $this->assertEquals($expectedDashFormat, $actualDashFormat);
    }

    public function testGetDashAudioOrVideoWebmFormats_VideoFormats_ProducesCorrectVideoFormats() {
        $expectedDashFormat = $this->getExpectedDashWebmFormat();
        $actualDashFormat = getDashAudioOrVideoFormats(self::$mpdTestCasesContents[2], self::$playbackWebmUrl);
        $this->assertEquals($expectedDashFormat, $actualDashFormat);
    }
}

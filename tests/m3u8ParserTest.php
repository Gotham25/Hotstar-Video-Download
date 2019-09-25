<?php

require_once("src/helper/m3u8Parser.php");
use PHPUnit\Framework\TestCase;

class M3u8ParserTest extends TestCase {
    protected static $m3u8TestCasesContents;

    public static function setUpBeforeClass(): void {
        self::$m3u8TestCasesContents = [];
        $contentCount = 0;
        //Get all testCase m3u8 files in resources directory
        foreach (glob(dirname(__FILE__).DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."*.m3u8") as $m3u8File) {
            $fileContents = file_get_contents($m3u8File);
            self::$m3u8TestCasesContents[$contentCount++] = $fileContents;
        }
    }

    public function testm3u8Parser_ValidM3u8Content1_ProducesVideoFormats() {
        $playbackUrl = "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/master.m3u8?hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6";
        $playbackUrlData = "hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6";
        $expectedM3u8ParsedContent = [
            "hls-167" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "167271",
                "RESOLUTION" => "320x180",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_0_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ],

            "hls-327" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "327344",
                "RESOLUTION" => "320x180",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_1_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ],

            "hls-552" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "552127",
                "RESOLUTION" => "416x234",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_2_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ],

            "hls-960" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "960823",
                "RESOLUTION" => "640x360",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_3_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ],

            "hls-1472" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "1472714",
                "RESOLUTION" => "720x404",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_4_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ],

            "hls-2188" => [
                "PROGRAM-ID" => "1",
                "BANDWIDTH" => "2188953",
                "RESOLUTION" => "1280x720",
                "CODECS" => "\"avc1.66.30, mp4a.40.2\"",
                "CLOSED-CAPTIONS" => "NONE",
                "STREAM-URL" => "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/index_5_av.m3u8?null=0&id=AgCdLeTnMSxxookre1yyOZrUVjGsAjTrI2jaZKKjKzRKekEWQ81I2j3HSzMs2ZZcxJTgLWz%2f4cRk1A%3d%3d&hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6"
            ]
        ];

        $actualM3u8ParsedContent = parseM3u8Content(self::$m3u8TestCasesContents[0], $playbackUrl, $playbackUrlData);
        $this->assertEquals($expectedM3u8ParsedContent, $actualM3u8ParsedContent);
    }

    public function testm3u8Parser_ValidM3u8Content2_ProducesVideoFormats() {
        $playbackUrl = "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669";
        $playbackUrlData = "hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669";
        $expectedM3u8ParsedContent = [
            "hls-141" => [
                "BANDWIDTH" => "157168",
                "AVERAGE-BANDWIDTH" => "141703",
                "CODECS" => "\"avc1.42c015,mp4a.40.2\"",
                "RESOLUTION" => "320x180",
                "FRAME-RATE" => "15.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer1_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-280" => [
                "BANDWIDTH" => "304560",
                "AVERAGE-BANDWIDTH" => "280690",
                "CODECS" => "\"avc1.42c015,mp4a.40.2\"",
                "RESOLUTION" => "320x180",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer2_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-505" => [
                "BANDWIDTH" => "555477",
                "AVERAGE-BANDWIDTH" => "505575",
                "CODECS" => "\"avc1.66.30,mp4a.40.2\"",
                "RESOLUTION" => "416x234",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer3_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-914" => [
                "BANDWIDTH" => "1014698",
                "AVERAGE-BANDWIDTH" => "914365",
                "CODECS" => "\"avc1.66.30,mp4a.40.2\"",
                "RESOLUTION" => "640x360",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer4_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-1425" => [
                "BANDWIDTH" => "1588474",
                "AVERAGE-BANDWIDTH" => "1425351",
                "CODECS" => "\"avc1.66.30,mp4a.40.2\"",
                "RESOLUTION" => "720x404",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer5_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-2140" => [
                "BANDWIDTH" => "2380832",
                "AVERAGE-BANDWIDTH" => "2140799",
                "CODECS" => "\"avc1.42c01f,mp4a.40.2\"",
                "RESOLUTION" => "1280x720",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer6_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-3297" => [
                "BANDWIDTH" => "3656474",
                "AVERAGE-BANDWIDTH" => "3297345",
                "CODECS" => "\"avc1.640029,mp4a.40.2\"",
                "RESOLUTION" => "1600x900",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer7_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ],

            "hls-4830" => [
                "BANDWIDTH" => "5360256",
                "AVERAGE-BANDWIDTH" => "4830306",
                "CODECS" => "\"avc1.640032,mp4a.40.2\"",
                "RESOLUTION" => "1920x1080",
                "FRAME-RATE" => "25.000",
                "STREAM-URL" => "https://hsdesinova.akamaized.net/video/vijay_hd/chinnathambi/92df3509e0/337/master_Layer8_.m3u8?hdnea=st=1551575720~exp=1551577520~acl=/*~hmac=75f2905ca5d5f79a674205e3e0e25b622ff9d08f77dbc2d50374d70ddb706669"
            ]
        ];

        $actualM3u8ParsedContent = parseM3u8Content(self::$m3u8TestCasesContents[1], $playbackUrl, $playbackUrlData);
        $this->assertEquals($expectedM3u8ParsedContent, $actualM3u8ParsedContent);
    }

    public function testm3u8Parser_ValidM3u8Content3_ProducesVideoFormats() {
        $playbackUrl = "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/master.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7";
        $playbackUrlData = "hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7";
        $expectedM3u8ParsedContent = [
            "hls-178" => [
                "AVERAGE-BANDWIDTH" => "178039",
                "BANDWIDTH" => "236504",
                "CODECS" => "\"avc1.42C00C,mp4a.40.2\"",
                "RESOLUTION" => "320x180",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-1/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ],

            "hls-234" => [
                "AVERAGE-BANDWIDTH" => "234185",
                "BANDWIDTH" => "324488",
                "CODECS" => "\"avc1.42C015,mp4a.40.2\"",
                "RESOLUTION" => "426x240",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-2/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ],

            "hls-361" => [
                "AVERAGE-BANDWIDTH" => "361956",
                "BANDWIDTH" => "499704",
                "CODECS" => "\"avc1.4D401E,mp4a.40.2\"",
                "RESOLUTION" => "640x360",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-3/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ],

            "hls-576" => [
                "AVERAGE-BANDWIDTH" => "576455",
                "BANDWIDTH" => "877584",
                "CODECS" => "\"avc1.4D401F,mp4a.40.2\"",
                "RESOLUTION" => "854x480",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-4/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ],

            "hls-1003" => [
                "AVERAGE-BANDWIDTH" => "1003957",
                "BANDWIDTH" => "1608904",
                "CODECS" => "\"avc1.4D401F,mp4a.40.2\"",
                "RESOLUTION" => "1280x720",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-5/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ],

            "hls-1987" => [
                "AVERAGE-BANDWIDTH" => "1987031",
                "BANDWIDTH" => "3017776",
                "CODECS" => "\"avc1.640028,mp4a.40.2\"",
                "RESOLUTION" => "1920x1080",
                "STREAM-URL" => "https://hses.akamaized.net/videos/vijay_hd/chinnathambi/0b3c2675ea/362/1100017417/phone/media-6/index.m3u8?hdnea=st=1551575749~exp=1551577549~acl=/*~hmac=45b40d19a096f5a9e1d0eb68c2c9577ae349443dde273a9ce393f17686badcb7"
            ]
        ];

        $actualM3u8ParsedContent = parseM3u8Content(self::$m3u8TestCasesContents[2], $playbackUrl, $playbackUrlData);
        $this->assertEquals($expectedM3u8ParsedContent, $actualM3u8ParsedContent);
    }
}

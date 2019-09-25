<?php
use PHPUnit\Framework\TestCase;

require_once "src/VideoFormats.php";

class VideoFormatsTest extends TestCase {
    private $videoFormats;
    protected static $testNameToUrlMap;

    public static function setUpBeforeClass(): void {
        self::$testNameToUrlMap = [
            "testGetAvailableFormats_WithoutDashVideoFormats_ProducesCorrectVideoFormats" => "http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795",
            "testGetAvailableFormats_WithDashVideoFormats_ProducesCorrectVideoFormats" => "http://www.hotstar.com/tv/ayudha-ezhuthu/s-2213/indra-on-a-mission/1100027262",
            "testGetAvailableFormats_VideoFormats_Produces404Error" => "https://www.hotstar.com/sports/football/arsenal-vs-liverpool/2001707928",
            "testGetAvailableFormats_VideoFormats_ProducesDRMProtectedError" => "https://www.hotstar.com/sports/cricket/vivo-ipl-2018/mumbai-indians-vs-chennai-super-kings-m186490/match-clips/2018-match-1-mi-vs-csk/2001705598",
            "testGetAvailableFormats_VideoFormats_ProducesInvalidUrlError" => "https://www.blah.com",
        ];
    }
    
    protected function setUp() : void {
        $this->initVideoFormats(self::$testNameToUrlMap[$this->getName()]);
    }

    private function initVideoFormats($videoUrl) {
        $this->videoFormats = new VideoFormats($videoUrl);
    }

    private function getActualFormats() {
        return json_decode($this->videoFormats->getAvailableFormats(), true);
    }

    public function testGetAvailableFormats_WithoutDashVideoFormats_ProducesCorrectVideoFormats() {
        $expectedFormats = [
            "hls-167" => "320x180",
            "hls-327" => "320x180",
            "hls-552" => "416x234",
            "hls-960" => "640x360",
            "hls-1472" => "720x404",
        ];

        $actualFormats = $this->getActualFormats();

        $this->assertFalse($actualFormats["isError"]);
        $this->assertEquals($expectedFormats["hls-167"], $actualFormats["video"]["hls-167"]["RESOLUTION"]);
        $this->assertEquals($expectedFormats["hls-327"], $actualFormats["video"]["hls-327"]["RESOLUTION"]);
        $this->assertEquals($expectedFormats["hls-552"], $actualFormats["video"]["hls-552"]["RESOLUTION"]);
        $this->assertEquals($expectedFormats["hls-960"], $actualFormats["video"]["hls-960"]["RESOLUTION"]);
        $this->assertEquals($expectedFormats["hls-1472"], $actualFormats["video"]["hls-1472"]["RESOLUTION"]);
    }

    public function testGetAvailableFormats_WithDashVideoFormats_ProducesCorrectVideoFormats() {
        $expectedFormats = [
            "video" => [
                "hls-177-0" => "320x180",
                "hls-177-1" => "320x180",
                "hls-291-0" => "426x240",
                "hls-291-1" => "426x240",
                "hls-361-0" => "640x360",
                "hls-361-1" => "640x360",
                "hls-528-0" => "854x480",
                "hls-528-1" => "854x480",
                "hls-826-0" => "1280x720",
                "hls-826-1" => "1280x720",
                "hls-1765-0" => "1920x1080",
                "hls-1765-1" => "1920x1080",
                "hls-234" => "426x240",
                "hls-404" => "640x360",
                "hls-536" => "854x480",
                "hls-924" => "1280x720",
                "hls-2037" => "1920x1080",
            ],
            "dash-audio" => [
                "dash-audio-33-0" => "(48000 Hz)",
                "dash-audio-33-1" => "(48000 Hz)",
                "dash-audio-33-2" => "(48000 Hz)",
                "dash-audio-65-0" => "(48000 Hz)",
                "dash-audio-65-1" => "(48000 Hz)",
                "dash-audio-65-2" => "(48000 Hz)",
            ],
            "dash-video" => [
                "dash-video-109-0" => "320x180",
                "dash-video-109-1" => "320x180",
                "dash-video-185-0" => "426x240",
                "dash-video-185-1" => "426x240",
                "dash-video-259-0" => "640x360",
                "dash-video-259-1" => "640x360",
                "dash-video-488-0" => "854x480",
                "dash-video-488-1" => "854x480",
                "dash-video-908-0" => "1280x720",
                "dash-video-908-1" => "1280x720",
                "dash-video-1935-0" => "1920x1080",
                "dash-video-1935-1" => "1920x1080",
                "dash-video-175" => "426x240",
                "dash-video-315" => "640x360",
                "dash-video-584" => "854x480",
                "dash-video-1172" => "1280x720",
                "dash-video-2563" => "1920x1080",
            ],
        ];
        $actualFormats = $this->getActualFormats();
        $this->assertEquals(6, count($actualFormats));
        foreach ($actualFormats as $videoType => $formats) {
            if ((strcmp($videoType, "video")==0) || startsWith($videoType, "dash-")) {
                foreach ($formats as $formatCode => $formatInfo) {
                    $this->assertEquals($expectedFormats[$videoType][$formatCode], $formatInfo["RESOLUTION"]);
                }
            }
        }
    }

    public function testGetAvailableFormats_VideoFormats_Produces404Error() {
        $actualFormats = $this->getActualFormats();

        $this->assertTrue($actualFormats["isError"]);
        $this->assertEquals("file_get_contents(https://www.hotstar.com/sports/football/arsenal-vs-liverpool/2001707928): failed to open stream: HTTP request failed! HTTP/1.0 404 Not Found", str_replace(array(
            "\n",
            "\r"
        ), '', $actualFormats["errorMessage"]));
    }

    public function testGetAvailableFormats_VideoFormats_ProducesDRMProtectedError() {
        $actualFormats = $this->getActualFormats();

        $this->assertTrue($actualFormats["isError"]);
        $this->assertEquals("The video is DRM Protected", str_replace(array(
            "\n",
            "\r"
        ), '', $actualFormats["errorMessage"]));
    }

    public function testGetAvailableFormats_VideoFormats_ProducesInvalidUrlError() {
        $actualFormats = $this->getActualFormats();

        $this->assertTrue($actualFormats["isError"]);
        $this->assertEquals("Invalid Hotstar video URL", str_replace(array(
            "\n",
            "\r"
        ), '', $actualFormats["errorMessage"]));
    }
}

<?php
use PHPUnit\Framework\TestCase;
include "src/VideoFormats.php";

class VideoFormatsTest extends TestCase
{
	private $videoFormats;
	
	protected function setUp() { 
	   $this->initVideoFormats("http://www.hotstar.com/tv/chinnathambi/15301/chinnathambi-yearns-for-nandini/1100003795"); 
 }
	
	private function initVideoFormats($videoUrl) {
		$this->videoFormats = new VideoFormats($videoUrl);
	}
	
    function testGetAvailableFormats_VideoFormats_ProducesCorrectVideoFormats()
    {
        $expectedFormats = array(
			"hls-167" => "320x180",
			"hls-327" => "320x180",
			"hls-552" => "416x234",
			"hls-960" => "640x360",
			"hls-1472" => "720x404",
			"hls-2188" => "1280x720",
		);
		
		$actualFormats = json_decode($this->videoFormats->getAvailableFormats(), true);
		
		$this->assertFalse($actualFormats["isError"]);
		$this->assertEquals($expectedFormats["hls-167"], $actualFormats["hls-167"]["RESOLUTION"]);
		$this->assertEquals($expectedFormats["hls-327"], $actualFormats["hls-327"]["RESOLUTION"]);
		$this->assertEquals($expectedFormats["hls-552"], $actualFormats["hls-552"]["RESOLUTION"]);
		$this->assertEquals($expectedFormats["hls-960"], $actualFormats["hls-960"]["RESOLUTION"]);
		$this->assertEquals($expectedFormats["hls-1472"], $actualFormats["hls-1472"]["RESOLUTION"]);
		$this->assertEquals($expectedFormats["hls-2188"], $actualFormats["hls-2188"]["RESOLUTION"]);
    }
	
	function testGetAvailableFormats_VideoFormats_Produces404Error()
    {
		$this->initVideoFormats("https://www.hotstar.com/sports/football/arsenal-vs-liverpool/2001707928");
		
		$actualFormats = json_decode($this->videoFormats->getAvailableFormats(), true);
		
		$this->assertTrue($actualFormats["isError"]);
		$this->assertEquals("file_get_contents(https://www.hotstar.com/sports/football/arsenal-vs-liverpool/2001707928): failed to open stream: HTTP request failed! HTTP/1.0 404 Not Found", str_replace(array("\n", "\r"), '', $actualFormats["errorMessage"]));
    }
	
	function testGetAvailableFormats_VideoFormats_ProducesDRMProtectedError()
    {
		$this->initVideoFormats("https://www.hotstar.com/sports/cricket/vivo-ipl-2018/mumbai-indians-vs-chennai-super-kings-m186490/match-clips/2018-match-1-mi-vs-csk/2001705598");
		
		$actualFormats = json_decode($this->videoFormats->getAvailableFormats(), true);
		
		$this->assertTrue($actualFormats["isError"]);
		$this->assertEquals("The video is DRM Protected", str_replace(array("\n", "\r"), '', $actualFormats["errorMessage"]));
    }
	
	function testGetAvailableFormats_VideoFormats_ProducesInvalidUrlError()
    {
		$this->initVideoFormats("https://www.blah.com");
		
		$actualFormats = json_decode($this->videoFormats->getAvailableFormats(), true);
		
		$this->assertTrue($actualFormats["isError"]);
		$this->assertEquals("Invalid Hotstar video URL", str_replace(array("\n", "\r"), '', $actualFormats["errorMessage"]));
    }
	
}
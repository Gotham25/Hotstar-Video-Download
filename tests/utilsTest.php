<?php
require_once("src/helper/hotstarauthgenerator.php");
require_once("src/helper/utils.php");

use PHPUnit\Framework\TestCase;

class utilsTest extends TestCase {
    public function testKForm_InvalidNumber_ProducesInvalidArgumentException() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid parameter type passed. Parameter should be integer");
        getKForm("blah");
    }

    public function testKForm_ValidNumberLessThanThousand_ProducesCorrectKForm() {
        $this->assertEquals(123, getKForm(123));
    }

    public function testKForm_ValidNumberGreaterThanThousand_ProducesCorrectKForm() {
        $this->assertEquals(1234, getKForm(1234567));
    }

    public function testStartsWith_ValidNeedleWithHaystack_ProducesBooleanOutput() {
        $this->assertTrue(startsWith("This is a sample text", "This"));
    }

    public function testStartsWith_ValidNeedleWithInvalidHaystack_ProducesBooleanOutput() {
        $this->assertFalse(startsWith("This is a sample text", "this"));
    }

    public function testMakeGetRequest_InvalidUrlWithoutHeaders_ProducesErrorOutput() {
        $this->assertEquals("cURL error 7: Failed to connect to www.blah.com port 80: Connection timed out (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)", make_get_request("www.blah.com"));
    }

    public function testMakeGetRequest_ValidUrlWithHeaders_ProducesCorrectOutput() {
        $videoUrl = "https://hssouthsp-vh.akamaihd.net/i/videos/vijay_hd/chinnathambi/149/master_,106,180,400,800,1300,2000,3000,4500,kbps.mp4.csmil/master.m3u8?hdnea=st=1551575624~exp=1551577424~acl=/*~hmac=3d89f2aab02315ee100156209746e0e9f3bc70b0b52c17573300b5caa517cfd6";
        $headers = ['Hotstarauth' => generateHotstarAuth() , 'X-Country-Code' => 'IN', 'X-Platform-Code' => 'JIO', 'Referer' => $videoUrl];

        $expectedResponse = "<HTML><HEAD>\n";
        $expectedResponse .= "<TITLE>Access Denied</TITLE>\n";
        $expectedResponse .= "</HEAD><BODY>\n";
        $expectedResponse .= "<H1>Access Denied</H1>\n";
        $expectedResponse .= " \n";
        $expectedResponse .= "You don't have permission to access \"http&#58;&#47;&#47;hssouthsp&#45;vh&#46;akamaihd&#46;net&#47;i&#47;videos&#47;vijay&#95;hd&#47;chinnathambi&#47;149&#47;master&#95;&#44;106&#44;180&#44;400&#44;800&#44;1300&#44;2000&#44;3000&#44;4500&#44;kbps&#46;mp4&#46;csmil&#47;master&#46;m3u8&#63;\" on this server.<P>\n";
        $expectedResponse .= "</BODY>\n";
        $expectedResponse .= "</HTML>\n";

        $actualResponse = make_get_request($videoUrl, $headers);
        $actualResponse = substr($actualResponse, 0, strpos($actualResponse, "Reference")) . substr($actualResponse, strpos($actualResponse, "</BODY>"));

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}

<?php

require_once(realpath(dirname(__FILE__) . '/../..') . "/vendor/autoload.php");
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

// function make_get_request2($url, array $headers = []) {
//     $proxyIP = "43.224.8.116";
//     $proxyPort = "6666";
//     $userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0";
//     $curl = curl_init();
//     curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($curl, CURLOPT_TIMEOUT, 20);
//     curl_setopt($curl, CURLOPT_PROXY, "$proxyIP:$proxyPort");
//     curl_setopt($curl, CURLOPT_URL, $url);
//     curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
//     curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 0);
//     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
//     curl_setopt($curl, CURLOPT_PROXYHEADER);
//     $result = curl_exec();
// }

function make_get_request($url, array $headers = []) {
    $guzzleClient = new GuzzleHttp\Client();
    $proxyIP = "43.224.8.116";
    $proxyPort = "6666";

    try {
        $guzzleResponse = $guzzleClient->request("GET", $url, ["headers" => $headers, "proxy" => "tcp://$proxyIP:$proxyPort"]);
        $guzzleResponseCode = $guzzleResponse->getStatusCode();

        if ($guzzleResponseCode == 200) {
            $guzzleResponseBody = (string)$guzzleResponse->getBody();
            return $guzzleResponseBody;
        }

        throw new ClientException("Error code $guzzleResponseCode. Error Processing Request Invalid response.", 1);
    } catch (ConnectException $e) {
        return $e->getMessage();
    } catch (ClientException $e) {
        $guzzleErrorResponse = $e->getResponse();
        $guzzleErrorResponseBody = $guzzleErrorResponse->getBody()
            ->getContents();
        return $guzzleErrorResponseBody;
    }
}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function getKForm($num) {
    if (!is_integer($num)) {
        throw new InvalidArgumentException("Invalid parameter type passed. Parameter should be integer");
    }

    if ($num < 1000) {
        return $num;
    }
    return intval($num / 1000);
}

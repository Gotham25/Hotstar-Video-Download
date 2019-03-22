<?php

require_once( realpath( dirname(__FILE__) . '/../..') . "/vendor/autoload.php" );
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

function make_get_request($url, array $headers = []) {
   
   $guzzleClient = new GuzzleHttp\Client();
   try {
      $response  = $guzzleClient->request('GET', $url, [
            'headers' => $headers
         ]);
      $responseCode = $response->getStatusCode();
      
      if($responseCode == 200){
         return (string)$response->getBody();
      }

      throw new ClientException("Error code $responseCode. Error Processing Request Invalid response.", 1);

   } catch(ConnectException $e) {
      return $e->getMessage();
   } catch(ClientException $e) {
      return $e->getResponse()->getBody()->getContents();
   }
}

function startsWith($haystack, $needle) {
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}

function getKForm($num) {

   if(!is_integer($num)){
      throw new InvalidArgumentException("Invalid parameter type passed. Parameter should be integer");
   }

	if($num < 1000) {
		return $num;
	}
	return intval($num/1000);
}

?>
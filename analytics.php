<?php
    require_once("vendor/autoload.php");
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\ConnectException;
    use GuzzleHttp\Exception\ClientException;

    if ($argc == 2) {
        $ip = $argv[1];
        $accessKey = getenv("IPSTACK_API_KEY");
        $url = "http://api.ipstack.com/" . $ip . "?access_key=" . $accessKey . "&hostname=1";
        try {
            $guzzleClient = new GuzzleHttp\Client();
            $guzzleResponse = $guzzleClient->request("GET", $url, ["headers" => []]);
            $guzzleResponseCode = $guzzleResponse->getStatusCode();

            if ($guzzleResponseCode == 200) {
                $guzzleResponseBody = (string)$guzzleResponse->getBody();
                $ipInfo = json_decode($guzzleResponseBody, true);
                $dbConnection = getDBConnection();
                $insertQuery = getInsertQuery($ipInfo);
                $insertQueryResult = pg_query($insertQuery);
                $insertedTuples = pg_affected_rows($insertQueryResult);

                if ($insertedTuples > 0) {
                    echo "Inserted $insertedTuples tuple in the site_access_info_table";
                } else {
                    die("Error occured in inserting record... for query : " . PHP_EOL . PHP_EOL . $insertQuery);
                }

                //closing DB connection after successful insert
                pg_close($dbConnection);
            }
        } catch (ConnectException $e) {
            echo "<br/>" . "Error : " . $e->getMessage();
        } catch (ClientException $e) {
            $guzzleErrorResponse = $e->getResponse();
            $guzzleErrorResponseBody = $guzzleErrorResponse->getBody()->getContents();
            echo "<br/>" . "Error : " . $guzzleErrorResponseBody;
        }
    } else {
        die("Invalid argument");
    }

    function getDBConnection() {
        $dbConn = pg_connect(getenv("DATABASE_URL"));
        if (!$dbConn) {
            die("Error occured in establishing Postgres DB connection");
        }
        return $dbConn;
    }

    function getInsertQuery($apiIpInfo) {
        $ipInfo = getIpInfo($apiIpInfo);
        $insertQuery = "INSERT INTO site_access_info(ip, hostname, city, continent_code, continent_name, country_code, country_name, latitude, location_calling_code, location_capital, location_country_flag, location_country_flag_emoji, location_country_flag_emoji_unicode, location_geoname_id, location_is_eu, location_language_codes, location_language_names, location_language_natives, longitude, region_code, region_name, type, zip, entry_created_time) VALUES ('" . $ipInfo["ip"] . "', '" . $ipInfo["hostname"] . "', '" . $ipInfo["city"] . "', '" . $ipInfo["continent_code"] . "', '" . $ipInfo["continent_name"] . "', '" . $ipInfo["country_code"] . "', '" . $ipInfo["country_name"]. "', '" . $ipInfo["latitude"] . "', '" . $ipInfo["location_calling_code"] . "', '" . $ipInfo["location_capital"] . "', '" . $ipInfo["location_country_flag"] . "', '" . $ipInfo["location_country_flag_emoji"] . "', '" . $ipInfo["location_country_flag_emoji_unicode"] . "', '" . $ipInfo["location_geoname_id"] .  "', '" . $ipInfo["location_is_eu"] . "', '" . $ipInfo["location_language_codes"] . "', '" . $ipInfo["location_language_names"] . "', '" . $ipInfo["location_language_natives"] . "', '" . $ipInfo["longitude"] . "', '" . $ipInfo["region_code"] . "', '" . $ipInfo["region_name"] . "', '" . $ipInfo["type"] . "', '" . $ipInfo["zip"] . "', now() || '    ' || current_setting('TIMEZONE'))";
        return $insertQuery;
    }

    function getIpInfo($apiIpInfo) {
        $ipInfo = [];
        $code = "{";
        $name = "{";
        $native = "{";

        foreach ($apiIpInfo["location"]["languages"] as $languageIndex => $language) {
            if ($languageIndex != 0) {
                $code .= ",";
                $name .= ",";
                $native .= ",";
            }

            $code .= "\"" . $language["code"] . "\"";
            $name .= "\"" . $language["name"] . "\"";
            $native .= "\"" . $language["native"] . "\"";
        }

        $code .= "}";
        $name .= "}";
        $native .= "}";

        $ipInfo["ip"] = $apiIpInfo["ip"];
        $ipInfo["hostname"] = $apiIpInfo["hostname"];
        $ipInfo["city"] = $apiIpInfo["city"];
        $ipInfo["continent_code"] = $apiIpInfo["continent_code"];
        $ipInfo["continent_name"] = $apiIpInfo["continent_name"];
        $ipInfo["country_code"] = $apiIpInfo["country_code"];
        $ipInfo["country_name"] = $apiIpInfo["country_name"];
        $ipInfo["latitude"] = $apiIpInfo["latitude"];
        $ipInfo["location_calling_code"] = $apiIpInfo["location"]["calling_code"];
        $ipInfo["location_capital"] = $apiIpInfo["location"]["capital"];
        $ipInfo["location_country_flag"] = $apiIpInfo["location"]["country_flag"];
        $ipInfo["location_country_flag_emoji"] = $apiIpInfo["location"]["country_flag_emoji"];
        $ipInfo["location_country_flag_emoji_unicode"] = $apiIpInfo["location"]["country_flag_emoji_unicode"];
        $ipInfo["location_geoname_id"] = $apiIpInfo["location"]["geoname_id"];
        $ipInfo["location_is_eu"] = $apiIpInfo["location"]["is_eu"] === true ? 'true' : 'false';
        $ipInfo["location_language_codes"] = $code;
        $ipInfo["location_language_names"] = $name;
        $ipInfo["location_language_natives"] = $native;
        $ipInfo["longitude"] = $apiIpInfo["longitude"];
        $ipInfo["region_code"] = $apiIpInfo["region_code"];
        $ipInfo["region_name"] = $apiIpInfo["region_name"];
        $ipInfo["type"] = $apiIpInfo["type"];
        $ipInfo["zip"] = $apiIpInfo["zip"];

        return $ipInfo;
    }

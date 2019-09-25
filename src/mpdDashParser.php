<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;

use Hitch\HitchManager;
use Hitch\Mapping\ClassMetadataFactory;
use Hitch\Mapping\Loader\AnnotationLoader;

use Dash\Model\MPD;

function getDashAudioOrVideoFormats($xmlContent, $masterPlaybackUrl) {

// include the class loader to load mpd DASH model and doctrine-common library
    require_once "ClassLoader.php";

    // register namespaces
    $loader = new ClassLoader();
    $loader->registerNamespaces(array(
        'Hitch'             => realpath(__DIR__.DIRECTORY_SEPARATOR.'hitchLibrary'),    // main Hitch lib
        'Dash\\Model'       => realpath(__DIR__.DIRECTORY_SEPARATOR.'model'),    // dash demo package
        'Doctrine\\Common'  => realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'gotham25'.DIRECTORY_SEPARATOR.'doctrine-common'.DIRECTORY_SEPARATOR.'lib'),    // Doctrine common library
    ));

    // register the autoloading
    $loader->register();

    $hitch = new HitchManager();
    $hitch->setClassMetaDataFactory(new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()), new ArrayCache()));

    // pre-build the class meta data cache
    $hitch->registerRootClass("Dash\\Model\\MPD");
    $hitch->buildClassMetaDatas();

    // parse the xml content into a dash mpd object
    $mpd = $hitch->unmarshall($xmlContent, "Dash\\Model\\MPD");
    $audioOrVideo = array();

    $mediaPresentationDuration = $mpd->getMediaPresentationDuration();

    if (preg_match('/PT((\d+)H)?((\d+)M)?((\d+)\.(\d+)S)?/', $mediaPresentationDuration, $duration)) {
        $hours        = getParsedTimeUnit($duration[2]);
        $minutes      = getParsedTimeUnit($duration[4]);
        $seconds      = getParsedTimeUnit($duration[6]);
        $milliSeconds = getParsedTimeUnit($duration[7]);

        $totalSeconds = ($hours * 60 * 60) + ($minutes * 60) + $seconds + $milliSeconds/1000;

        $adaptationSets = $mpd->getPeriod()->getAdaptationSets();

        foreach ($adaptationSets as $adaptationSet) {
            $duration = $adaptationSet->getSegmentTemplate()->getDuration();
            $timescale = $adaptationSet->getSegmentTemplate()->getTimescale();
            $segmentScale = (float)$duration / (float)$timescale;
            $totalSegments = (int) ceil($totalSeconds / $segmentScale);

            $initializationURL = $adaptationSet->getSegmentTemplate()->getInitialization();
            $mediaURL = $adaptationSet->getSegmentTemplate()->getMedia();

            switch ($adaptationSet->getMimeType()) {
            case "video/mp4":
                $audioOrVideo["video"]=array();
                populateFormatsInfo($audioOrVideo, "video", $adaptationSet, $totalSegments, $initializationURL, $mediaURL, $masterPlaybackUrl);
                break;
            case "audio/mp4":
                $audioOrVideo["audio"]=array();
                populateFormatsInfo($audioOrVideo, "audio", $adaptationSet, $totalSegments, $initializationURL, $mediaURL, $masterPlaybackUrl);
                break;
            default: echo "Invalid MIME Type : " . $adaptationSet->getMimeType();
                break;
            }
        }
    }

    return $audioOrVideo;
}

function getParsedTimeUnit($time) {
    return (strcmp($time, "") == 0) ? 0.0 : (float) $time;
}

function populateFormatsInfo(&$av, $type, $adaptationSet, $totalSegments, $initializationURL, $mediaURL, $masterPlaybackUrl) {
    foreach ($adaptationSet->getRepresentations() as $representation) {
        $formatInfo = array();
        $bandwidth = (int) $representation->getBandwidth();
        $formatInfo["BANDWIDTH"] = $bandwidth;
        
        $formatInfo["K-FORM-NUMBER"] = sprintf("%d", $bandwidth/1000);
        $formatInfo["CODECS"] = sprintf("mp4_dash container, %s", $representation->getCodecs());
        $formatInfo["MIME-TYPE"] = $adaptationSet->getMimeType();

        if (strcmp($type, "video") === 0) {
            $formatInfo["STREAM"] = "video only";
            $formatInfo["RESOLUTION"] = sprintf("%sx%s", $representation->getWidth(), $representation->getHeight());
            $formatInfo["FRAME-RATE"] = $representation->getFrameRate();
            $formatInfo["K-FORM"] = sprintf("DASH video %dk", $bandwidth/1000);
        } else {
            $formatInfo["STREAM"] = "audio only";
            $formatInfo["SAMPLING-RATE"] = sprintf("(%s Hz)", $representation->getAudioSamplingRate());
            $formatInfo["K-FORM"] = sprintf("DASH audio %dk", $bandwidth/1000);
            $formatInfo["RESOLUTION"] = sprintf("(%s Hz)", $representation->getAudioSamplingRate());
        }

        $formatInfo["TOTAL-SEGMENTS"] = $totalSegments;
        $formatInfo["INIT-URL"] = getURL("\$RepresentationID\$", $representation->getId(), $initializationURL);
        $formatInfo["STREAM-URL"] = getURL("\$RepresentationID\$", $representation->getId(), $mediaURL);
        $formatInfo["PLAYBACK-URL"] = $masterPlaybackUrl;
        $av[$type][sprintf("%dk", $bandwidth/1000)] = $formatInfo;
    }
}

function getURL($search, $replace, $subject) {
    return str_ireplace($search, $replace, $subject);
}

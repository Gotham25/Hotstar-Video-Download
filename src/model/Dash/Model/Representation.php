<?php

    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class Representation {

        /**
         * @xml:XmlAttribute(name="bandwidth")
         */
        protected $bandwidth;

        /**
         * @xml:XmlAttribute(name="codecs")
         */
        protected $codecs;

        /**
         * @xml:XmlAttribute(name="frameRate")
         */
        protected $frameRate;

        /**
         * @xml:XmlAttribute(name="height")
         */
        protected $height;

        /**
         * @xml:XmlAttribute(name="id")
         */
        protected $id;

        /**
         * @xml:XmlAttribute(name="scanType")
         */
        protected $scanType;

        /**
         * @xml:XmlAttribute(name="width")
         */
        protected $width;

        /**
         * @xml:XmlAttribute(name="mimeType")
         */
        protected $mimeType;

        /**
         * @xml:XmlAttribute(name="sar")
         */
        protected $sar;

        /**
         * @xml:XmlAttribute(name="audioSamplingRate")
         */
        protected $audioSamplingRate;

        /**
         * @xml:XmlElement(name="AudioChannelConfiguration", type="Dash\Model\AudioChannelConfiguration")
         */
        protected $audioChannelConfiguration;

        /**
         * @xml:XmlElement(name="BaseURL")
         */
        protected $baseUrl;

        /**
         * @xml:XmlElement(name="SegmentBase", type="Dash\Model\SegmentBase")
         */
        protected $segmentBase;

        public function getBandwidth() {
            return $this->bandwidth;
        }

        public function setBandwidth($bandwidth) {
            $this->bandwidth = $bandwidth;
        }

        public function getCodecs() {
            return $this->codecs;
        }

        public function setCodecs($codecs) {
            $this->codecs = $codecs;
        }

        public function getFrameRate() {
            return $this->frameRate;
        }

        public function setFrameRate($frameRate) {
            $this->frameRate = $frameRate;
        }

        public function getHeight() {
            return $this->height;
        }

        public function setHeight($height) {
            $this->height = $height;
        }

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getScanType() {
            return $this->scanType;
        }

        public function setScanType($scanType) {
            $this->scanType = $scanType;
        }

        public function getWidth() {
            return $this->width;
        }

        public function setWidth($width) {
            $this->width = $width;
        }

        public function getAudioSamplingRate() {
            return $this->audioSamplingRate;
        }

        public function setAudioSamplingRate($audioSamplingRate) {
            $this->audioSamplingRate = $audioSamplingRate;
        }

        public function getMimeType() {
            return $this->mimeType;
        }

        public function setMimeType($mimeType) {
            $this->mimeType = $mimeType;
        }

        public function getSar() {
            return $this->sar;
        }

        public function setSar($sar) {
            $this->sar = $sar;
        }

        public function getAudioChannelConfiguration() {
            return $this->audioChannelConfiguration;
        }

        public function setAudioChannelConfiguration($audioChannelConfiguration) {
            $this->audioChannelConfiguration = $audioChannelConfiguration;
        }

        public function getBaseUrl() {
            return $this->baseUrl;
        }

        public function setBaseUrl($baseUrl) {
            $this->baseUrl = $baseUrl;
        }

        public function getSegmentBase() {
            return $this->segmentBase;
        }

        public function setSegmentBase($segmentBase) {
            $this->segmentBase = $segmentBase;
        }
    }

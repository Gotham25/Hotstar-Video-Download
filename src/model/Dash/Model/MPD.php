<?php
    
    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class MPD {
        
        /**
         * @xml:XmlAttribute(name="mediaPresentationDuration")
         */
        protected $mediaPresentationDuration;

        /**
         * @xml:XmlAttribute(name="minBufferTime")
         */
        protected $minBufferTime;

        /**
         * @xml:XmlAttribute(name="profiles")
         */
        protected $profiles;

        /**
         * @xml:XmlAttribute(name="type")
         */
        protected $type;

        /**
         * @xml:XmlAttribute(name="xmlns")
         */
        protected $xmlns;

        /**
         * @xml:XmlAttribute(name="xmlns:xsi")
         */
        protected $xmlnsXsi;

        /**
         * @xml:XmlAttribute(name="xsi:schemaLocation")
         */
        protected $schemaLocation;

        /**
         * @xml:XmlElement(name="Period", type="Dash\Model\Period")
         */
        protected $period;

        public function getMediaPresentationDuration() {
            return $this->mediaPresentationDuration;
        }

        public function setMediaPresentationDuration($mediaPresentationDuration) {
            $this->mediaPresentationDuration = $mediaPresentationDuration;
        }

        public function getMinBufferTime() {
            return $this->minBufferTime;
        }

        public function setMinBufferTime($minBufferTime) {
            $this->minBufferTime = $minBufferTime;
        }

        public function getProfiles() {
            return $this->profiles;
        }

        public function setProfiles($profiles) {
            $this->profiles = $profiles;
        }

        public function getType() {
            return $this->type;
        }

        public function setType($type) {
            $this->type = $type;
        }

        public function getXmlns() {
            return $this->xmlns;
        }

        public function setXmlns($xmlns) {
            $this->xmlns = $xmlns;
        }

        public function getXmlnsXsi() {
            return $this->xmlnsXsi;
        }

        public function setXmlnsXsi($xmlnsXsi) {
            $this->xmlnsXsi = $xmlnsXsi;
        }

        public function getSchemaLocation() {
            return $this->schemaLocation;
        }

        public function setSchemaLocation($schemaLocation) {
            $this->schemaLocation = $schemaLocation;
        }

        public function getPeriod() {
            return $this->period;
        }

        public function setPeriod($period) {
            $this->period = $period;
        }
    }

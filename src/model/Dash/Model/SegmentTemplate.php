<?php
    
    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class SegmentTemplate {

        /**
         * @xml:XmlAttribute(name="duration")
         */
        protected $duration;

        /**
         * @xml:XmlAttribute(name="initialization")
         */
        protected $initialization;

        /**
         * @xml:XmlAttribute(name="media")
         */
        protected $media;

        /**
         * @xml:XmlAttribute(name="startNumber")
         */
        protected $startNumber;

        /**
         * @xml:XmlAttribute(name="timescale")
         */
        protected $timescale;

        //TODO: Add getters and setters
        public function getDuration() {
            $duration = $this->duration;
            return ($duration == "") ? "1" : $duration;
        }

        public function setDuration($duration) {
            $this->duration = $duration;
        }

        public function getInitialization() {
            return $this->initialization;
        }

        public function setInitialization($initialization) {
            $this->initialization = $initialization;
        }

        public function getMedia() {
            return $this->media;
        }

        public function setMedia($media) {
            $this->media = $media;
        }

        public function getStartNumber() {
            return $this->startNumber;
        }

        public function setStartNumber($startNumber) {
            $this->startNumber = $startNumber;
        }

        public function getTimescale() {
            $timescale = $this->timescale;
            return ($timescale == "") ? "1" : $timescale;
        }

        public function setTimescale($timescale) {
            $this->timescale = $timescale;
        }
    }

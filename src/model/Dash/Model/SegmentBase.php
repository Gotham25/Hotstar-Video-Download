<?php

    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class SegmentBase {

        /**
         * @xml:XmlAttribute(name="indexRange")
         */
        protected $indexRange;

        /**
         * @xml:XmlAttribute(name="timescale")
         */
        protected $timescale;

        /**
         * @xml:XmlAttribute(name="presentationTimeOffset")
         */
        protected $presentationTimeOffset;

        /**
         * @xml:XmlElement(name="Initialization", type="Dash\Model\Initialization")
         */
        protected $initialization;

        public function getIndexRange() {
            return $this->indexRange;
        }

        public function setIndexRange($indexRange) {
            $this->indexRange = $indexRange;
        }

        public function getTimescale() {
            return $this->timescale;
        }

        public function setTimescale($timescale) {
            $this->timescale = $timescale;
        }

        public function getPresentationTimeOffset() {
            return $this->presentationTimeOffset;
        }

        public function setPresentationTimeOffset($presentationTimeOffset) {
            $this->presentationTimeOffset = $presentationTimeOffset;
        }

        public function getInitialization() {
            return $this->initialization;
        }

        public function setInitialization($initialization) {
            $this->initialization = $initialization;
        }
    }

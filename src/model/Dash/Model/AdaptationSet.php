<?php
    
    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class AdaptationSet {

        /**
         * @xml:XmlAttribute(name="maxHeight")
         */
        protected $maxHeight;

        /**
         * @xml:XmlAttribute(name="maxWidth")
         */
        protected $maxWidth;

        /**
         * @xml:XmlAttribute(name="mimeType")
         */
        protected $mimeType;

        /**
         * @xml:XmlAttribute(name="segmentAlignment")
         */
        protected $segmentAlignment;

        /**
         * @xml:XmlAttribute(name="startWithSAP")
         */
        protected $startWithSap;
        
        /**
         * @xml:XmlElement(name="SegmentTemplate", type="Dash\Model\SegmentTemplate")
         */
        protected $segmentTemplate;

        /**
         * @xml:XmlList(name="Representation", type="Dash\Model\Representation")
         */
        protected $representations;

        public function getMaxHeight() {
            return $this->maxHeight;
        }

        public function setMaxHeight($maxHeight) {
            $this->maxHeight = $maxHeight;
        }

        public function getMaxWidth() {
            return $this->maxWidth;
        }

        public function setMaxWidth($maxWidth) {
            $this->maxWidth = $maxWidth;
        }

        public function getMimeType() {
            return $this->mimeType;
        }

        public function setMimeType($mimeType) {
            $this->mimeType = $mimeType;
        }

        public function getSegmentAlignment() {
            return $this->segmentAlignment;
        }

        public function setSegmentAlignment($segmentAlignment) {
            $this->segmentAlignment = $segmentAlignment;
        }

        public function getStartWithSap() {
            return $this->startWithSap;
        }

        public function setStartWithSap($startWithSap) {
            $this->startWithSap = $startWithSap;
        }

        public function getSegmentTemplate() {
            return $this->segmentTemplate;
        }

        public function setSegmentTemplate($segmentTemplate) {
            $this->segmentTemplate = $segmentTemplate;
        }

        public function getRepresentations() {
            return $this->representations;
        }

        public function setRepresentations($representations) {
            $this->representations = $representations;
        }
    }

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
         * @xml:XmlAttribute(name="id")
         */
        protected $id;

        /**
         * @xml:XmlAttribute(name="contentType")
         */
        protected $contentType;

        /**
         * @xml:XmlAttribute(name="frameRate")
         */
        protected $frameRate;

        /**
         * @xml:XmlAttribute(name="subsegmentAlignment")
         */
        protected $subsegmentAlignment;

        /**
         * @xml:XmlAttribute(name="par")
         */
        protected $par;
        
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

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getContentType() {
            return $this->contentType;
        }

        public function setContentType($contentType) {
            $this->contentType = $contentType;
        }

        public function getFrameRate() {
            return $this->frameRate;
        }

        public function setFrameRate($frameRate) {
            $this->frameRate = $frameRate;
        }

        public function getSubsegmentAlignment() {
            return $this->subsegmentAlignment;
        }

        public function setSubsegmentAlignment($subsegmentAlignment) {
            $this->subsegmentAlignment = $subsegmentAlignment;
        }

        public function getPar() {
            return $this->par;
        }

        public function setPar($par) {
            $this->par = $par;
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

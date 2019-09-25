<?php

    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class AudioChannelConfiguration {

        /**
         * @xml:XmlAttribute(name="schemeIdUri")
         */
        protected $schemeIdUri;

        /**
         * @xml:XmlAttribute(name="value")
         */
        protected $value;

        public function getSchemeIdUri() {
            return $this->schemeIdUri;
        }

        public function setSchemeIdUri($schemeIdUri) {
            $this->schemeIdUri = $schemeIdUri;
        }

        public function getValue() {
            return $this->value;
        }

        public function setValue($value) {
            $this->value = $value;
        }
    }

<?php

    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class Initialization {

        /**
         * @xml:XmlAttribute(name="range")
         */
        protected $range;

        public function getRange() {
            return $this->range;
        }

        public function setRange($range) {
            $this->range = $range;
        }
    }

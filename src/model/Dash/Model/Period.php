<?php
    
    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class Period {
        
        /**
         * @xml:XmlAttribute(name="id")
         */
        protected $id;

        /**
         * @xml:XmlList(name="AdaptationSet", type="Dash\Model\AdaptationSet")
         */
        protected $adaptationSets;
        
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getAdaptationSets() {
            return $this->adaptationSets;
        }

        public function setAdaptationSets($adaptationSets) {
            $this->adaptationSets = $adaptationSets;
        }
    }

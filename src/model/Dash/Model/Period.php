<?php
    
    namespace Dash\Model;

    /**
     * @xml:XmlObject
     */
    class Period {
        
        /**
         * @xml:XmlList(name="AdaptationSet", type="Dash\Model\AdaptationSet")
         */
        protected $adaptationSets;
        
        public function getAdaptationSets() {
            return $this->adaptationSets;
        }

        public function setAdaptationSets($adaptationSets) {
            $this->adaptationSets = $adaptationSets;
        }
    }

<?php 
    class Wordbase{
        public $wid;
        public $wword;
        public $wtype;
        public $wclass;
        
        public $wordbase;
        
        
        /**
         * 导出array
         * @return array
         */
        public function array(){
            $arr = [
                'wclass' => $this->wclass,
                'wid' => $this->wid,
                'wtype' => $this->wtype,
                'wword' => $this->wword
            ];
            return $arr;
        }
        
        /**
         * 导出json
         * @return string
         */
        public function json(){
            //return json_encode($this->wordbase);
            $arr = [
                'wclass' => $this->wclass,
                'wid' => $this->wid,
                'wtype' => $this->wtype,
                'wword' => $this->wword
            ];
            return json_encode($arr);
            
        }
        
        /**
         * @param field_type $wordbase
         */
        public function setWordbase($wordbase)
        {
            $this->wordbase = $wordbase;
            $this->wclass = $wordbase['wclass'];
            $this->wid = $wordbase['wid'];
            $this->wtype = $wordbase['wtype'];
            $this->wword = $wordbase['wword'];
        }
        
        /**
         * @return the $wordbase
         */
        public function getWordbase()
        {
            return $this->wordbase;
        }
    
        /**
         * @return the $wid
         */
        public function getWid()
        {
            return $this->wid;
        }
    
        /**
         * @return the $wword
         */
        public function getWword()
        {
            return $this->wword;
        }
    
        /**
         * @return the $wtype
         */
        public function getWtype()
        {
            return $this->wtype;
        }
    
        /**
         * @return the $wclass
         */
        public function getWclass()
        {
            return $this->wclass;
        }
    
        /**
         * @param field_type $wid
         */
        public function setWid($wid)
        {
            $this->wid = $wid;
        }
    
        /**
         * @param field_type $wword
         */
        public function setWword($wword)
        {
            $this->wword = $wword;
        }
    
        /**
         * @param field_type $wtype
         */
        public function setWtype($wtype)
        {
            $this->wtype = $wtype;
        }
    
        /**
         * @param field_type $wclass
         */
        public function setWclass($wclass)
        {
            $this->wclass = $wclass;
        }
    
        
    }
?>
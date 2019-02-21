<?php 
    require_once 'wordbase.class.php';
    class Learn{
        public $aid;
        public $aword;
        public $afq;
        public $atype;
        public $aclass;
        
        public $wordbase;
        public $learn;
        
        function __construct() {
            $this->wordbase = new Wordbase();
        }
        /**
         * 学习经验
         * @return int
         */
        public function doLearn(){
            $this->afq += 1;
            return $this->afq;
        }
        /**
         * 判断学习是否成熟
         * @return boolean
         */
        public function isOk(){
            //第一次到达 饱和度 时可进词库更新，大于的表示已经学习过了，不必再次更新词库，饱和度在config中配置
            if($this->afq == LEARN_FQ)return true;
            else return false;
        }
        /**
         * 完成学习
         * @return Wordbase
         */
        public function doEnd(){
            $this->wordbase->setWword($this->aword);
            $this->wordbase->setWtype($this->atype);
            $this->wordbase->setWclass('straightWords');
            return $this->wordbase;
        }
        
        /**
         * 导出array
         * @return array
         */
        public function array(){
            $arr = [
                'aclass' => $this->aclass,
                'afq' => $this->afq,
                'aid' => $this->aid,
                'atype' => $this->atype,
                'aword' => $this->aword
            ];
            return $arr;
        }
        
        /**
         * 导出json
         * @return string
         */
        public function json(){
            //return json_encode($this->learn);
            $arr = [
                'aclass' => $this->aclass,
                'afq' => $this->afq,
                'aid' => $this->aid,
                'atype' => $this->atype,
                'aword' => $this->aword
            ];
            return json_encode($arr);
            
        }
        
        /**
         * @return the $learn
         */
        public function getLearn()
        {
            return $this->learn;
        }
    
        /**
         * @param field_type $learn
         */
        public function setLearn($learn)
        {
            $this->learn = $learn;
            $this->aclass = $learn['aclass'];
            $this->afq = $learn['afq'];
            $this->aid = $learn['aid'];
            $this->atype = $learn['atype'];
            $this->aword = $learn['aword'];
        }
    
        /**
         * @return the $aid
         */
        public function getAid()
        {
            return $this->aid;
        }
    
        /**
         * @return the $aword
         */
        public function getAword()
        {
            return $this->aword;
        }
    
        /**
         * @return the $afq
         */
        public function getAfq()
        {
            return $this->afq;
        }
    
        /**
         * @return the $atype
         */
        public function getAtype()
        {
            return $this->atype;
        }
    
        /**
         * @return the $aclass
         */
        public function getAclass()
        {
            return $this->aclass;
        }
    
        /**
         * @param field_type $aid
         */
        public function setAid($aid)
        {
            $this->aid = $aid;
        }
    
        /**
         * @param field_type $aword
         */
        public function setAword($aword)
        {
            $this->aword = $aword;
        }
    
        /**
         * @param field_type $afq
         */
        public function setAfq($afq)
        {
            $this->afq = $afq;
        }
    
        /**
         * @param field_type $atype
         */
        public function setAtype($atype)
        {
            $this->atype = $atype;
        }
    
        /**
         * @param field_type $aclass
         */
        public function setAclass($aclass)
        {
            $this->aclass = $aclass;
        }
    
        
    }
?>
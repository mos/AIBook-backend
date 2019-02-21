<?php 
    class Consume{
        public $cid;
        public $uid;
        public $ctime;
        public $cmoney;
        public $ctype;
        public $cthing;
        
        public $consume;
        
        /**
         * 导出array
         * @return array
         */
        public function array(){
            $arr = [
                'uid'=>$this->uid,
                'cid'=>$this->cid,
                'cmoney'=>$this->cmoney,
                'ctime'=>$this->ctime,
                'ctype'=>$this->ctype,
                'cthing'=>$this->cthing
            ];
            return $arr;
        }
        
        /**
         * 导出json
         * @return string
         */
        public function json(){
            //return json_encode($this->consume);
            $arr = [
                'uid'=>$this->uid,
                'cid'=>$this->cid,
                'cmoney'=>$this->cmoney,
                'ctime'=>$this->ctime,
                'ctype'=>$this->ctype,
                'cthing'=>$this->cthing
            ];
            return json_encode($arr);
            
        }
        
        /**
         * @param field_type $consume
         */
        public function setConsume($consume)
        {
            $this->consume = $consume;
            $this->cid = $consume['cid'];
            $this->cmoney = $consume['cmoney'];
            $this->cthing = $consume['cthing'];
            $this->ctime = $consume['ctime'];
            $this->ctype = $consume['ctype'];
            $this->uid = $consume['uid'];
        }
        
        
        /**
         * @return the $consume
         */
        public function getConsume()
        {
            return $this->consume;
        }
    
        /**
         * @return the $cid
         */
        public function getCid()
        {
            return $this->cid;
        }
    
        /**
         * @return the $uid
         */
        public function getUid()
        {
            return $this->uid;
        }
    
        /**
         * @return the $ctime
         */
        public function getCtime()
        {
            return $this->ctime;
        }
    
        /**
         * @return the $cmoney
         */
        public function getCmoney()
        {
            return $this->cmoney;
        }
    
        /**
         * @return the $ctype
         */
        public function getCtype()
        {
            return $this->ctype;
        }
    
        /**
         * @return the $cthing
         */
        public function getCthing()
        {
            return $this->cthing;
        }
    
        /**
         * @param field_type $cid
         */
        public function setCid($cid)
        {
            $this->cid = $cid;
        }
    
        /**
         * @param field_type $uid
         */
        public function setUid($uid)
        {
            $this->uid = $uid;
        }
    
        /**
         * @param field_type $ctime
         */
        public function setCtime($ctime)
        {
            $this->ctime = $ctime;
        }
    
        /**
         * @param field_type $cmoney
         */
        public function setCmoney($cmoney)
        {
            $this->cmoney = $cmoney;
        }
    
        /**
         * @param field_type $ctype
         */
        public function setCtype($ctype)
        {
            $this->ctype = $ctype;
        }
    
        /**
         * @param field_type $cthing
         */
        public function setCthing($cthing)
        {
            $this->cthing = $cthing;
        }
    
        
    }
?>
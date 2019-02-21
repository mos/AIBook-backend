<?php 
    class token{
        private $key = 'hjsad7tysa7'; //md5中撒点盐
        public function create(){
            $time = time();
            $server_token= md5( md5($this->key) . md5($time) );
            return $server_token;
        }
    }
?>
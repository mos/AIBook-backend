<?php 
    class curlTo{
        
        private $url;
        private $isadmin;
        
        /**
         * @param string $isadmin
         * @param string $url
         */
        function __construct($isadmin, $url){
            $this->url = BASIC_DOMAIN.$url;
            $this->isadmin = $isadmin;
        }
        
        /**
         * 
         * @param array $array
         * @return mixed
         */
        function send($array){
            //将发送数据进行转换
            $data = '?admin='.$this->isadmin;
            foreach ($array as $key=>$value){
                $data .= ("&".substr($key, 1)."=".$value);
            }
            //echo $this->url.$data;
            //curl发送数据
            $curl = curl_init($this->url.$data);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        }
        
        function onlyUrl(){
            $data = '?admin='.$this->isadmin;
            //curl发送数据
            $curl = curl_init($this->url.$data);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        }
        
        /**
         * @return the $url
         */
        public function getUrl()
        {
            return $this->url;
        }
    
        /**
         * @return the $isadmin
         */
        public function getIsadmin()
        {
            return $this->isadmin;
        }
    
        /**
         * @param field_type $url
         */
        public function setUrl($url)
        {
            $this->url = $url;
        }
    
        /**
         * @param field_type $isadmin
         */
        public function setIsadmin($isadmin)
        {
            $this->isadmin = $isadmin;
        }
    
        
        
    }
?>
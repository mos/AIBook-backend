<?php 
    class User{
        public $uid;
        public $usex;
        public $urole;
        public $uphone;
        public $uname;
        public $upasswd;
        public $ulogo;
        public $unote;
        public $uplan;
        public $ainame;
        public $ailogo;
        public $token;
        
        public $user;
        public $oldpwd;
        
        function __construct(){
            $this->ailogo = 'null.jpg';
            $this->ainame = '小管家';
            $this->ulogo = 'null.jpg';
            $this->uname = '未设置';
            $this->unote = '这个人暂时没有什么野心';
            $this->uplan = 900;
            $this->urole = '保密';
            $this->usex = '保密';
        }
        
        /**
         * 导出array
         * @return array
         */
        public function getArray(){
            $arr = [
                'uid'=>$this->uid,
                'usex'=>$this->usex,
                'urole'=>$this->urole,
                'uphone'=>$this->uphone,
                'uname'=>$this->uname,
                'upasswd'=>$this->upasswd,
                'ulogo'=>$this->ulogo,
                'unote'=>$this->unote,
                'uplan'=>$this->uplan,
                'ainame'=>$this->ainame,
                'ailogo'=>$this->ailogo,
                'token'=>$this->token,
            ];
            return $arr;
        }
        
        /**
         * 导出json
         * @return string
         */
        public function json(){
            //return json_encode($this->user);
            $arr = [
                'uid'=>$this->uid,
                'usex'=>$this->usex,
                'urole'=>$this->urole,
                'uphone'=>$this->uphone,
                'uname'=>$this->uname,
                'upasswd'=>$this->upasswd,
                'ulogo'=>$this->ulogo,
                'unote'=>$this->unote,
                'uplan'=>$this->uplan,
                'ainame'=>$this->ainame,
                'ailogo'=>$this->ailogo,
                'token'=>$this->token,
            ];
            return json_encode($arr);
            
        }
        
        /**
         * @return the $user
         */
        public function getUser()
        {
            return $this->user;
        }
    
        /**
         * @param field_type $user
         */
        public function setUser($user)
        {
            $this->user = $user;
            $this->ailogo = $user['ailogo'];
            $this->ainame = $user['ainame'];
            $this->token = $user['token'];
            $this->uid = $user['uid'];
            $this->ulogo = $user['ulogo'];
            $this->uname = $user['uname'];
            $this->unote = $user['unote'];
            $this->upasswd = $user['upasswd'];
            $this->uphone = $user['uphone'];
            $this->uplan = $user['uplan'];
            $this->urole = $user['urole'];
            $this->usex = $user['usex'];
        }
    
        /**
         * @return the $oldpwd
         */
        public function getOldpwd()
        {
            return $this->oldpwd;
        }
    
        /**
         * @param field_type $oldpwd
         */
        public function setOldpwd($oldpwd)
        {
            $this->oldpwd = $oldpwd;
        }
    
        /**
         * @return the $uid
         */
        public function getUid()
        {
            return $this->uid;
        }
    
        /**
         * @return the $usex
         */
        public function getUsex()
        {
            return $this->usex;
        }
    
        /**
         * @return the $urole
         */
        public function getUrole()
        {
            return $this->urole;
        }
    
        /**
         * @return the $uphone
         */
        public function getUphone()
        {
            return $this->uphone;
        }
    
        /**
         * @return the $uname
         */
        public function getUname()
        {
            return $this->uname;
        }
    
        /**
         * @return the $upasswd
         */
        public function getUpasswd()
        {
            return $this->upasswd;
        }
    
        /**
         * @return the $ulogo
         */
        public function getUlogo()
        {
            return $this->ulogo;
        }
    
        /**
         * @return the $unote
         */
        public function getUnote()
        {
            return $this->unote;
        }
    
        /**
         * @return the $uplan
         */
        public function getUplan()
        {
            return $this->uplan;
        }
    
        /**
         * @return the $ainame
         */
        public function getAiname()
        {
            return $this->ainame;
        }
    
        /**
         * @return the $ailogo
         */
        public function getAilogo()
        {
            return $this->ailogo;
        }
    
        /**
         * @return the $token
         */
        public function getToken()
        {
            return $this->token;
        }
    
        /**
         * @param field_type $uid
         */
        public function setUid($uid)
        {
            $this->uid = $uid;
        }
    
        /**
         * @param field_type $usex
         */
        public function setUsex($usex)
        {
            $this->usex = $usex;
        }
    
        /**
         * @param field_type $urole
         */
        public function setUrole($urole)
        {
            $this->urole = $urole;
        }
    
        /**
         * @param field_type $uphone
         */
        public function setUphone($uphone)
        {
            $this->uphone = $uphone;
        }
    
        /**
         * @param field_type $uname
         */
        public function setUname($uname)
        {
            $this->uname = $uname;
        }
    
        /**
         * @param field_type $upasswd
         */
        public function setUpasswd($upasswd)
        {
            $this->upasswd = $upasswd;
        }
    
        /**
         * @param field_type $ulogo
         */
        public function setUlogo($ulogo)
        {
            $this->ulogo = $ulogo;
        }
    
        /**
         * @param field_type $unote
         */
        public function setUnote($unote)
        {
            $this->unote = $unote;
        }
    
        /**
         * @param field_type $uplan
         */
        public function setUplan($uplan)
        {
            $this->uplan = $uplan;
        }
    
        /**
         * @param field_type $ainame
         */
        public function setAiname($ainame)
        {
            $this->ainame = $ainame;
        }
    
        /**
         * @param field_type $ailogo
         */
        public function setAilogo($ailogo)
        {
            $this->ailogo = $ailogo;
        }
    
        /**
         * @param field_type $token
         */
        public function setToken($token)
        {
            $this->token = $token;
        }
    
        
    }
?>
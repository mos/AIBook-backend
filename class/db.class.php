<?php 
    require_once 'user.class.php';
    require_once 'wordbase.class.php';
    require_once 'consume.class.php';
    require_once 'learn.class.php';
    require_once 'mysql.class.php';
    class DB{
        private $user;
        private $wordbase;
        private $consume;
        private $learn;
        
        private $mysql;
        private $table;
        
        function __construct() {
            $this->mysql = new sql();
        }
        
        /**
         * 查询操作:all
         * @param string
         * @return array
         */
        public function select($table){
            if(!empty($table)){
                $this->mysql->init();
                $sql = "select * from $table";
                $result = $this->mysql->exec($sql);
                $this->mysql->closeCon();
                return $result;
            }
        }
        
        /**
         * 插入操作
         * @param string, User|Wordbase|Consume|Learn
         * @return boolean|id
         */
        public function insert($table, $class){
            if(!empty($table)){
                $this->mysql->init();
                $sql = '';
                switch ($table){
                    case 'user': {
                        $sql = "insert into user(usex,urole,uphone,uname,upasswd,ulogo,unote,uplan,ainame,ailogo,token) values(
                                '$class->usex',
                                '$class->urole',
                                '$class->uphone',
                                '$class->uname',
                                '$class->upasswd',
                                '$class->ulogo',
                                '$class->unote',
                                $class->uplan,
                                '$class->ainame',
                                '$class->ailogo',
                                '$class->token'
                                )";
                        break;
                    }
                    case 'wordbase':{
                        $sql = "insert into wordbase(wword,wtype,wclass) values(
                                '$class->wword',
                                '$class->wtype',
                                '$class->wclass'
                                 )";
                        break;
                    }
                    case 'consume': {
                        $sql = "insert into consume(uid,ctime,cmoney,ctype,cthing) values(
                                $class->uid,
                                '$class->ctime',
                                $class->cmoney,
                                '$class->ctype',
                                '$class->cthing'
                                 )";
                        break;
                    }
                    case 'learn': {
                        $sql = "insert into learn(aword,afq,atype,aclass) values(
                            '$class->aword',
                            $class->afq,
                            '$class->atype',
                            '$class->aclass'
                        )";
                        break;
                    }
                }
                //echo $sql;
                $this->mysql->exec($sql);
                $inser_id = $this->mysql->getId();
                $this->mysql->closeCon();
                return $inser_id;
            }else return false;
        }
        
        /**
         * 修改操作
         * @param string, User|Wordbase|Consume|Learn
         * @return boolean
         */
        public function update($table, $class){
            if(!empty($table)){
                $this->mysql->init();
                $sql = '';
                switch ($table){
                    case 'user': {
                        $sql = "update user set 
                                    usex='$class->usex',
                                    urole='$class->urole',
                                    uphone='$class->uphone',
                                    uname='$class->uname',
                                    upasswd='$class->upasswd',
                                    ulogo='$class->ulogo',
                                    unote='$class->unote',
                                    uplan=$class->uplan,
                                    ainame='$class->ainame',
                                    ailogo='$class->ailogo',
                                    token='$class->token' 
                               where uid=$class->uid";
                        break;
                    }
                    case 'wordbase':{
                        $sql = "update wordbase set 
                                    wword='$class->wword',
                                    wtype='$class->wtype',
                                    wclass='$class->wclass' 
                                where wid=$class->wid";
                        break;
                    }
                    case 'consume': {
                        $sql = "update consume set 
                                    ctime='$class->ctime',
                                    cmoney=$class->cmoney,
                                    ctype='$class->ctype',
                                    cthing='$class->cthing' 
                                where uid=$class->uid and cid=$class->cid";
                        break;
                    }
                    case 'learn': {
                        $sql = "update learn set 
                                    aword='$class->aword',
                                    afq=$class->afq,
                                    atype='$class->atype',
                                    aclass='$class->aclass' 
                                where aid=$class->aid";
                        break;
                    }
                }
                //echo $sql;
                $result = $this->mysql->exec($sql);
                $this->mysql->closeCon();
                return $result;
            }else return false;
        }
        
        /**
         * 删除操作
         * @param string, User|Wordbase|Consume|Learn
         * @return boolean
         */
        public function delete($table, $class){
            if(!empty($table)){
                $this->mysql->init();
                $sql = '';
                switch ($table){
                    case 'user': {
                        $sql = "delete from user where uid=$class->uid";
                        break;
                    }
                    case 'wordbase':{
                        $sql = "delete from wordbase where wid=$class->wid";
                        break;
                    }
                    case 'consume': {
                        $sql = "delete from consume where cid=$class->cid";
                        break;
                    }
                    case 'learn': {
                        $sql = "delete from learn where aid=$class->aid";
                        break;
                    }
                }
                //echo $sql;
                $this->mysql->exec($sql);
                $affected_rows = $this->mysql->getRows();
                $this->mysql->closeCon();
                return $affected_rows;
            }else return false;
        }
        
        /**
         * @return the $user
         */
        public function getUser()
        {
            return $this->user;
        }
    
        /**
         * @return the $wordbase
         */
        public function getWordbase()
        {
            return $this->wordbase;
        }
    
        /**
         * @return the $consume
         */
        public function getConsume()
        {
            return $this->consume;
        }
    
        /**
         * @return the $learn
         */
        public function getLearn()
        {
            return $this->learn;
        }
    
        /**
         * @return the $table
         */
        public function getTable()
        {
            return $this->table;
        }
    
        /**
         * @param field_type $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }
    
        /**
         * @param field_type $wordbase
         */
        public function setWordbase($wordbase)
        {
            $this->wordbase = $wordbase;
        }
    
        /**
         * @param field_type $consume
         */
        public function setConsume($consume)
        {
            $this->consume = $consume;
        }
    
        /**
         * @param field_type $learn
         */
        public function setLearn($learn)
        {
            $this->learn = $learn;
        }
    
        /**
         * @param field_type $table
         */
        public function setTable($table)
        {
            $this->table = $table;
        }
    
        
        
    }
?>
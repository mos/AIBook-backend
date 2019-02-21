<?php 
class sql {
    private $host = '';
    private $user = '';
    private $password = '';
    private $database = '';
    private $con;
    
    function __construct($host='localhost',$user='root',$pwd='root') {
        $this->host = $host;
        $this->user = $user;
        $this->pwd = $pwd;
    }
    /* 数据库连接 */
    function connect(){
        $con = new mysqli($this->host,$this->user,$this->password);
        $con->set_charset("utf8");
        $this->con = $con;
    }
    /* 选择库 */
    function setDatabase($database){
        $this->database = $database;
        $this->con->select_db($database);
    }
    /*  关闭连接 */
    function closeCon(){
        if($this->con->close()){
            return true;
        }else{
            return false;
        }
    }
    /* 增 */
    function addData($table,$name,$value){
        $sql = "insert into ".$table ."(".$name.")". "values(".$value.")";
        if($this->con->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    /* 删 */
    function delData($table,$where,$aim){
        $sql = "delete from ".$table." where ".$where."=".$aim;
        if($this->con->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    /* 改 */
    function updateData($table,$where,$aim,$update_name,$update_value){
        $sql = "update ".$table." set ".$update_name."=".$update_value." where ".$where."="."$aim";
        if($this->con->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    /* 查 */
    function findData($table,$where="",$aim="",$name="*"){
        if($where==""&&$aim==""){
            $sql = "select $name from $table";
        }else {
            $sql = "select $name from $table where $where=$aim";
        }
        $reslut = $this->con->query($sql);
        if($reslut){
            return $reslut;
        }else{
            return false;
        }
    }
}

?>
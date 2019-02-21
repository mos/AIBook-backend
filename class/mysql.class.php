<?php 
//include_once '../config/config.php';  //这样会报错找不到
    
class sql{
    private $con;
    private $result;
    
    /**
     * 数据库连接:初始化
     * @return void
     */
    function init(){
        $this->con = new mysqli(SQL_HOST, SQL_USER, SQL_PWD);
        $this->con->set_charset("utf8");
        $this->con->select_db(SQL_DATABASE);
    }
    
    /**
     * 执行语句
     * @param string $sql
     * @return mixed|boolean
     */
    function exec($sql){
        $this->result = $this->con->query($sql);
        if($this->result){
            return $this->result;
        }else{
            return false;
        }
    }
    
    /**
     * 执行并返回 单一数组（普通数组）
     * @param string $sql
     * @param string $name
     * @return array|boolean
     */
    function exec_getArray($sql, $name){
        $this->result = $this->con->query($sql);
        while ($row = mysqli_fetch_array($this->result))$array[] = $row[$name];
        if(count($array)>0) return $array;
        else return false;
    }
    
    /**
     * 获取新增的id
     * @return int
     */
    function getId(){
        return $this->con->insert_id;
    }
    
    /**
     * 获取影响的行数
     * @return int
     */
    function getRows(){
        return $this->con->affected_rows;
    }
    
    /**
     * 关闭连接
     * @return boolean
     */
    function closeCon(){
        if($this->con->close()){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * @return the $con
     */
    public function getCon()
    {
        return $this->con;
    }

    /**
     * @return the $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mysqli $con
     */
    public function setCon($con)
    {
        $this->con = $con;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    
}

?>
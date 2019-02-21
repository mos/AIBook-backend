<?php
require_once '../config/config.php';
require_once 'class/state.class.php';
require_once 'class/mysql.class.php';

session_start();
if($_POST['msgCode']==$_SESSION['phone_check_code']){
    $state = new state();
    $mysql = new sql();
    $mysql->init();
    switch ($_SESSION['action']){
        case "register":{
            $phone = $_SESSION['reg-user'];
            $pwd = $_SESSION['reg-pwd'];
            $regtime = time();
            $sql = "insert into userinfo (uphone,upwd,regtime) values($phone,'$pwd',$regtime)";
            if($mysql->exec($sql))echo $_SESSION['aimurl']."?res=success";
            else echo $_SESSION['aimurl']."?res=failed";
            $state->delSession('reg-user');
            $state->delSession('reg-pwd');
            $state->delSession('aimurl');
            $state->delSession('action');
            $mysql->closeCon();
            break;
        }
        case "forget":{
            echo $_SESSION['aimurl'];
            $state->delSession('aimurl');
            $state->delSession('action');
            break;
        }
    }
}else{
    echo "error";
}
?>
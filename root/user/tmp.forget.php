<?php
    include '../config/config.php';
    require_once 'class/state.class.php';
    require_once 'sendCheckMsg.php';
    session_start();
    if($_POST['code']==$_SESSION['check_code']){
        if(!empty($_POST['user'])){
            $state = new state();
            $state->setSession("forget-user", $_POST['user']);//session临时储存用户信息
            $state->setSession("action", "forget");//用于下一步判断行为
            $state->setSession("aimurl", $reSetpwd);//用于下一步跳转
            sendPhoneMsg($_POST['user']);//发送短信验证码
        }
        echo "ok";
    }else{
        echo "error";
    }
?>
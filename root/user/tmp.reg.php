<?php
    include '../config/config.php';
    require_once 'class/state.class.php';
    require_once 'sendCheckMsg.php';
    session_start();
    if($_POST['code']==$_SESSION['check_code']){
        if(!empty($_POST['user'])&&!empty($_POST['pwd'])){
            $state = new state();
            $state->setSession("reg-user", $_POST['user']);//session临时储存用户注册信息
            $state->setSession("reg-pwd", $_POST['pwd']);
            $state->setSession("action", "register");//用于下一步判断行为
            $state->setSession("aimurl", $regResult);//用于下一步跳转
            sendPhoneMsg($_POST['user']);//发送短信验证码
        }
        echo "ok";
    }else{
        echo "error";
    }
?>
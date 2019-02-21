<?php 
    require_once '../api/sendmsg/sendMsg.php';
    require_once '../class/state.class.php';
    include '../api/verCode.php';
    
    function sendPhoneMsg($phone){
        set_time_limit(0);
        /*生成短信验证码*/
        $ver_code = createRandNumber(6);
        /*session储存以便验证*/
        $state = new state();
        $state ->setSession("phone_check_code", $ver_code);
        /*     测试数据 */
        /* $phone = "15524807787"; */
       
        $response = SmsDemo::sendSms($phone,$ver_code);
        return $ver_code;
    }
?>
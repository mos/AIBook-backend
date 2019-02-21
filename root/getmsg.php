<?php 
    /*
     * 获取手机验证码
     * */
    require_once '../function/sendCheckMsg.php';
    if(isset($_GET['phone']))$phone = $_GET['phone'];
    else $phone = $_POST['phone'];
    //$phone = $_POST['phone'];
    $ckmsg['ckmsg'] = sendPhoneMsg($phone);
    echo json_encode($ckmsg);
?>
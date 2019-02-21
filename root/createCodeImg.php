<?php 
    include '../api/verCode.php';
    require_once '../class/state.class.php';
    session_start();
    /*生成验证码*/
    $ver_code = createRandNumber(4);
    /*session储存以便验证*/
    /* $state = new state();
    $state ->setSession("check_code", $ver_code);
     */
    $_SESSION['check_code'] = $ver_code;
    createVerCodeImg($ver_code);
?>
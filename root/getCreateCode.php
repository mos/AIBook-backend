<?php 
    /* header('Access-Control-Allow-Origin:*'); */
    session_start();
    $code['code'] = $_SESSION['check_code'];
    echo json_encode($code);
    exit();
?>
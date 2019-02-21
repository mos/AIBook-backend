<?php 
/**
* Create new token for user login and keep state.
* @author: Patrick Jun
* @date: 2018年10月26日 下午8:02:04
*/
    require_once '../class/token.class.php';
    $token = new token();
    $newtoken['token'] = $token->create();
    echo json_encode($newtoken);
?>
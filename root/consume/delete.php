<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/consume.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/consume/delete.php?cid=1&token=12312312312312312312312312312313
    $cid = $_POST['cid'];
    $token = $_POST['token'];
    
    if(!empty($token)){
        $con = new sql();
        $user = new User();
        $con->init();
        $sql = "select * from user where token='$token'";
        $result = $con->exec($sql);
        $user->setUser(mysqli_fetch_array($result));
        //echo "<pre>";var_dump($user->array());
        if($token == $user->getUser()['token']){ //验证token
            //获取数据库内容
            $consume = new Consume();
            $uid = $user->getUid();
            $sql = "select * from consume where cid=$cid and uid=$uid";
            $result = $con->exec($sql);
            if($result->num_rows == 1){
                $consume->setConsume(mysqli_fetch_array($result));
                $db = new DB();
                //删除操作
                if($db->delete('consume',$consume) == 1){
                    $return['disc'] = "删除成功";
                    $return['code'] = "100";
                    $return['data'] = null;
                }else {
                    $return['disc'] = "服务器删除出错";
                    $return['code'] = "500";
                    $return['data'] = null;
                }
            }else {
                $return['disc'] = "该用户不存在此cid=".$cid."的账单";
                $return['code'] = "110";
                $return['data'] = null;
            }
        }else {
            $return['disc'] = "token错误，非法操作";
            $return['code'] = "110";
            $return['data'] = null;
        }
        
        $con->closeCon();
    }else {
        $return['disc'] = "无token，非法操作";
        $return['code'] = "110";
        $return['data'] = null;
    }
    //echo '<pre>';var_dump($return);
    echo json_encode($return);
    exit();
?>
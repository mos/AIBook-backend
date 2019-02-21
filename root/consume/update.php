<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/consume.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/consume/update.php?action=ctype,cmoney&cid=1&token=12312312312312312312312312312313&type=out&money=12
    $action = $_POST['action'];
    $cid = $_POST['cid'];
    $token = $_POST['token'];
    
    $ctime = $_POST['time'];
    $cmoney = $_POST['money'];
    $ctype = $_POST['type'];
    $cthing = $_POST['thing'];
    
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
                //判断修改的内容，并修改
                if($action == 'all' || $action == '*' || $action == 'consume'){
                    $consume->setCmoney($cmoney);
                    $consume->setCthing($cthing);
                    $consume->setCtime($ctime);
                    $consume->setCtype($ctype);
                }else {
                    $actions = explode(',', $action);
                    foreach ($actions as $act ){
                        switch ($act){
                            case 'ctime': $consume->setCtime($ctime);break;
                            case 'cmoney': $consume->setCmoney($cmoney);break;
                            case 'ctype': $consume->setCtype($ctype);break;
                            case 'cthing': $consume->setCthing($cthing);break;
                        }
                    }
                }
                //更新操作
                if($db->update('consume',$consume)){
                    $return['disc'] = "更新成功";
                    $return['code'] = "100";
                    $return['data'] = $consume->array();
                }else {
                    $return['disc'] = "服务器更新出错";
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
<?php     
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/consume.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/consume/insert.php?token=12312312312312312312312312312313&type=out&money=12&time=1550462400000&thing=测试
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
            //获取内容
            $consume = new Consume();
            $consume->setCmoney($cmoney);
            $consume->setCthing($cthing);
            $consume->setCtime($ctime);
            $consume->setCtype($ctype);
            $consume->setUid($user->getUid());
            //新增操作
            $db = new DB();
            $insert_id = $db->insert('consume',$consume);
            if($insert_id){
                $consume->setCid($insert_id);
                $return['disc'] = "添加成功";
                $return['code'] = "100";
                $return['data'] = $consume->array();
            }else {
                $return['disc'] = "服务器更新出错";
                $return['code'] = "500";
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
    echo '<pre>';var_dump($return);
    echo json_encode($return);
    exit();
?>
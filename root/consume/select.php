<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/consume.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/consume/select.php?day=2019-02-18&token=12312312312312312312312312312313
    $time = $_POST['day'];
    $token = $_POST['token'];
    
    //php.ini设置 date.timezone = Asia/Shanghai
    $today = strtotime($time)."000";
    $tomarrow = (strtotime($time." +1day")-1)."000";
    
    if(!empty($token)){
        $con = new sql();
        $user = new User();
        $con->init();
        $sql = "select * from user where token='$token'";
        $result = $con->exec($sql);
        $user->setUser(mysqli_fetch_array($result));
        //echo "<pre>";var_dump($user->getArray());
        if($token == $user->getUser()['token']){ //验证token
            //获取数据库内容
            $uid = $user->getUid();
            $sql = "select * from consume where uid=$uid and (ctime between '$today' and '$tomarrow')";
            //echo $sql;
            $result = $con->exec($sql);
            if($result->num_rows >= 1){
                $return['disc'] = "查询成功，共".$result->num_rows."条记录";
                $return['code'] = "100";
                $consume = new Consume();
                for ($i = 0; $i<$result->num_rows; $i++){
                    $consume->setConsume(mysqli_fetch_array($result));
                    $return['data'][$i] = $consume->getArray();
                }
            }else {
                $return['disc'] = "uid=".$uid."查询到0条记录";
                $return['code'] = "120";
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
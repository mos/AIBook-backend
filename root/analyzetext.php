<?php
require_once '../config/config.php';
require_once '../class/mysql.class.php';
require_once '../class/state.class.php';
require_once '../class/analyze.class.php';
require_once '../api/voiceTransfer/splitWord.php';
require_once '../class/consume.class.php';
require_once '../class/user.class.php';
require_once '../class/db.class.php';

//header("content-type:text/html;charset=utf-8");
//     https://dc.1zdz.cn/api/root/analyzetext.php?token=12312312312312312312312312312313&userstr=我中午买包子花了十块钱


$requeststr = $_POST['userstr'];
$token = $_POST['token'];


$return = array();

if(!empty($token)){
    $con = new sql();
    $user = new User();
    $con->init();
    $sql = "select * from user where token='$token'";
    $result = $con->exec($sql);
    $user->setUser(mysqli_fetch_array($result));
    $con->closeCon();
    //echo "<pre>";var_dump($user->array());
    if($token == $user->getUser()['token']){  //验证是否存在此token
        $consume = new Consume();
        //开始句子分析
        $data=splitThis($requeststr);
        $analyze = new analyze();
        $analyze->getStr($data);
        $consume->setConsume($analyze->extractKeyWords());
        if(!empty($consume->getConsume())){
            $db = new DB();
            $consume->setUid($user->getUid());
            $insert_id = $db->insert('consume', $consume);
            if($insert_id){
                $consume->setCid($insert_id);
                if($consume->getCthing() == null)$consume->setCthing($requeststr);  //当事件分析失败时，原句返回
                $return['disc'] = "分析成功";
                $return['code'] = "100";
                $return['data'] = $consume->array();
            }else{
                $return['disc'] = "分析成功，插入数据库失败";
                $return['code'] = "120";
                $return['data'] = null;
            }
        }
        else{
            $return['disc'] = "分析失败";
            $return['code'] = "120";
            $return['data'] = null;
        }
    }else{
        $return['disc'] = "未识别的token，非法操作";
        $return['code'] = "110";
        $return['data'] = null;
    }
}else{
    $return['disc'] = "未获取到token，非法操作";
    $return['code'] = "110";
    $return['data'] = null;
}
//echo "<pre>";var_dump($return);
echo json_encode($return);
exit();
?>
    


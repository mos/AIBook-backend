<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/learn.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/learn/update.php?admin=qwe123&aid=1&word=更新&type=收&class=consumeWords&fq=1
    $isadmin = $_GET['admin'];
    
    $aid = $_GET['aid'];
    $afq = $_GET['fq'];
    $aword = $_GET['word'];
    $aclass = $_GET['class'];
    $atype = $_GET['type'];
    
    if(!empty($isadmin)){
        if($isadmin == ADMIN_PWD){ //验证管理口令. 在config里设置
            //设置内容
            $learn = new Learn();
            $learn->setAclass($aclass);
            $learn->setAfq($afq);
            $learn->setAid($aid);
            $learn->setAtype($atype);
            $learn->setAword($aword);
            //更新操作
            $db = new DB();
            if($db->update('learn', $learn)){
                $return['disc'] = "更新成功";
                $return['code'] = "100";
                $return['data'] = $learn->getArray();
            }else {
                $return['disc'] = "更新出错，aid=".$aid;
                $return['code'] = "120";
                $return['data'] = null;
            }
        }else {
            $return['disc'] = "管理口令错误，非法操作";
            $return['code'] = "110";
            $return['data'] = null;
        }
    }else {
        $return['disc'] = "无管理口令，非法操作";
        $return['code'] = "110";
        $return['data'] = null;
    }
    //echo '<pre>';var_dump($return);
    //仅调试输出
    //echo json_encode($return);
    exit();
?>
<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/wordbase.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/wordbase/update.php?admin=qwe123&wid=1&word=更新&type=收&class=consumeWords
    $isadmin = $_GET['admin'];
    
    $wid = $_GET['wid'];
    $wword = $_GET['word'];
    $wclass = $_GET['class'];
    $wtype = $_GET['type'];
    
    if(!empty($isadmin)){
        if($isadmin == ADMIN_PWD){ //验证管理口令. 在config里设置
            //设置内容
            $wordbase = new Wordbase();
            $wordbase->setWid($wid);
            $wordbase->setWclass($wclass);
            $wordbase->setWtype($wtype);
            $wordbase->setWword($wword);
            //更新操作
            $db = new DB();
            if($db->update('wordbase', $wordbase)){
                $return['disc'] = "更新成功";
                $return['code'] = "100";
                $return['data'] = $wordbase->getArray();
            }else {
                $return['disc'] = "更新出错，wid=".$wid;
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
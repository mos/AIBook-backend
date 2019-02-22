<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/wordbase.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/wordbase/insert.php?admin=qwe123&word=测试&type=收&class=consumeWords
    $isadmin = $_GET['admin'];
    
    $wword = $_GET['word'];
    $wclass = $_GET['class'];
    $wtype = $_GET['type'];
    
    if(!empty($isadmin)){
        if($isadmin == ADMIN_PWD){ //验证管理口令. 在config里设置
            //设置内容
            $wordbase = new Wordbase();
            $wordbase->setWclass($wclass);
            $wordbase->setWtype($wtype);
            $wordbase->setWword($wword);
            //新增操作
            $db = new DB();
            $insert_id = $db->insert('wordbase',$wordbase);
            if($insert_id){
                $wordbase->setWid($insert_id);
                $return['disc'] = "添加成功";
                $return['code'] = "100";
                $return['data'] = $wordbase->getArray();
            }else {
                $return['disc'] = "服务器更新出错";
                $return['code'] = "500";
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
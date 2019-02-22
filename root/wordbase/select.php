<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/wordbase.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/wordbase/select.php?admin=qwe123
    $isadmin = $_GET['admin'];
    
    if(!empty($isadmin)){
        if($isadmin == ADMIN_PWD){ //验证管理口令. 在config里设置
            //查询操作
            $db = new DB();
            $result = $db->select('wordbase');
            if($result->num_rows >= 1){
                $return['disc'] = "查询成功，共".$result->num_rows."条记录";
                $return['code'] = "100";
                $wordbase = new Wordbase();
                for ($i = 0; $i<$result->num_rows; $i++){
                    $wordbase->setWordbase(mysqli_fetch_array($result));
                    $return['data'][$i] = $wordbase->getArray();
                }
            }else {
                $return['disc'] = "查询到0条记录";
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
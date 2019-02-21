<?php
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/learn.class.php';
    require_once '../../class/wordbase.class.php';
    require_once '../../class/db.class.php';
    require_once '../../class/curlto.class.php';
    
    $return = array();
    //     https://dc.1zdz.cn/api/root/learn/learn.php?admin=qwe123&aid=1
    $isadmin = $_GET['admin'];
    
    $aid = $_GET['aid'];
    
    if(!empty($isadmin)){
        if($isadmin == ADMIN_PWD){ //验证管理口令. 在config里设置
            $learn = new Learn();
            //获取数据库信息
            $con = new sql();
            $db = new DB();
            $con->init();
            $sql = "select * from learn where aid=$aid";
            $result = $con->exec($sql);
            $learn->setLearn(mysqli_fetch_array($result));
            //开始学习
            $learn->doLearn();
            //判断学习成果 再进行下一步操作
            if($learn->isOk()){
                //学习完成，进行更新词库，再更新学习记录
                $wordbase = new Wordbase();
                $wordbase = $learn->doEnd();
                //更新词库
                $curl = new curlTo($isadmin, 'root/wordbase/insert.php');
                $res = $curl->send($wordbase->array());   
                //curl执行后，wordbase那边有输出，但这边会输出空数组。因此打算两边都去掉输出。输出仅调试时开启
                //echo "</pre>";var_dump($res);
                //$res = json_decode($res);
                if($res){
                    //更新学习记录
                    if($db->update('learn', $learn)){
                        $return['disc'] = "学习完成，已加入词库";
                        $return['code'] = "100";
                        $return['data'] = $learn->array();
                    }else {
                        $return['disc'] = "已加入词库，但更新学习记录出错，aid=".$aid;
                        $return['code'] = "120";
                        $return['data'] = null;
                    }
                }else{
                    $return['disc'] = "加入词库出错，未更新学习记录，aid=".$aid;
                    $return['code'] = "120";
                    $return['data'] = null;
                }
                
            }else {
                if($learn->afq > LEARN_FQ){
                    //已学习过，更新学习记录
                    if($db->update('learn', $learn)){
                        $return['disc'] = "已学习完成，本次仅更新学习记录，不再次加入词库";
                        $return['code'] = "100";
                        $return['data'] = $learn->array();
                    }else {
                        $return['disc'] = "学习出错，aid=".$aid;
                        $return['code'] = "120";
                        $return['data'] = null;
                    }
                }else{
                    //学习成果不足，更新当前学习进度
                    if($db->update('learn', $learn)){
                        $return['disc'] = "学习成功";
                        $return['code'] = "100";
                        $return['data'] = $learn->array();
                    }else {
                        $return['disc'] = "学习出错，aid=".$aid;
                        $return['code'] = "120";
                        $return['data'] = null;
                    }
                }
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
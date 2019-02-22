<?php
    require_once '../config/config.php';
    
    require_once '../api/voiceTransfer/AipSpeech.php';
    require_once '../api/voiceTransfer/AipNlp.php';
    require_once '../api/voiceTransfer/splitWord.php';
    
    require_once '../class/mysql.class.php';
    require_once '../class/state.class.php';
    require_once '../class/analyze.class.php';
    require_once '../class/consume.class.php';
    require_once '../class/user.class.php';
    require_once '../class/db.class.php';
//    header("content-type:text/html;charset=utf-8");
//    $_FILES["uservoice"]["size"] < 1024000)
    
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
            if ($_FILES["uservoice"]["error"] > 0)
            {
                //错误处理，存入错误日志记录
            }else{
                //移动并重命名文件
                $s_path = "voicetmp/";
                $splitname=explode(".", $_FILES["uservoice"]["name"]);
                $typename=end($splitname);
                $result=move_uploaded_file($_FILES["uservoice"]["tmp_name"], $s_path.'user'.$user->getUid().'.'.$typename);
                if($result){
                    //实例化消费记录
                    $consume = new Consume();
                    //语音转码
                    $command='ffmpeg -y -i '.$s_path.'user'.$user->getUid().'.'.$typename.'  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 '.$s_path.'user'.$user->getUid().'.'.$typename.'.pcm';
                    exec($command);
                    //语音识别
                    $Transfer = new AipSpeech(BD_APP_ID, BD_API_KEY, BD_SECRET_KEY);
                    $result=$Transfer->asr(file_get_contents($s_path.'user'.$user->getUid().'.'.$typename.'.pcm'), 'pcm', 16000,array('dev_pid' => '1536'));
                    //开始句子分析
                    $data=splitThis($result["result"][0]);
                    $analyze = new analyze();
                    $analyze->getStr($data);
                    $consume->setConsume($analyze->extractKeyWords());
                    if(!empty($consume->getConsume())){
                        $db = new DB();
                        $consume->setUid($user->getUid());
                        $insert_id = $db->insert('consume', $consume);
                        if($insert_id){
                            $consume->setCid($insert_id);
                            if($consume->getCthing() == null)$consume->setCthing($result["result"][0]);  //当事件分析失败时，原句返回
                            $return['disc'] = "分析成功";
                            $return['code'] = "100";
                            $return['data'] = $consume->getArray();
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
                }else {
                    $return['disc'] = "语音文件上传失败";
                    $return['code'] = "120";
                    $return['data'] = null;
                }
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
    


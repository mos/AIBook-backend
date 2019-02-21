<?php
    include '../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/user.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //action中逗号   %2c
    //     https://dc.1zdz.cn/api/root/user/update.php?action=uname%2cuphoone&phone=17866508676&password=123123&token=12312312312312312312312312312315
    $action = $_POST['action'];
    $token = $_POST['token'];
    
    $usex = $_POST['sex'];
    $urole = $_POST['role'];
    $uname = $_POST['name'];
    $ulogo = $_POST['logo'];
    $unote = $_POST['note'];
    $uplan = $_POST['plan'];
    $ainame = $_POST['ainame'];
    $ailogo = $_POST['ailogo'];
    $uphone = htmlspecialchars(trim($_POST['phone']));
    $upassword = md5(htmlspecialchars(trim($_POST['password'])));
    
    $oldpwd = md5(htmlspecialchars(trim($_POST['oldpwd'])));
    
    if(!empty($token)){
        $con = new sql();
        $user = new User();
        $con->init();
        $sql = "select * from user where token='$token'";
        $result = $con->exec($sql);
        $user->setUser(mysqli_fetch_array($result));
        //echo "<pre>";var_dump($user->array());
        if($token == $user->getUser()['token']){
            $db = new DB();
            $flag = true;//用于标志手机号和密码修改是否出错
            //判断修改的内容，并修改
            if($action == 'user'){
                $user->setUname($uname);
                $user->setUnote($unote);
                $user->setUplan($uplan);
                $user->setUrole($urole);
                $user->setUsex($usex);
            }elseif ($action == 'ai'){
                $user->setAiname($ainame);
            }else {
                $actions = explode(',', $action);
                foreach ($actions as $act ){
                    switch ($act){
                        case 'uname': $user->setUname($uname);break;
                        case 'unote': $user->setUnote($unote);break;
                        case 'usex': $user->setUsex($usex);break;
                        case 'urole': $user->setUrole($urole);break;
                        case 'ulogo': $user->setUlogo($ulogo);break;
                        case 'uplan': $user->setUplan($uplan);break;
                        case 'ainame': $user->setAiname($ainame);break;
                        case 'ailogo': $user->setAilogo($ailogo);break;
                        case 'uphone': {
                            $sql = "select uphone from user";
                            $result = $con->exec($sql);
                            while ($row = mysqli_fetch_array($result))$allphone[] = $row['uphone'];
                            if(!in_array($user->getUphone(), $allphone))$user->setUphone($uphone);
                            else {
                                $flag = false;
                                $return['disc'] = "该手机号已被注册";
                                $return['code'] = "120";
                                $return['data'] = null;
                            }
                            break;
                        }
                        case 'upassword': {
                            if($oldpwd == $user->getUpasswd())$user->setUpasswd($upassword);
                            else {
                                $flag = false;
                                $return['disc'] = "原密码错误";
                                $return['code'] = "110";
                                $return['data'] = null;
                            }break;
                        }
                    }
                }
            }
            //更新操作
            if($flag){
                if($db->update('user',$user)){
                    $return['disc'] = "更新成功";
                    $return['code'] = "100";
                    $return['data'] = $user->array();
                }else {
                    $return['disc'] = "服务器更新出错";
                    $return['code'] = "500";
                    $return['data'] = null;
                }
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
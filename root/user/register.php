<?php 
/**
* for user register.
* @author: Patrick Jun
* @date: 2018年10月26日 下午8:03:17
*/
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/user.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //获取用户注册信息
//    https://dc.1zdz.cn/api/root/register.php?phone=17866508676&password=123123&token=12312312312312312312312312312312
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $token = $_POST['token'];
    
    $user = new User();
    $user->setUphone(htmlspecialchars(trim($phone)));
    $user->setUpasswd(md5(htmlspecialchars(trim($password))));
    $user->setToken($token);
    
    if(strlen($user->getUphone())==11){
        $con = new sql();
        $con->init();
        $sql = "select uphone from user";
        $result = $con->exec($sql);
        while ($row = mysqli_fetch_array($result))$allphone[] = $row['uphone'];
        if(!in_array($user->getUphone(), $allphone)){
            $db = new DB();
            if($db->insert('user', $user)){
                //获取用户数据
                $phone = $user->getUphone();
                $sql = "select * from user where uphone='$phone'";
                $result = $con->exec($sql);
                $user->setUser(mysqli_fetch_array($result));
                
                $return['disc'] = "注册成功";
                $return['code'] = "100";
                $return['data'] = $user->array();
            }
            else{
                $return['disc'] = "服务器新增数据出错";
                $return['code'] = "500";
                $return['data'] = null;
            }
        }else{
            $return['disc'] = "手机号已注册";
            $return['code'] = "120";
            $return['data'] = null;
        }
        
    }else{
        $return['disc'] = "手机号位数错误";
        $return['code'] = "120";
        $return['data'] = null;
    }
    //echo '<pre>';var_dump($return);
    echo json_encode($return);
    $con->closeCon();
    exit();
?>
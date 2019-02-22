<?php 
/**
* for user login.
* @author: Patrick Jun
* @date: 2018年10月26日 下午8:22:37
*/
    require_once '../../config/config.php';
    require_once '../../class/mysql.class.php';
    require_once '../../class/user.class.php';
    require_once '../../class/db.class.php';
    
    $return = array();
    //获取用户登录信息
//     https://dc.1zdz.cn/api/root/user/login.php?phone=17866508676&password=123123&token=12312312312312312312312312312315
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $token = $_POST['token'];
    
    $phone = htmlspecialchars(trim($phone));
    $password = md5(htmlspecialchars(trim($password)));
    
    if(!empty($phone)&&!empty($password)&&strlen($phone)==11){
        $con = new sql();
        $user = new User();
        $con->init();
        $sql = "select uphone from user";
        $result = $con->exec($sql);
        while ($row = mysqli_fetch_array($result))$allphone[] = $row['uphone'];
        if(!in_array($phone, $allphone)){
            $return['disc'] = "该手机号未注册";
            $return['code'] = "120";
            $return['data'] = null;
        }else{
            $sql = "select * from user where uphone=$phone";
            $result = $con->exec($sql);
            $user->setUser(mysqli_fetch_array($result));
            if($password == $user->getUser()['upasswd']){
                $user->setToken($token);
                $db = new DB();
                if($db->update('user',$user)){
                    $return['disc'] = "登录成功";
                    $return['code'] = "100";
                    $return['data'] = $user->getArray();
                }
                else {
                    $return['disc'] = "服务器更新记录token发生错误";
                    $return['code'] = "500";
                    $return['data'] = null;
                }
            }
            else {
                $return['disc'] = "密码错误";
                $return['code'] = "110";
                $return['data'] = null;
            }
        }
    }
    //echo '<pre>';var_dump($return);
    echo json_encode($return);
    $con->closeCon();
    exit();
?>
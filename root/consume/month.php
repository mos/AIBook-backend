<?php
require_once '../../config/config.php';
require_once '../../class/mysql.class.php';
require_once '../../class/consume.class.php';
require_once '../../class/db.class.php';
require_once '../../function/getThisMonth.php';

$return = array();
//     https://dc.1zdz.cn/api/root/consume/month.php?month=2019-02&token=12312312312312312312312312312313
$time = $_POST['month'];
$token = $_POST['token'];
$month = getThisMonth($time);
//php.ini设置 date.timezone = Asia/Shanghai
$monthstart = $month['start'];
$monthend = $month['end'];

if(!empty($token)){
    $con = new sql();
    $user = new User();
    $con->init();
    $sql = "select * from user where token='$token'";
    $result = $con->exec($sql);
    $user->setUser(mysqli_fetch_array($result));
    //echo "<pre>";var_dump($user->getArray());
    if($token == $user->getUser()['token']){ //验证token
        //获取数据库内容
        $uid = $user->getUid();
        $sql = "select * from consume where uid=$uid and (ctime between '$monthstart' and '$monthend')";
        //echo $sql;
        $result = $con->exec($sql);
        if($result->num_rows >= 1){
            $days = array();
            $return['disc'] = "查询成功，共".$result->num_rows."条记录";
            $return['code'] = "100";
            $consume = new Consume();
            for ($i = 0; $i<$result->num_rows; $i++){
                $consume->setConsume(mysqli_fetch_array($result));
                $day = date("j", substr($consume->getCtime(), 0, 10));
                for($k=1; $k<=31; $k++){
                    if($day == $k){
                        $days[$k]['day'] = $day;
                        if($consume->getCtype() == 'out')$days[$k]['out'] += $consume->getCmoney();
                        else if($consume->getCtype() == 'in')$days[$k]['in'] += $consume->getCmoney();
                    }else{
                        $days[$k]['day'] = $k;
                        $days[$k]['out'] = 0;
                        $days[$k]['in'] = 0;
                    }
                }
            }
            $return['data'] = $days;
            
        }else {
            $return['disc'] = "uid=".$uid."查询到0条记录";
            $return['code'] = "120";
            $return['data'] = null;
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
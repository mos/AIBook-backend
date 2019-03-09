<?php 
function getThisMonth($month){
    $setmonth = $month."-01";
    $lastday = (strtotime($setmonth." +1 month")-1)."000";
    $firstday = strtotime($setmonth)."000";
    return array(
        'start' => $firstday,
        'end' => $lastday
    );
}
?>
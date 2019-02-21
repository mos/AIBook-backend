<?php 
require_once 'config/config.php';
require_once 'class/wordbase.class.php';
require_once 'class/db.class.php';

// 执行时间有点长，将php.ini中设置max_execution_time = 300

$words = [
    'consumeWords' => [
        '租','买','花费','消费','缴费',
        '还','换','交易','汇款','购买',
        '支付','破费','缴纳','花','充值',
        '费','用','丢','不见','给',
        '输','借','给','捐','赠','交','缴','发红包'
    ],
    'incomeWords' => [
        '借','卖','贷款','贷','拾',
        '取','收','赚','赢','得',
        '捡','挣','还','给','干','多'
    ],
    'timeWords' => [
        '上个月','上午','下午','昨天',
        '前天','大前天','凌晨',
        '中午','傍晚','早晨',
        '晚上','夜间','正午','刚刚',
        '刚才','不久','现在',
        '白天','一下','清早','下晚',
        '半夜','早上','今天'
    ],
    'straightWords' => [
        '费','用','丢','不见','花',
        '捐','交','缴','输','借',
        '还','给','支付','汇款','消费',
        '充值','花费','缴费','发红包'
    ],
    'amountWords' => [
        '对','个','把','只','双','群',
        '串','条','张','块','寸','扎',
        '单','幅','件','次','颗','粒',
        '类','位','顶','辆','台','顿',
        '场','通','枚','道','阵','根',
        '头','具','株','支','枝','管',
        '面','份','部','剂','服','付',
        '顶','座','栋','扇','堵','间',
        '处','所','桩','笔','本','门',
        '盏','套','堆'
    ],
    'moneyWordsZ' => [
        '元','块'
    ],
    'moneyWordsS' => [
        '角','毛'
    ],
];

$wordbase = new Wordbase();
$db = new DB();
foreach ($words as $wclass=>$value){
    $wordbase->setWclass($wclass);
    switch ($wclass){
        case 'consumeWords': ;
        case 'straightWords': $wordbase->setWtype('out');break;
        case 'incomeWords': $wordbase->setWtype('in');break;
        case 'timeWords': ;
        case 'amountWords': ;
        case 'moneyWordsZ': ;
        case 'moneyWordsS': $wordbase->setWtype('wow');break;
    }
    foreach ($value as $wword){
        $wordbase->setWword($wword);
        $db->insert('wordbase', $wordbase);
    }
}

echo '初始化词库数据完成！请检查wordbase表是否成功。共148条数据';
exit();


// private static $consumeWords = [
//             '租','买','花费','消费','缴费',
//             '还','换','交易','汇款','购买',
//             '支付','破费','缴纳','花','充值',
//             '费','用','丢','不见','给',
//             '输','借','给','捐','赠','交','缴','发红包'
//         ];
//         private static $incomeWords = [
//             '借','卖','贷款','贷','拾',
//             '取','收','赚','赢','得',
//             '捡','挣','还','给','干','多'
//         ];
//         private static $timeWords = [
//             '上个月','上午','下午','昨天',
//             '前天','大前天','凌晨',
//             '中午','傍晚','早晨',
//             '晚上','夜间','正午','刚刚',
//             '刚才','不久','现在',
//             '白天','一下','清早','下晚',
//             '半夜','早上','今天'
//         ];
//         private static $straightWords = [
//             '费','用','丢','不见','花',
//             '捐','交','缴','输','借',
//             '还','给','支付','汇款','消费',
//             '充值','花费','缴费','发红包'
//         ];
//         private static $amountWords = [
//             '对','个','把','只','双','群',
//             '串','条','张','块','寸','扎',
//             '单','幅','件','次','颗','粒',
//             '类','位','顶','辆','台','顿',
//             '场','通','枚','道','阵','根',
//             '头','具','株','支','枝','管',
//             '面','份','部','剂','服','付',
//             '顶','座','栋','扇','堵','间',
//             '处','所','桩','笔','本','门',
//             '盏','套','堆'
//         ];
//         private static $moneyWordsZ = [
//           '元','块'
//         ];
//         private static $moneyWordsS = [
//             '角','毛'
//         ];
?>
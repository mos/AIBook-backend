<?php 
    require_once '../config/config.php';
    require_once 'mysql.class.php';
    
    class analyze{
        private $str = array();
        private static $consumeWords = [];
        private static $straightWords = [];
        private static $incomeWords = [];
        private static $timeWords = [];
        private static $amountWords = [];
        private static $moneyWordsZ = [
          '元','块'
        ];
        private static $moneyWordsS = [
            '角','毛'
        ];
        
        /**
         * 初始化 获取词库
         */
        function __construct(){
            $con = new sql();
            $con->init();
            //获取花费词
            $sql = "select * from wordbase where wclass='consumeWords'";
            self::$consumeWords = $con->exec_getArray($sql, 'wword');
            //获取直接消费词
            $sql = "select * from wordbase where wclass='straightWords'";
            self::$straightWords = $con->exec_getArray($sql, 'wword');
            //获取收入词
            $sql = "select * from wordbase where wclass='incomeWords'";
            self::$incomeWords = $con->exec_getArray($sql, 'wword');
            //获取时间词
            $sql = "select * from wordbase where wclass='timeWords'";
            self::$timeWords = $con->exec_getArray($sql, 'wword');
            //获取单位词
            $sql = "select * from wordbase where wclass='amountWords'";
            self::$amountWords = $con->exec_getArray($sql, 'wword');
        }
        
        /* 获取分词后的数组 */
        function getStr($string){
            $this->str = $string;
        }
        
        
        /*将汉字数字转为阿拉伯数字-----开始
         * 分割中文字符串，函数Han2Num需要*/
        private function mbStrSplit ($string, $len=1) {
            $start = 0;
            $strlen = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string,$start,$len,"utf8");
                $string = mb_substr($string, $len, $strlen,"utf8");
                $strlen = mb_strlen($string);
            }
            return $array;
        }
        private function is_num($p,$words){
            if($p != "个" && $words[$p] < 10) return true;
            return false;
        }
        private function is_emm($p){
            if($p == "万" || $p == "亿" ) return true;
            return false;
        }
        /*将汉字数字转为阿拉伯数字*/
        private function Han2Num($str){
            $words = array(
                "零"=>0,
                "一"=>1,
                "两"=>2,
                "二"=>2,
                "三"=>3,
                "四"=>4,
                "五"=>5,
                "六"=>6,
                "七"=>7,
                "八"=>8,
                "九"=>9,
                "个"=>1,
                "十"=>10,
                "百"=>100,
                "千"=>1000,
                "万"=>10000,
                "亿"=>100000000
            );
            if(is_numeric($str)){
                return $str;
            }else {
                $result = 0;
                $sum = 0;
                $tmp = 0;
                $flagnum = 1;
                $flag = false;
                $str = $this->mbStrSplit($str);
                for($i = 0; $i < sizeof($str); $i++){
                    if($i == 0 && $str[$i] == "十"){
                        $sum = 10;
                    }
                    if($this->is_emm($str[$i])){
                        $flagnum = $words[$str[$i]];
                        $sum += $tmp;
                        $tmp = 0;
                        $result += $sum*$words[$str[$i]];
                        $sum = 0;
                        
                    }
                    else if($this->is_num($str[$i],$words)){
                        $sum += $tmp;
                        $tmp = $words[$str[$i]];
                        
                        $flag = true;
                    }
                    else{
                        if($flag){
                            $flagnum = 1;
                        }
                        $tmp *= $words[$str[$i]];
                        $flagnum *= $words[$str[$i]];
                        $flag = false;
                    }
                }
                if(sizeof($str) != 1 && $str[sizeof($str)-2] != "零" && $flag) $tmp *= $flagnum/10;
                $result += $sum + $tmp;
                return $result;
            }
        }
        /*将汉字数字转为阿拉伯数字-----结束*/
        
        /*金钱金额提取*/
        private function getMoney($i1,$i2=null,$i3=null,$i4=null){
            $return = array();
            //句式1：动词+123+金钱词判断              用123元
            if($this->str[$i1]['wtype'] == 'm'){
                if(in_array($this->str[$i2]['word'], self::$moneyWordsZ)||empty($this->str[$i3]['word']))  //整元
                    $num = $this->Han2Num($this->str[$i1]['word']);
                    elseif (in_array($this->str[$i2]['word'], self::$moneyWordsS))//角，毛
                    $num = $this->Han2Num($this->str[$i1]['word'])/10;
                    $moneyIndex = $i1;
            }
            //句式2：动词+‘了’+123+金钱词判断        用了123元
            elseif ($this->str[$i1]['word'] == '了' && $this->str[$i2]['wtype'] == 'm'){
                if(in_array($this->str[$i3]['word'], self::$moneyWordsZ)||empty($this->str[$i4]['word'])) //整元
                    $num = $this->Han2Num($this->str[$i2]['word']);
                elseif (in_array($this->str[$i3]['word'], self::$moneyWordsS))//角，毛
                    $num = $this->Han2Num($this->str[$i2]['word'])/10;
                $moneyIndex = $i2;
            }
            //句式3：动词+物品名词（或句子）+123元
            elseif ($this->str[$i1]['wtype'] == 'n'){
                $x = $i1;
                for($x=$i2;$x<count($this->str);$x++){
                    if($this->str[$x]['wtype'] == 'm'){
                        if(in_array($this->str[$x+1]['word'], self::$moneyWordsZ)){
                            $num = $this->Han2Num($this->str[$x]['word']);
                            $moneyIndex = $x;
                        }
                        if(in_array($this->str[$x+1]['word'], self::$moneyWordsS)){
                            $num = $this->Han2Num($this->str[$x]['word'])/10;
                            $moneyIndex = $x;
                        }
                    }
                }
            }
            else {//出现错误情况
                $num = 0;$moneyIndex = -1;
            }
            
            /* bug词   */
            //1:  "三元"
            if($this->str[$i1]['word'] == '三元'){
                $num = 3;$moneyIndex = $i1;
            }
            
            $return['money']=$num;
            $return['index']=$moneyIndex;
            return $return;
        }
        
        /*分析提取关键词 返回数组*/
        function extractKeyWords(){
            $returnKeyWords = array();
            $time = "";    //储存时间、事件、费用、收支
            $thing = "";
            $money = "";
            $type = "";
            
            date_default_timezone_set('PRC');
            $nowYear = date('Y',time());
            $nowMonth = date('n',time());
            $nowDay = date('j',time());
            $nowTime = date('H:i',time());
            $overNoon = false;  //判断是否过中午
            if(date('H',time())>12)$overNoon = true;
            /*存储时间*/
            $month = $nowMonth;  //
            $day = $nowDay;//日期
            $hms = $nowTime;//具体时间

            /*存储费用*/
            $type = "支";
            $num = 0;
            
            /*分析、事件验证*/
            $timeIndex = false;//存时间尾词的下一词开始序号，用于事件判断
            /* $actionIndex = false; *///存消费行为动词序号，用于事件判断
            $moneyIndex = false;//存金额序号，用于事件判断
            
            for($i = 0; $i<count($this->str); $i++){
                /*
                 * 处理时间
                 * */
                /* echo "循环执行：".$i."次<br>"; */
                if($this->str[$i]['wtype'] == 't'){
                    //仅语言时间
                    if(in_array($this->str[$i]['word'], self::$timeWords)){
                        switch ($this->str[$i]['word']){
                            case '上个月':{$month -= 1;$timeIndex = $i+1;break;};
                            case '大前天':{ $day = $nowDay - 3;$timeIndex = $i+1;break;};
                            case '前天':{$day = $nowDay - 2;$timeIndex = $i+1;break;};
                            case '昨天':{$day = $nowDay - 1;$timeIndex = $i+1;break;};
                            case '清早':
                            case '早上':
                            case '早晨':{$hms = "06:00";$timeIndex = $i+1;break;};
                            case '白天':
                            case '今天':
                            case '上午':{$hms = "08:00";$timeIndex = $i+1;break;};
                            case '正午':
                            case '中午':{$hms = "12:00";$timeIndex = $i+1;$overNoon = true;break;};
                            case '下午':{$hms = "16:00";$timeIndex = $i+1;$overNoon = true;break;};
                            case '傍晚':{$hms = "18:00";$timeIndex = $i+1;$overNoon = true;break;};
                            case '夜间':
                            case '下晚':
                            case '晚上':{$hms = "20:00";$timeIndex = $i+1;$overNoon = true;break;};
                            case '凌晨':
                            case '半夜':{$hms = "00:00";$timeIndex = $i+1;break;}
                        }
                    }
                    //语言时间+钟点(仅整时)
                    if($this->str[$i+1]['wtype']=='m'&& 
                        $this->str[$i+2]['word']=='点'&& 
                        $this->str[$i+3]['word']!='半'&& 
                        $this->str[$i+3]['wtype']!='m'){
                        if($overNoon){
                            $hms = (12+$this->Han2Num($this->str[$i+1]['word'])).":00";
                        }else {
                            $hms = $this->Han2Num($this->str[$i+1]['word']).":00";
                        }
                        $timeIndex = $i+3;
                        if($this->str[$i+3]['word']=='分'||$this->str[$i+3]['word']=='多')$timeIndex = $i+4;
                    }
                    //语言时间+钟点(整时+半点)
                    elseif ($this->str[$i+1]['wtype']=='m'&&
                            $this->str[$i+2]['word']=='点'&&
                            $this->str[$i+3]['word']=='半'){
                        if($overNoon){
                            $hms = (12+$this->Han2Num($this->str[$i+1]['word'])).":30";
                        }else {
                            $hms = $this->Han2Num($this->str[$i+1]['word']).":30";
                        }
                        $timeIndex = $i+4;
                    }
                    //语言时间+钟点(整时+详细分)
                    elseif ($this->str[$i+1]['wtype']=='m'&&
                            $this->str[$i+2]['word']=='点'&&
                            $this->str[$i+3]['wtype']=='m'){
                        if($overNoon){
                            $hms = (12+$this->Han2Num($this->str[$i+1]['word'])).":".$this->Han2Num($this->str[$i+3]['word']);
                        }else {
                            $hms = $this->Han2Num($this->str[$i+1]['word']).":".$this->Han2Num($this->str[$i+3]['word']);
                        }
                        $timeIndex = $i+4;
                        if($this->str[$i+4]['word']=='分')$timeIndex = $i+5;
                    }
                }
                //仅钟点时间
                //钟点(仅整时)
                if($this->str[$i]['wtype']=='m'&&
                    $this->str[$i+1]['word']=='点'&&
                    $this->str[$i+2]['word']!='半'&&
                    $this->str[$i+2]['wtype']!='m'){
                    if($overNoon){
                        $hms = (12+$this->Han2Num($this->str[$i]['word'])).":00";
                    }else{
                        $hms = $this->Han2Num($this->str[$i]['word']).":00";
                    }
                    $timeIndex = $i+2;
                    if($this->str[$i+2]['word']=='分'||$this->str[$i+2]['word']=='多')$timeIndex = $i+3;
                }
                //钟点(整时+半点)
                elseif ($this->str[$i]['wtype']=='m'&&
                    $this->str[$i+1]['word']=='点'&&
                    $this->str[$i+2]['word']=='半'){
                    if($overNoon){
                        $hms = (12+$this->Han2Num($this->str[$i]['word'])).":30";
                    }else {
                        $hms = $this->Han2Num($this->str[$i]['word']).":30";
                    }
                    $timeIndex = $i+3;
                }
                //钟点(整时+详细分)
                elseif ($this->str[$i]['wtype']=='m'&&
                    $this->str[$i+1]['word']=='点'&&
                    $this->str[$i+2]['wtype']=='m'){
                    if($overNoon){
                        $hms = (12+$this->Han2Num($this->str[$i]['word'])).":".$this->Han2Num($this->str[$i+2]['word']);
                    }else {
                        $hms = $this->Han2Num($this->str[$i]['word']).":".$this->Han2Num($this->str[$i+2]['word']);
                    }
                    $timeIndex = $i+3;
                    if($this->str[$i+3]['word']=='分')$timeIndex = $i+4;
                }
                /* 时间bug词   */
                // 1： "一点"   分不开
                if($this->str[$i]['word']=='一点'){
                    if($overNoon){
                        $hms = "13:00";
                    }else {
                        $hms = "01:00";
                    }
                    $timeIndex = $i+1;
                    if($this->str[$i+2]['word']=='分'||$this->str[$i+2]['word']=='多')$timeIndex = $i+3;
                }
                /*
                 * 处理费用
                 * */
                if($this->str[$i]['wtype'] == 'v'||$this->str[$i]['wtype'] == 'Ng'){
                    /*获取金额*/
                    /*支出*/
                    if(in_array($this->str[$i]['word'], self::$consumeWords)){
                        $actionIndex = $i;
                        /*行为判断*/
                        switch ($this->str[$i]['word']){
                            case '给':
                            case '还':
                            case '借':{
                                //给我 ，给了我
                                if($this->str[$i+1]['word']=='我'||$this->str[$i+2]['word']=='我'){
                                    $type = "收";
                                }
                            };break;
                            //其他理应为支出
                        }
                        /*金额判断*/
                        $getMoney = $this->getMoney($i+1,$i+2,$i+3,$i+4);
                        $num = $getMoney['money'];
                        $moneyIndex = $getMoney['index'];
                        
                    }
                    /*收入*/
                    else if(in_array($this->str[$i]['word'], self::$incomeWords)){
                        /*行为判断*/
                        $type = "收";   //经过上一个if后，都应当为 收
                        /* switch ($this->str[$i]['word']){
                            case '给':
                            case '还':
                            case '借':{
                                //给我 ，给了我
                                if($this->str[$i+1]['word']=='我'||$this->str[$i+2]['word']=='我'){
                                    $type = "收";
                                }
                            };
                            //其他理应为收入
                            default:$type = "收";
                        } */
                        /*金额判断*/
                        $getMoney = $this->getMoney($i+1,$i+2,$i+3,$i+4);
                        $num = $getMoney['money'];
                        $moneyIndex = $getMoney['index'];
                    }
                }
               
            }
            
            
            //特殊时间处理    部分特殊时间词分词后的属性为d，而非t
            $FTword = $this->str[0]['word'];
            if($FTword=='刚刚'||$FTword=='刚才'||$FTword=='不久'||$FTword=='现在'||$FTword=='一下')$timeIndex = 1;
            /*金额特殊处理*/
            //终极句式:  123元
            for($z = 0; $z<count($this->str); $z++){
                if($this->str[$z]['wtype'] == 'm'){
                    if(in_array($this->str[$z+1]['word'], self::$moneyWordsZ)){
                        $num = $this->Han2Num($this->str[$z]['word']);
                        $moneyIndex = $z;
                    }
                    if(in_array($this->str[$z+1]['word'], self::$moneyWordsS)){
                        $num = $this->Han2Num($this->str[$z]['word'])/10;
                        $moneyIndex = $z;
                    }
                }
            }
            /*
             * 事件判断
             * */
            /* echo "timeIndex:".$timeIndex;
            echo "moneyIndex:".$moneyIndex; */
            if($timeIndex){
                if($this->str[$timeIndex]['word']=='，')$timeIndex++;
                for($i = $timeIndex; $i<count($this->str); $i++){
                    /* if($i<$moneyIndex && 
                       $this->str[$i]['word']!='，'&&(
                            (!(in_array($this->str[$i]['word'], self::$straightWords))||(
                                in_array($this->str[$i]['word'], self::$straightWords)&&((($i+1)!=$moneyIndex)||($this->str[$i+1]['word']=='了'&&($i+2)!=$moneyIndex))
                                ))
                           )){
                        echo "thing add".$i."time<br>";
                        $thing .= $this->str[$i]['word'];
                    } */
                    if ($i<$moneyIndex &&
                        $this->str[$i]['word']=='，'&&
                        (in_array($this->str[$i+1]['word'], self::$consumeWords)||in_array($this->str[$i+1]['word'], self::$incomeWords))){
                        /* echo "break1"; */
                        break;
                    }
                    elseif ($i<$moneyIndex &&
                        in_array($this->str[$i]['word'], self::$straightWords)&&
                        (($i+1)==$moneyIndex)||($this->str[$i+1]['word']=='了'&&($i+2)==$moneyIndex)){
                        /* echo "break2"; */
                        break;
                    }
                    elseif ($i==$moneyIndex){
                        /* echo "break3"; */
                        break;
                    }
                    else {
                        $thing .= $this->str[$i]['word'];
                    }
                }
            }
            /*事件准确性判断*/
            if(!$moneyIndex&&!$timeIndex){
                $thing = "句式分析失败！";
            }
            /*整理时间*/
           // $time = $nowYear."/".$month."/".$day." ".$hms;
            $strtime = $nowYear."-".$month."-".$day." ".$hms.":00";
            $time = strtotime($strtime);
	    //$time = $strtime;
            /*整理费用*/
            if($type=="收")$type = "in";
            else $type = "out";
            $money = $num;
            /*整理事件*/
            if(empty($thing))$thing = null;  //置空，外部根据null判断，是否原句返回
            /*返回数据整理*/
            $returnKeyWords['ctime'] = $time."000";
            $returnKeyWords['cthing'] = $thing;
            $returnKeyWords['cmoney'] = $money;
            $returnKeyWords['ctype'] = $type;
            return $returnKeyWords;
        }
    }
?>

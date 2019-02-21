<?php 
    class analyze{
        private $str = array();
        static $consumeWords = [
            '租','买','花费','消费','缴费',
            '还','换','交易','汇款','购买',
            '支付','破费','缴纳','花','充值',
            '费','用','丢','不见','给',
            '输','借','给','捐','赠'
        ];
        static $incomeWords = [
            '借','卖','贷款','贷','拾',
            '取','收','赚','赢','得',
            '捡','挣','还','给','干'
        ];
        static $timeWords = [
            '上个月','上午','下午','昨天',
            '前天','大前天','凌晨',
            '中午','傍晚','早晨',
            '晚上','夜间','正午','刚刚',
            '刚才','不久','现在',
            '白天','一下','清早','下晚',
            '半夜','早上','今天'
        ];
        static $amountWords = [
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
        ];
        static $moneyWordsZ = [
          '元','块',''
        ];
        static $moneyWordsS = [
            '角','毛'
        ];
        /* 获取分词后的数组 */
        function getStr($string){
            $this->str = $string;
        }
        /*分割中文字符串，函数Han2Num需要*/
        function mbStrSplit ($string, $len=1) {
            $start = 0;
            $strlen = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string,$start,$len,"utf8");
                $string = mb_substr($string, $len, $strlen,"utf8");
                $strlen = mb_strlen($string);
            }
            return $array;
        }
        /*将汉字数字转为阿拉伯数字    仅支持 亿 级以内*/
        function Han2Num($str){
            if(is_numeric($str)){   //如果已经是阿拉伯数字，直接返回
                return $str;
            }else {
            $num = 0;
            $num1 = 0;
            $num2 = 0;
            $words = $this->mbStrSplit($str);
            $wanFlag = 0;
            for($i=0;$i<count($words);$i++){
                switch ($words[$i]){
                    case '万':{$wanFlag = $i;break;}
                    case '九':{$words[$i] = '9';break;}
                    case '八':{$words[$i] = '8';break;}
                    case '七':{$words[$i] = '7';break;}
                    case '六':{$words[$i] = '6';break;}
                    case '五':{$words[$i] = '5';break;}
                    case '四':{$words[$i] = '4';break;}
                    case '三':{$words[$i] = '3';break;}
                    case '二':{$words[$i] = '2';break;}
                    case '一':{$words[$i] = '1';break;}
                    default:$words[$i] = $words[$i];
                }
            }
            if($wanFlag!=0){
                for($i=0;$i<=$wanFlag;$i++){
                    $words1[] = $words[$i];
                }
                for($j=$wanFlag+1;$j<count($words);$j++){
                    $words2[] = $words[$j];
                }
            }
            $tmp_num1 = array();
            $k = 0;
            for($i=0;$i<count($words1);$i++){
                switch ($words1[$i]){
                    case '万':{$tmp_num1[$k-1] += (int)$words1[$i-1];break;}
                    case '千':{$tmp_num1[$k++] = ((int)$words1[$i-1]*1000);break;}
                    case '百':{$tmp_num1[$k++] = ((int)$words1[$i-1]*100);break;}
                    case '十':{$tmp_num1[$k++] = ((int)$words1[$i-1]*10);break;}
                    case '零':$tmp_num1[$k++] = $words1[$i];
                }
            }
            $tmp_num2 = array();
            $k = 0;
            for($i=0;$i<count($words2);$i++){
                switch ($words2[$i]){
                    case '千':{$tmp_num2[$k++] = ((int)$words2[$i-1]*1000);break;}
                    case '百':{$tmp_num2[$k++] = ((int)$words2[$i-1]*100);break;}
                    case '十':{$tmp_num2[$k++] = ((int)$words2[$i-1]*10);break;}
                    case '零':$tmp_num2[$k++] = $words2[$i];
                }
            }
            for($i=0;$i<count($tmp_num1);$i++){
                if($tmp_num1[$i]!='零'){
                    $num1 += $tmp_num1[$i];
                }else{
                    $i++;
                    $num1 += $tmp_num1[$i];
                }
            }
            $num1 *=10000;
            for($i=0;$i<count($tmp_num2);$i++){
                if($tmp_num2[$i]!='零'){
                    $num2 += $tmp_num2[$i];
                }else{
                    $i++;
                    $num2 += $tmp_num2[$i];
                }
            }
            if($wanFlag!=0){
                $num = $num1+$num2;
            }
            else $num = $num2;
            return $num;
            }
        }
        
        /*金钱金额提取*/
        function getMoney(){
            //
        }
        
        /*分析提取关键词 返回数组*/
        function extractKeyWords(){
            $returnKeyWords = array();
            $time = "";    //储存时间、事件、费用、收支
            $thing = "";
            $money = "";
            $type = "";
            
            $nowYear = date('Y',time());
            $nowMonth = date('n',time());
            $nowDay = date('j',time());
            $nowTime = date('H:i',time());
            
            /*存储时间*/
            $month = $nowMonth;  //
            $day = $nowDay;//日期
            $hms = $nowTime;//具体时间

            /*存储费用*/
            $type = "支";
            $mum = 0;
            
            for($i = 0; $i<count($this->str); $i++){
                /*处理时间*/
                if($this->str[$i]['wtype'] == 't'){
                    /*如果存在语言时间，转数据*/
                    if(in_array($this->str[$i]['word'], $this->timeWords)){
                        switch ($this->str[$i]['word']){
                            case '上个月':{
                                $month -= 1;   //还需补充
                            };
                            case '大前天':{
                                $day = $nowDay - 3;
                            };
                            case '前天':{
                                $day = $nowDay - 2;
                            };
                            case '昨天':{
                                $day = $nowDay - 1;
                            };
                            case '清早':
                            case '早上':
                            case '早晨':{
                                $hms = "06:00";
                            };
                            case '白天':
                            case '今天':
                            case '上午':{
                                $hms = "08:00";
                            };
                            case '正午':
                            case '中午':{
                                $hms = "12:00";
                            };
                            case '下午':{
                                $hms = "16:00";
                            };
                            case '傍晚':{
                                $hms = "18:00";
                            };
                            case '夜间':
                            case '下晚':
                            case '晚上':{
                                $hms = "20:00";
                            };
                            case '凌晨':
                            case '半夜':{
                                $hms = "00:00";
                            }
                        }
                    }
                }
                /*处理事件,费用*/
                if($this->str[$i]['wtype'] == 'v'){
                    /*获取金额*/
                    /*支出*/
                    if(in_array($this->str[$i]['word'], $this->consumeWords)){
                        /*行为判断*/
                        switch ($this->str[$i]['word']){
                            case '给':
                            case '还':
                            case '借':{
                                //给我 ，给了我
                                if($this->str[$i+1]['word']=='我'||$this->str[$i+2]['word']=='我'){
                                    $type = "收";
                                }
                            };
                            //其他理应为支出
                        }
                        /*金额判断*/
                        //句式1：动词+以数量词+金钱词判断
                        if($this->str[$i+1]['wtype'] == 'm'){   
                            if(in_array($this->str[$i+2]['word'], $this->moneyWordsZ))  //整元
                                $num = $this->Han2Num($this->str[$i+1]['word']);
                            elseif (in_array($this->str[$i+2]['word'], $this->moneyWordsS))//角，毛
                                $num = $this->Han2Num($this->str[$i+1]['word'])/10;
                        }
                        //句式2：以动词+‘了’+数量词+金钱词判断
                        elseif ($this->str[$i+1]['word'] == '了' && $this->str[$i+2]['wtype'] == 'm'){
                            if(in_array($this->str[$i+3]['word'], $this->moneyWordsZ))  //整元
                                $num = $this->Han2Num($this->str[$i+2]['word']);
                            elseif (in_array($this->str[$i+3]['word'], $this->moneyWordsS))//角，毛
                                $num = $this->Han2Num($this->str[$i+2]['word'])/10;
                        }
                    }
                    /*收入*/
                    else if(in_array($this->str[$i]['word'], $this->incomeWords)){
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
                        //句式1：动词+以数量词+金钱词判断
                        if($this->str[$i+1]['wtype'] == 'm'){
                            if(in_array($this->str[$i+2]['word'], $this->moneyWordsZ))  //整元
                                $num = $this->Han2Num($this->str[$i+1]['word']);
                                elseif (in_array($this->str[$i+2]['word'], $this->moneyWordsS))//角，毛
                                $num = $this->Han2Num($this->str[$i+1]['word'])/10;
                        }
                        //句式2：以动词+‘了’+数量词+金钱词判断
                        elseif ($this->str[$i+1]['word'] == '了' && $this->str[$i+2]['wtype'] == 'm'){
                            if(in_array($this->str[$i+3]['word'], $this->moneyWordsZ))  //整元
                                $num = $this->Han2Num($this->str[$i+2]['word']);
                                elseif (in_array($this->str[$i+3]['word'], $this->moneyWordsS))//角，毛
                                $num = $this->Han2Num($this->str[$i+2]['word'])/10;
                        }
                    }
                    /*事件详细*/
                    if(in_array($this->str[$i]['word'], $this->amountWords)){
                        /*
                         * wtype:  了  ul
                         *          份   qj
                         *          元   qd
                         *           ,  w
                         *    花123买/租了[------]
                         *    买/租[-----]花了123
                         *    时间v了[------],花了123
                         *    [借给-----]123
                         *    [xx还我]123    给我/借我
                         *    [丢了/不见了]123
                         *    [充值xx/缴纳xx/汇款给]123
                         * */
                        
                    }
                }
            }
            
            
            /*整理时间*/
            $time = $nowYear."-".$month."-".$day." ".$hms;
            /*整理费用*/
            if($type=="收")$type = "out";
            else $type = "in";
            /*返回数据整理*/
            $returnKeyWords['time'] = $time;
            $returnKeyWords['thing'] = $thing;
            $returnKeyWords['money'] = $money;
            $returnKeyWords['type'] = $type;
            
            return $returnKeyWords;
        }
    }
?>
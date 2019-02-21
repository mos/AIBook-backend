<?php 
Class Chart{
    private $image; // 定义图像
    private $title; // 定义标题
    private $ydata; // 定义Y轴数据
    private $xdata; // 定义X轴数据
    private $seriesName; // 定义每个系列数据的名称
    private $color; // 定义条形图颜色
    private $bgcolor; // 定义图片背景颜色
    private $width; // 定义图片的宽
    private $height; // 定义图片的长
    
    /*
     * 构造函数
     * String title 图片标题
     * Array xdata 索引数组，X轴数据
     * Array ydata 索引数组，数字数组,Y轴数据
     * Array series_name 索引数组，数据系列名称
     */
    function __construct($title,$xdata,$ydata,$seriesName) {
        $this->title = $title;
        $this->xdata = $xdata;
        $this->ydata = $ydata;
        $this->seriesName = $seriesName;
        $this->color = array('#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4');
    }
    
    /*
     * 公有方法，设置条形图的颜色
     * Array color 颜色数组,元素取值为'#058DC7'这种形式
     */
    function setBarColor($color){
        $this->color = $color;
    }
    /*
     * 绘制折线图
     */
    public function paintLineChart() {
        $ydataNum = $this->arrayNum($this->ydata); // 取得数据分组的个数
        $max = $this->arrayMax($this->ydata); // 取得所有呈现数据的最大值
        $max = ($max > 100)? $max : 100;
        $multi = $max/100; // 如果最大数据是大于100的则进行缩小处理
        $barHeightMulti = 2.2; // 条形高缩放的比例
        $lineWidth = 50;
        $chartLeft = (1+strlen($max))*12; // 设置图片左边的margin
        
        $lineY = 250; // 初始化条形图的Y的坐标
        // 设置图片的宽、高
        //$this->width = $lineWidth*count($this->xdata) + $chartLeft - $lineWidth/1.6;
        
        $margin = 10; // 小矩形描述右边margin
        $recWidth = 20; // 小矩形的宽
        $recHeight = 15; // 小矩形的高
        $space = 20; // 小矩形与条形图的间距
        $tmpWidth = 0;
        // 设置图片的宽、高
        $lineChartWidth =  $lineWidth*count($this->xdata) + $chartLeft - $lineWidth/1.6 ;
        // 两个系列数据以上的加上小矩形的宽
        if($ydataNum > 1) {
            $tmpWidth = $this->arrayLengthMax($this->seriesName)*10*4/3 + $space + $recWidth + + $margin;
        }
        $this->width = $lineChartWidth + $tmpWidth;
        
        $this->height = 300;
        $this->image = imagecreatetruecolor($this->width ,$this->height); // 准备画布
        $this->bgcolor = imagecolorallocate($this->image,255,255,255); // 图片的背景颜色
        
        // 设置条形图的颜色
        $color = array();
        foreach($this->color as $col) {
            $col = substr($col,1,strlen($col)-1);
            $red = hexdec(substr($col,0,2));
            $green = hexdec(substr($col,2,2));
            $blue = hexdec(substr($col,4,2));
            $color[] = imagecolorallocate($this->image ,$red, $green, $blue);
        }
        
        // 设置线段的颜色、字体的颜色、字体的路径
        $lineColor = imagecolorallocate($this->image ,0xcc,0xcc,0xcc);
        $fontColor = imagecolorallocate($this->image, 0x95,0x8f,0x8f);
        $fontPath = 'font/simsun.ttc';
        
        imagefill($this->image,0,0,$this->bgcolor); // 绘画背景
        
        // 绘画图的分短线与左右边线
        for($i = 0; $i < 6; $i++ ) {
            imageline($this->image,$chartLeft-10,$lineY-$barHeightMulti*$max/5/$multi*$i,$lineChartWidth,$lineY-$barHeightMulti*$max/5/$multi*$i,$lineColor);
            imagestring($this->image,4,5,$lineY-$barHeightMulti*$max/5/$multi*$i-8,floor($max/5*$i),$fontColor);
        }
        imageline($this->image,$chartLeft-10,30,$chartLeft-10,$lineY,$lineColor);
        imageline($this->image,$lineChartWidth-1,30,$lineChartWidth-1,$lineY,$lineColor);
        $style = array($lineColor,$lineColor,$lineColor,$lineColor,$lineColor,$this->bgcolor,$this->bgcolor,$this->bgcolor,$this->bgcolor,$this->bgcolor);
        imagesetstyle($this->image,$style);
        
        // 绘制折线图的分隔线（虚线）
        foreach($this->xdata as $key => $val) {
            $lineX = $chartLeft + 3 + $lineWidth*$key;
            imageline($this->image,$lineX,30,$lineX,$lineY,IMG_COLOR_STYLED);
        }
        
        // 绘画图的折线
        foreach($this->ydata as $key => $val) {
            if($ydataNum == 1) {
                // 一个系列数据时
                if($key == count($this->ydata) - 1 ) break;
                $lineX = $chartLeft + 3 + $lineWidth*$key;
                $lineY2 = $lineY-$barHeightMulti*($this->ydata[$key+1])/$multi;
                
                // 画折线
                if($key == count($this->ydata) - 2 ) {
                    imagefilledellipse($this->image,$lineX+$lineWidth,$lineY2,10,10,$color[0]);
                }
                imageline($this->image,$lineX,$lineY-$barHeightMulti*$val/$multi,$lineX+$lineWidth,$lineY2,$color[0]);
                imagefilledellipse($this->image,$lineX,$lineY-$barHeightMulti*$val/$multi,10,10,$color[0]);
            }elseif($ydataNum > 1) {
                // 多个系列的数据时
                foreach($val as $ckey => $cval) {
                    
                    if($ckey == count($val) - 1 ) break;
                    $lineX = $chartLeft + 3 + $lineWidth*$ckey;
                    $lineY2 = $lineY-$barHeightMulti*($val[$ckey+1])/$multi;
                    // 画折线
                    if($ckey == count($val) - 2 ) {
                        imagefilledellipse($this->image,$lineX+$lineWidth,$lineY2,10,10,$color[$key%count($this->color)]);
                    }
                    imageline($this->image,$lineX,$lineY-$barHeightMulti*$cval/$multi,$lineX+$lineWidth,$lineY2,$color[$key%count($this->color)]);
                    imagefilledellipse($this->image,$lineX,$lineY-$barHeightMulti*$cval/$multi,10,10,$color[$key%count($this->color)]);
                }
            }
            
        }
        
        // 绘画条形图的x坐标的值
        foreach($this->xdata as $key => $val) {
            $lineX = $chartLeft + $lineWidth*$key + $lineWidth/3 - 20;
            imagettftext($this->image,10,-65,$lineX,$lineY+15,$fontColor,$fontPath,$this->xdata[$key]);
        }
        
        // 两个系列数据以上时绘制小矩形及之后文字说明
        if($ydataNum > 1) {
            $x1 = $lineChartWidth + $space;
            $y1 = 20 ;
            foreach($this->seriesName as $key => $val) {
                imagefilledrectangle($this->image,$x1,$y1,$x1+$recWidth,$y1+$recHeight,$color[$key%count($this->color)]);
                imagettftext($this->image,10,0,$x1+$recWidth+5,$y1+$recHeight-2,$fontColor,$fontPath,$this->seriesName[$key]);
                $y1 += $recHeight + 10;
            }
        }
        
        // 绘画标题
        $titleStart = ($this->width - 5.5*strlen($this->title))/2;
        imagettftext($this->image,11,0,$titleStart,20,$fontColor,$fontPath,$this->title);
        
        // 输出图片
        header("Content-Type:image/png");
        imagepng ( $this->image );
    }
    
    
    /*
     * 私有方法，当数组为二元数组时，统计数组的长度
     * Array arr 要做统计的数组
     */
    private function arrayNum($arr) {
        $num = 0;
        if(is_array($arr)) {
            $num++;
            for($i = 0; $i < count($arr); $i++){
                if(is_array($arr[$i])) {
                    $num = count($arr);
                    break;
                }
            }
        }
        return $num;
    }
    
    /*
     * 私有方法，计算数组的深度
     * Array arr 数组
     */
    private function arrayDepth($arr) {
        $num = 0;
        if(is_array($arr)) {
            $num++;
            for($i = 0; $i < count($arr); $i++){
                if(is_array($arr[$i])) {
                    $num += $this->arrayDepth($arr[$i]);
                    break;
                }
            }
        }
        return $num;
    }
    
    /*
     * 私有方法，找到一组中的最大值
     * Array arr 数字数组
     */
    private function arrayMax($arr) {
        $depth = $this->arrayDepth($arr);
        $max = 0;
        if($depth == 1) {
            rsort($arr);
            $max = $arr[0];
        }elseif($depth > 1) {
            foreach($arr as $val) {
                if(is_array($val)) {
                    if($this->arrayMax($val) > $max) {
                        $max = $this->arrayMax($val);
                    }
                }else{
                    if($val > $max){
                        $max = $val;
                    }
                }
            }
        }
        return $max;
    }
    
    /*
     * 私有方法，求数组的平均值
     * Array arr 数字数组
     */
    function arrayAver($arr) {
        $aver = array();
        foreach($arr as $val) {
            if(is_array($val)) {
                $aver = array_merge($aver,$val);
            }else{
                $aver[] = $val;
            }
        }
        return array_sum($aver)/count($aver);
    }
    
    /*
     * 私有方法，求数组中元素长度最大的值
     * Array arr 字符串数组,必须是汉字
     */
    private function arrayLengthMax($arr) {
        $length = 0;
        foreach($arr as $val) {
            $length = strlen($val) > $length ? strlen($val) : $length;
        }
        return $length/3;
    }
    
    // 析构函数
    function __destruct(){
        imagedestroy($this->image);
    }
}


$xdata = array('测试一','测试二','测试三','测试四','测试五','测试六','测试七','测试八','测试九');
$ydata = array(array(29,30,45,54,65,45,76,23,54),array(89,60,90,23,35,45,56,23,56));
$color = array();
$seriesName = array("七月","八月");
$title = "测试数据";
$Img = new Chart($title,$xdata,$ydata,$seriesName);
$Img->paintLineChart();
 
 
 ?>
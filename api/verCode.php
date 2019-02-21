<?php 
    function createRandNumber ($len = 6){
        $max = 0;
        for($i=$len-1;$i>=0;$i--)$max+=pow(10,$i)*9; 
        $randnumber = rand(0,$max);
        $format = "%0".$len."d";
        $randnumber = sprintf("$format",$randnumber);
        return $randnumber;
    }
    function createRandChar ($len = 6){
        $randchar = "";
        $chars = "0123456789abcdefghijkl0123456789mnopqrstuvwxyz0123456789ABCDEFGHIJKL0123456789MNOPQRSTUVWXYZ";
        $max = strlen($chars)-1;
        for($i=0;$i<$len;$i++){
            $randchar .= $chars[rand(0,$max)];
        }
        return $randchar;
    }
    function createRandChar33 ($mode = "aaa000"){
        $randchar = "";
        $randnumber = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "0123456789";
        $max = strlen($chars)-1;
        for($i=0;$i<3;$i++){
            $randchar .= $chars[rand(0,$max)];
        }
        for($i=0;$i<3;$i++){
            $randnumber .= $numbers[rand(0,9)];
        }
        if($mode=="aaa000")return $randchar.$randnumber;
        elseif ($mode=="000aaa")return $randnumber.$randchar;
        else return "error";
    }
    function createVerCodeImg ($randstr){
        /*需要单独创建一个php页面使用该函数，***.php作为img的src引用图片使用*/
        
        //提前页面session存储随机码，此处不存
        $image_width=60;
        $image_height=24;
        $strlen = strlen($randstr);
        $image=imagecreate($image_width,$image_height);
        imagecolorallocate($image,255,255,255);
        for($i=0;$i<$strlen;$i++){
            $font=rand(3,5);
            $x=rand(1,$image_width/($strlen*2))+$image_width*$i/$strlen;
            $y=rand(1,$image_height/4);
            $color=imagecolorallocate($image,rand(0,100),rand(0,100),rand(0,100));
            imagestring($image,$font,$x,$y,$randstr[$i],$color);
        }
        //干扰斑点
        for($i = 0; $i < $image_width; $i++) {
            $color = imagecolorallocate($image, rand(0, 254), rand(0, 254), rand(0, 254));
            imagesetpixel($image, rand(0, $image_width), rand(0,$image_height), $color);
        }
        //干扰线
        for($i = 0; $i < 1; $i++) {
            $color = imagecolorallocate($image, rand(120, 250), rand(120, 250), rand(120, 250));
            imageline($image, rand(0, $image_width), rand(0, $image_height), rand(0, $image_width), rand(0, $image_height), $color);
        } 
        header("Content-type: image/png"); 
        imagepng($image);
        imagedestroy($image);
    }
?>
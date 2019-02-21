<?php
header("content-type:text/html;charset=utf-8");
if ((($_FILES["file"]["type"] == "audio/wav")
    || ($_FILES["file"]["type"] == "audio/mp3")
    || ($_FILES["file"]["type"] == "audio/x-m4a")
    ||($_FILES["file"]["type"] == "audio/ogg"))
    && ($_FILES["file"]["size"] < 1024000))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "出错啦: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    }
    $s_path = "AudioTmp/";
    $splitname=explode(".", $_FILES["file"]["name"]);
    $typename=end($splitname);
    $result=move_uploaded_file($_FILES["file"]["tmp_name"], $s_path."userVoice.".$typename);
    if($result)echo "move success";
    else echo "move fail";
}
$command='ffmpeg -y -i '.$s_path.'userVoice.'.$typename.'  -acodec pcm_s16le -f s16le -ac 1 -ar 16000 '.$s_path.'userVoice.'.$typename.'.pcm';
exec($command);
?>


<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
	<input type="file" name="file" />
	<input type="submit" value="上传">
</form>

<?php 
    require_once 'AipSpeech.php';
    require_once 'AipNlp.php';
    require_once 'splitWord.php';
    require_once 'analyze.class.php';
    
//     const APP_ID = '11251894';
//     const API_KEY = 'cRYt7g69k7mNOl9UhELdWsZX';
//     const SECRET_KEY = 'OWTU9jyMcoNYNmc91aYhr6MGF4ovRgeZ';
    const APP_ID = '11471297';
    const API_KEY = 'Ixuc1LCHG6aR0Ul8LDWIFFh2';
    const SECRET_KEY = 'ndvwmpw3rXCjzYNMDdtsnTKVGRAiOdea';
    
//    $split = new AipNlp(APP_ID, API_KEY, SECRET_KEY);   //百度分词
    $Transfer = new AipSpeech(APP_ID, API_KEY, SECRET_KEY);
    
    $result=$Transfer->asr(file_get_contents($s_path.'userVoice.'.$typename.'.pcm'), 'pcm', 16000,array('dev_pid' => '1536'));
    echo "语句：".$result["result"][0];
    
    $data=splitThis($result["result"][0]);
    
    $analyze = new analyze();
    $analyze->getStr($data);
    $result = $analyze->extractKeyWords();
    echo "<br>提取结果：<pre>";
    /* var_dump($result); */
    echo "时间：".$result['time']."<br>";
    echo "事件：".$result['thing']."<br>";
    echo "金额：".$result['money']."<br>";
    echo "类型：";
    if($result['type']=='out')echo "支出<br>";
    elseif ($result['type']=='in')echo "收入<br>";
    echo "</pre>";
    
    
    echo "<pre>";
//    print_r($split->lexer($result["result"][0]));
    
    print_r($data);
    echo "</pre>";
    


    
    
?>
<?php 
    /*保存常用配置，供其他文件调用*/
    
    //1、数据库配置
    define('SQL_HOST', "");    //数据库主机地址
    define('SQL_USER', "");    //数据库用户名
    define('SQL_PWD', "");     //数据库用户密码
    define('SQL_DATABASE', "");//数据库名称
    
    //2、注册结果url
    define('REG_RESULT', "http://xxxx.cn/page/reg-result.html");
    
    //3、重置密码url
    define('RESET_PWD_IRL', "http://xxxx.cn/page/resetPassword.html");
    
    //4、语音识别接口参数  百度 
    define('BD_APP_ID', '');       //APP_ID
    define('BD_API_KEY', '');      //APO_KEY
    define('BD_SECRET_KEY', '');   //SECRET_KEY
    
    //5、分词接口参数  阿里云市场 
    define('ALI_APP_CODE', "");    //云市场分词接口调用秘钥
    
    //6、短信接口参数  阿里云短信服务
    define('ALI_accessKeyId', "");       // AccessKeyId
    define('ALI_accessKeySecret', "");   // AccessKeySecret
    define('ALI_setSignName', "");       //短信签名
    define('ALI_setTemplateCode', "");   //短信模板id
    
    //7、学习配置
    define('ADMIN_PWD', 'qwe123');  //词库、学习 手动添加数据 验证的口令
    define('LEARN_FQ', 3);          //学习饱和度
    
    //8、本站网址
    define('BASIC_DOMAIN', 'http://aibook.api/'); //本站网址，词库学习跳转需要
?>
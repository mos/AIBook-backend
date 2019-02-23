# 一、项目简介
AiBook-backEnd是我2018年的大创项目。这是后台实现程序。本项目核心功能是结合语音识别和自然语言处理技术，实现快捷记账。

# 二、核心功能演示
![image](https://raw.githubusercontent.com/wiki/Patrick-Jun/AiBook-backEnd/index.png)

# 三、目录说明
![image](https://raw.githubusercontent.com/wiki/Patrick-Jun/AiBook-backEnd/menu.png)


# 四、使用准备

## 4.1 运行环境与第三方API
1. LAMP/LNMP
2. PHP 5.6
3. ffmpeg
4. 百度语音识别
5. 阿里云市场-分词 [https://market.aliyun.com/products/57124001/cmapi017959.html](https://market.aliyun.com/products/57124001/cmapi017959.html)
6. 阿里短信服务[仅用于用户手机号验证]

## 4.2 安装步骤

1. clone到本地（clone前请先安装git）
```
cd /d D:\projects #进入到你的web目录路径
git clone git@github.com:Patrick-Jun/AiBook-backEnd.git
```

2. copy  **config/config.dev.php**  为  **config/config.php** 

3. 自己创建一个数据库，并创建关系表。关系表创建SQL语句在config/mysql.txt中

4. **config/config.php**填入对应的值
``` php
//1、数据库配置
define('SQL_HOST', "localhost");  //数据库地址
define('SQL_USER', "");           //数据库用户名
define('SQL_PWD', "");            //密码
define('SQL_DATABASE', "");       //数据库名称
//2、注册结果url
define('REG_RESULT', "http://xxxx.cn/page/reg-result.html");
//3、重置密码url
define('RESET_PWD_IRL', "http://xxxx.cn/page/resetPassword.html");
//4、语音识别接口参数  百度语音识别
define('BD_APP_ID', '');              //APP_ID
define('BD_API_KEY', '');             //APO_KEY
define('BD_SECRET_KEY', '');          //SECRET_KEY
//5、分词接口参数  阿里云市场
define('ALI_APP_CODE', "");           //APP_CODE
//6、短信接口参数  阿里云短信服务
define('ALI_accessKeyId', "");        //AccessKeyId
define('ALI_accessKeySecret', "");    //AccessKeySecret
define('ALI_setSignName', "");        //短信签名
define('ALI_setTemplateCode', "");    //短信模板id
//7、学习配置
define('ADMIN_PWD', '');    //词库、学习 手动添加数据 验证的口令
define('LEARN_FQ', 3);      //学习饱和度
//8、本站网址
define('BASIC_DOMAIN', 'http://aibook.api/');
```
5. 初始化词库：浏览器运行insert_wordbase.php，例如：http://aibook.api/insert_wordbase.php

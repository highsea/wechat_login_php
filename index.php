<?php
/*if ($_SESSION['access_token'] != $_SESSION['access_token']){
    echo "不能刷新本页面"
};*/
session_start();
$_SESSION["access_token"] = "none";


?>

<!DOCTYPE html>
<html>
<head>
<title>微信 登陆</title>
<meta charset="utf-8">
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>

</head>
<body>
<h1>微信二维码登陆</h1>
<div id="login_container"></div>

<p><?=('http://idacker.com/weixinsdk/index.php')?></p>



<script type="text/javascript">
var obj = new WxLogin({

    id:"login_container", 
    appid: "wx70a2b1aa7fed4cc9", 
    scope: "snsapi_login", 
    redirect_uri:"<?=UrlEncode('http://idacker.com/weixinsdk/main.php')?>",
    state: "<?=md5('idacker');?>",
    style: "black",
    href: ""//自定义样式css链接
});                  
</script>
</body>
</html>
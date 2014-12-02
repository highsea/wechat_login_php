<?php
session_start();
$code = isset($_GET['code']) ? $_GET['code'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';


$_SESSION["code"] = $code;
$_SESSION["state"] = $state;

$appid = '000000';
$secret = '000000';

if ($code!=''&&$state===md5('idacker')) {
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
    //echo $url;
}else{

    header("Location: http://".$_SERVER['HTTP_HOST']."/weixinsdk/index.php"); 
    //确保重定向后，后续代码不会被执行 

    die();
}

//发送请求
function curlAPI($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //解决报错 SSL certificate problem: unable to get local issuer certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    $result = json_decode(curl_exec($ch), true);
    if ($result === FALSE) {
        return "cURL Error: " . curl_error($ch);
        curl_close($ch);
        die();
    }
    curl_close($ch);
    return $result;
}

//尝试加密
function getAwardCheckCode() {
    $time = time();
    $key = "12345";
    $secret = md5($time.$key);
    return "&time=".$time."&secret=".$secret;
}

function get_password( $length = 8 ){
    $str = substr(md5(time()), 0, 6);
    return $str;
}
/**/
//获取 access_token
$outputAPI = curlAPI($url);
if (isset($outputAPI["access_token"])) {
    //存入session
    $_SESSION["access_token"] = $outputAPI['access_token'];
    $_SESSION["refresh_token"] = $outputAPI['refresh_token'];
    $_SESSION["scope"] = $outputAPI['scope'];
    $_SESSION["openid"] = $outputAPI['openid'];
    
} else{
    //调试
/*    echo "<pre>";
    var_dump($outputAPI);
    echo "</pre>";*/
}

/**/
//获取 页面刷新后 refresh_token

$refresh_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$_SESSION["refresh_token"];
$refresh_API = curlAPI($refresh_url);

if (isset($refresh_API["access_token"])) {
    //更新session token
    $_SESSION["access_token"] = $refresh_API['access_token'];
    $_SESSION["refresh_token"] = $refresh_API['refresh_token'];
    $_SESSION["scope"] = $refresh_API['scope'];
    $_SESSION["openid"] = $refresh_API['openid'];

}else{
    echo "<pre>";
    var_dump($refresh_API);
    echo "</pre>";
}


?>

<html>
<head>
<title>已登录后操作</title>
<meta charset="utf-8">
</head>
<body>
<p><?='_SESSION:'.$_SESSION["access_token"];?></p>
<p> <a href="<?=$url?>" target="_blank"><?=$url?></a> </p>

<p> <a href="<?=$refresh_url?>" target="_blank"><?=$refresh_url?></a> </p>
<p>access_token: <?=$_SESSION["access_token"]?> </p>
<p>refresh_token: <?=$_SESSION["refresh_token"]?> </p>
<p>scope: <?=$_SESSION["scope"]?> </p>
<p>openid: <?=$_SESSION["openid"]?> </p>

</body>
</html>




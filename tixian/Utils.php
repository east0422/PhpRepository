<?php

header('Content-Type:text/html;charset=utf-8');

/**
*
* 工具类 
**/
class Utils
{
    private $appid;
    private $appsecret;
    private $txstation;
    // 工具类构造函数
    // 依据不同的$txstation赋不同值
    public function __construct($_txstation)
    {
        $this->txstation = $_txstation;
        if ($this->txstation == 1) {
            $this->appid = 'wx5e0d1498e911111';
            $this->appsecret = '515217c2604211111';
        } elseif ($this->txstation == 2) {
            $this->appid = 'wxd71714619d22222';
            $this->appsecret = '52e66581e36c58d22222';
        } elseif ($this->txstation == 3) {
            $this->appid = 'wxf600dfadf833333';
            $this->appsecret = '408e5ebfcf1ce2b321e33333';
        }
    }

    public function gheader($url)  
    {  
        echo '<html><head><meta http-equiv="Content-Language" content="zh-CN"><meta HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=utf-8"><meta http-equiv="refresh"  
        content="0;url='.$url.'"><title>loading ... </title></head><body>
        <script>window.location="'.$url.'";</script></body></html>';  
        exit();  
    }  

    // 通过跳转获取用户的openid
    public function GetOpenid(){
        // TODO debug先获取code然后再获取openid
        // $code='0616XeGk037KSq1SOHHk0K97Gk06XeGC';
        // $openid = $this->getOpenidFromMp($code);
        // header('Location: http://www.baidu.com/?openid='.$openid);
        // exit();

        // 通过code获得openid
        if (!isset($_GET['code'])){
            // 触发微信返回code码
            // TODO debug公众号配置回调地址,依据跳转地址获取code再获取openid  
            // $baseUrl = urlencode('http://xxx');
            $parms = '?action=bindopenid&txstation='.$this->txstation;
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].$parms);
            // $baseUrl = urlencode('http://localhost/tixian/api.php?txstation='.$parms);
            $url = $this->_CreateOauthUrlForCode($baseUrl);
            $this->gheader($url);
        } else {
            // 获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }
      
    // 通过code从工作平台获取openid机器access_token
    public function GetOpenidFromMp($code){
        $url = $this->__CreateOauthUrlForOpenid($code);

        // 初始化curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // 运行curl，结果以json形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        // 取出openid
        $data = json_decode($res, true);
        $openid = $data['openid'];
        return $openid;
    }
      
    // 拼接签名字符串
    private function ToUrlParams($urlObj){
        $buff = '';
        foreach ($urlObj as $k => $v){
            if($k != 'sign'){
                $buff .= $k . '=' . $v . '&';
            }
        }
        
        $buff = trim($buff, '&');
        return $buff;
    }
      
    // 构造获取code的url连接($redirectUrl微信服务器回跳的url，需要url编码)
    public function _CreateOauthUrlForCode($redirectUrl){
        $urlObj['appid'] = $this->appid;
        $urlObj['redirect_uri'] = $redirectUrl;
        $urlObj['response_type'] = 'code';
        $urlObj['scope'] = 'snsapi_base';
        $urlObj['state'] = 'STATE'.'#wechat_redirect';
        $bizString = $this->ToUrlParams($urlObj);
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?'.$bizString;
    }
      
    // 构造获取open和access_toke的url地址
    private function __CreateOauthUrlForOpenid($code){
        $urlObj['appid'] = $this->appid;
        $urlObj['secret'] = $this->appsecret;
        $urlObj['code'] = $code;
        $urlObj['grant_type'] = 'authorization_code';
        $bizString = $this->ToUrlParams($urlObj);
        return 'https://api.weixin.qq.com/sns/oauth2/access_token?'.$bizString;
    }
}
<?php
 /**
 * 基类，常用方法
 */
class Conf
{
    public $MCHAPPID; // 商户账号appid(申请商户号的appid或商户号绑定的appid)
    public $MCHID; // 商户号(微信支付分配的商户号)
    private $KEY; // 商户支付秘钥(微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置)
    private $APPCERTPATH; // apiclient_cert.pem路径
    private $APPKEYPATH; // apiclient_key.pem路径
    // 连接数据库构造函数
    // 依据不同的$txstation赋不同值
    public function __construct($txstation)
    {
        if ($txstation == 1) {
            $this->MCHAPPID = 'wx5e0d1498e11111';
            $this->MCHID = '1508211111';
            $this->KEY = 'aRHKL7SRdwGdIAEIhq7jZL11111';
            $this->APPCERTPATH = './cert/apiclient_cert1.pem'; 
            $this->APPKEYPATH = './cert/apiclient_key1.pem';
        } elseif ($txstation == 2) {
            $this->MCHAPPID = 'wxd7171461922222';
            $this->MCHID = '1528222222';
            $this->KEY = 'sdfasdf23r23dsf2345hr22222';
            $this->APPCERTPATH = './cert/apiclient_cert2.pem'; 
            $this->APPKEYPATH = './cert/apiclient_key2.pem';
        } else if ($txstation == 3) {
            $this->MCHAPPID = 'wxf600dfadf833333';
            $this->MCHID = '1526733333';
            $this->KEY = 'lkjasdfalkjwejrfawfs33333';
            $this->APPCERTPATH = './cert/apiclient_cert3.pem'; 
            $this->APPKEYPATH = './cert/apiclient_key3.pem';
        }
    }

    /**
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

	/**  
	* 获取签名 
	* @param array $arr
	* @return string
	*/  
    public function getSign($arr) {
        // 去除空值
        $arr = array_filter($arr);
        // 按照键名字典排序
        ksort($arr);
        // 生成url格式的字符串
       $str = $this->arrToUrl($arr).'&key='.$this->KEY;
       return strtoupper(md5($str));
    }

    /**  
	* 获取带签名的数组 
	* @param array $arr
	* @return array
	*/  
    public function makeSign($arr) {
        $arr['sign'] = $this->getSign($arr);
        return $arr;
    }

	/**  
	* 数组转URL格式的字符串
	* @param array $arr
	* @return string
	*/
    public function arrToUrl($arr) {
        return urldecode(http_build_query($arr));
    }
    
    /**  
    * 数组转xml
    * @param array $arr
    * @return xml
    */
    function arrToXml($arr) {
        if(!is_array($arr) || count($arr) == 0) {
            return '数组数据异常！';
        }

        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            } else {
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }
	
    /**  
    * xml转数组
    * @param xml
    * @return array
    */
    function xmlToArr($xml) {	
        if(!$xml || $xml == '') {
            return 'xml数据异常！';
        }
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
        return $arr;
    }

    /**  
    * 以post方式提交xml到对应的接口url
    * @param url xml
    * @return
    */
    function postXmlCurl($url, $xml) {
        $ch = curl_init();

        $params[CURLOPT_URL] = $url;    // 请求url地址
        $params[CURLOPT_HEADER] = false; // 是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; // 要求结果为字符串且输出到屏幕上
        $params[CURLOPT_FOLLOWLOCATION] = true; // 是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $xml;
        $params[CURLOPT_SSL_VERIFYPEER] = true;
        $params[CURLOPT_SSL_VERIFYHOST] = 2;
        //以下是证书相关代码
        $params[CURLOPT_SSLCERTTYPE] = 'PEM';
        $params[CURLOPT_SSLCERT] = $this->APPCERTPATH;
        $params[CURLOPT_SSLKEYTYPE] = 'PEM';
        $params[CURLOPT_SSLKEY] = $this->APPKEYPATH;

        curl_setopt_array($ch, $params); // 传入curl参数
        $content = curl_exec($ch); // 执行
        if ($content) {
            curl_close($ch); // 关闭连接
            return $content;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return 'curl出错，错误码：' . $error;
        }
    }
}
<?php

/*
*	微信接口
*
*/
//namespace Lib;

/*
class jsskd{
	
/*
*获取微信JS-SDK签名算法
*@param  = NULL
*
	function JSSDK_signature(){
	
		//$nonceStr = substr(md5(time()), 0, 16);
	
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$nonceStr = "";
		for ($i = 0; $i < 16; $i++) {
			$nonceStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
	
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$jsapiTicket = jsapi_ticket();
		$timestamp = time();
		//$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		
		$signature = sha1($string);
		
		$signPackage = array(
			"appId"     => C('wx_appid'),
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage; 
	}
}
*/

class Pay{
	Static $appid;
	Static $mch_id;
	Static $key;
	Static $openid;
	Static $fee_type = 'CNY';
	
	public function __construct(){
		self::$appid =  C('wx_appid');
		self::$mch_id =  C('wx_mch_id');
		self::$key =  C('wx_pay_key');
		$uid = I('cookie.uid');
		$user = M('wechat_user') -> find($uid);
		self::$openid = $user['openid'];
	}
	
	function msg($uid,$msg){
		$db = M('wechat_user');
		$user = $db -> find($uid);
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".access_token();
		
		$post = array(
			"touser" => $user['openid'],
			'msgtype' => 'news',
			'news' => $msg,
		);
		
		$post = json_encode2($post);
		//dump($post);
		$r = curl($url,$post);
		$callback = json_decode($r,true);
		return $callback;
	}
	
	/*
		统一下单表
		@$total_fee int 金额
		@$body string 描述
	*/
	function tongyi($total_fee,$out_trade_no,$body="微信交易"){
		$post = array(
			'appid' => self::$appid,
			'mch_id' => self::$mch_id,
			'nonce_str' => $this -> nonceStr(30),
			'body' => $body,
			//'product_id' => $aid,
			'total_fee' => $total_fee,
			'out_trade_no' => $out_trade_no,
			'spbill_create_ip' => get_client_ip(),
			
			'trade_type' => 'JSAPI',
			'openid' => self::$openid,
		);
		$order = $post['out_trade_no'];
		$post['notify_url'] = "http://".$_SERVER['SERVER_NAME'].U('Wechat/Notify/index');
		$post['sign'] =  $this -> sign($post);
		//dump($post);
		
		
		
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		$xml = $this -> ToXml($post);
		$r = $this->FromXml(curl($url,$xml));
		 $post['prepay_id'] = $r['prepay_id'];
		//dump($r);
		return $post;
		
	}
	
	function sign($arr){
		ksort($arr);
		
		foreach($arr as $key => $val){	//去掉为空
			if(empty($val)){
				unset($arr[$key]);
			}else{
				$arr2[] = $key."=".$val;
			}
		}
		
		$arr['stringA'] = implode('&',$arr2);
		$arr2['key'] = "key=".self::$key;
		$arr['stringSignTemp'] = MD5(implode('&',$arr2));
		$sign= strtoupper($arr['stringSignTemp']);
		//$stringSignTemp = implode('&',$arr2)."&key"se
		//dump($arr);
		return  $sign;
	}
	
	/*
	*	随机
	*/
	function nonceStr($num){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$nonceStr = "";
		for ($i = 0; $i < $num; $i++) {
			$nonceStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $nonceStr;
	}
	
	/**
	 * 输出xml字符
	 * @throws WxPayException
	**/
	public function ToXml($arr)
	{
		
		if(!is_array($arr) 
			|| count($arr) <= 0)
		{
    		//throw new WxPayException("数组数据异常！");
			return "数组数据异常！";
    	}
    	
    	$xml = "<xml>";
    	foreach ($arr as $key=>$val)
    	{
			
    		if (is_numeric($val)){
    			$xml.="<".$key.">".$val."</".$key.">";
    		}else{
    			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		}
        }
        $xml.="</xml>";
		
        return $xml; 
	}
	
	
	 /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
	public function FromXml($xml)
	{	
		if(!$xml){
			//throw new WxPayException("xml数据异常！");
			return "xml数据异常！";
		}
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $this->values;
	}
	
	/**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
	{
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		//如果有配置代理这里就设置代理
		if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
			&& WxPayConfig::CURL_PROXY_PORT != 0){
			curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
			curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
		}
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
		if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
		}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			throw new WxPayException("curl出错，错误码:$error");
		}
	}
	
	
}

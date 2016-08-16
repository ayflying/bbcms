<?php
/*
//	助手函数
//
*/


/*
* curl函数
* @param str $url
* @param array $post
*/
function curl($url,$post=NULL){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if(isset($post)){	// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	
	$output = curl_exec($ch);
	curl_close($ch);
	
	return $output;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/*
// $string： 明文 或 密文  
// $operation：DECODE表示解密,其它表示加密  
*/
function cookie_decode($name){
	$cookie =  new app\common\library\CookieCode();
	//$cookie =  new luoe\library\CookieCode();
	$string = cookie($name);
	$string = $cookie -> discuz($string,'DECODE',config('database.password'));
	return $string;
}

function cookie_encode($name,$string,$time=null){
	//import('common.lib.CookieCode');
	$cookie =  new app\common\library\CookieCode();
	$string = $cookie -> discuz($string,'ENCODE',config('database.password'));
	cookie($name,$string);
	return $string;
}

<?php
/**
 *   运行时间开始
 */
\think\Debug::remark('begin');

/**
 * curl函数
 * @param str $url
 * @param array $post
 */
function curl($url,$post=NULL,$time=30,$type=null){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT,$time);
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
        if(isset($type)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/*'] );
        }
	}
	$output = curl_exec($ch);
	curl_close($ch);
	
	return $output;
}

/**
 * json升级版，中文不转义
 * @param array $arr
 */
function json_encode2($arr) {
	$parts = array ();
	$is_list = false;
	//Find out if the given array is a numerical array
	$keys = array_keys ( $arr );
	$max_length = count ( $arr ) - 1;
	if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
		$is_list = true;
		for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
			if ($i != $keys [$i]) { //A key fails at position check.
				$is_list = false; //It is an associative array.
				break;
			}
		}
	}
	foreach ( $arr as $key => $value ) {
		if (is_array ( $value )) { //Custom handling for arrays
			if ($is_list)
				$parts [] = json_encode2 ( $value ); /* :RECURSION: */
			else
				$parts [] = '"' . $key . '":' . json_encode2( $value ); /* :RECURSION: */
		} else {
			$str = '';
			if (! $is_list)
				$str = '"' . $key . '":';
			//Custom handling for multiple data types
			if (!is_string ( $value ) && is_numeric ( $value ) && $value<2000000000)
				$str .= $value; //Numbers
			elseif ($value === false)
				$str .= 'false'; //The booleans
			elseif ($value === true)
				$str .= 'true';
			else
				$str .= '"' . addslashes ( $value ) . '"'; //All other things
			// :TODO: Is there any more datatype we should be in the lookout for? (Object?)
			$parts [] = $str;
		}
	}
	$json = implode ( ',', $parts );
	if ($is_list)
		return '[' . $json . ']'; //Return numerical JSON
	return '{' . $json . '}'; //Return associative JSON
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
	$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    \Think\Hook::listen($hook,$params);
}

/**
 * 用户cooke解密与加密
 * @param string $name      需要加密的明文
 * @return string
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

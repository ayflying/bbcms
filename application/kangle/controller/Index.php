<?php
namespace app\kangle\controller;

class Index
{
	public function index()
	{
		
		
		$kangle = config('kangle');
		//dump($kangle);
		
		$kangle = $kangle[1];
		$ip = $kangle['ip'];
		$url = "http://".$ip.":3312/api/index.php";
		
		$r = rand(0,9999);
		$a = 
		//echo $url = $url."?json=1&c=whm&a=info&r=888&s=8e4fc6f181bedf11c64bac3bf341ca6a";
		$post = [
			'json' => 1,
			'c' => 'whm',
			'a' => 'getVh',
			'r' => $r,
			
		];
		$post['s'] = md5($post['a'].$kangle['key'].$post['r']);
		$post['name'] = "luoe_weidouke";
		$put = curl($url,$post);
		$arr = json_decode($put,true);
		
		dump($arr);
		
	}
	
	public function lists(){
		echo config('url');
	}
}

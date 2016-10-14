<?php
namespace app\wechat\controller;

use app\common\controller\Common;
use app\wechat\Util\Wechat;

class Index extends Common{
	public function index(){
	
	
	
	}
	
	public function menu(){
		$config = config('wechat');
		$options = array(
			'token'=>$config['token'], //填写你设定的key
			//'encodingaeskey'=> $config['token'], //填写加密用的EncodingAESKey，如接口为明文模式可忽略
			'appid'=>$config['appid'], //填写高级调用功能的app id
			'appsecret'=> $config['appsecret'], //填写高级调用功能的密钥
		);
        $wechat = new Wechat($options);
        
        $url = "http://".$_SERVER["SERVER_NAME"].url('Wechat/Api2/auth');
        
		$data = [
			'button' => [
				[
					'type' => 'view',
					'name' => '首页',
					//'url' => 'http://luoe_hongqiao.host.myolnet.com/',
                    //'url'=>$open_url.url('/'),
                    'url' => $wechat -> getOauthRedirect($url,url('/'),'snsapi_base'),
                    
				],
				[
					'type' => 'view',
					'name' => '购物车',
					'url' => $wechat -> getOauthRedirect($url,url('wechat/cart/index'),'snsapi_base'),
                    //'url' => 'http://luoe_hongqiao.host.myolnet.com/wechat-cart-index.html',
				],
				[
					'type' => 'view',
					'name' => '用户中心',
					//'url' => 'http://luoe_hongqiao.host.myolnet.com/member.html',
                    'url' => $wechat -> getOauthRedirect($url,url('member/index/index'),'snsapi_base'),
				],
			],
		];
		
		
		
		//$data = $wechat -> createMenu($data);
		dump($data);
		
	}
}

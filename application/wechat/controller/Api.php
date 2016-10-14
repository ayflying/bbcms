<?php
namespace app\wechat\controller;
use think\Cache;

class Api
{
	
	 /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = '')
    {
        import('@.Util.Wechat');
        $token = input('get.Token') ? input('get.Token') : C('token');
        //微信后台填写的TOKEN
        /* 加载微信SDK */
        $wechat = new \Wechat($token);
        /* 获取请求信息 */
        $data = $wechat->request();
        $data['openid'] = $data['FromUserName'];
        
		/*数据库检测*/
		//S('data', $data);
		$db = db('WechatUser');
		$where['openid'] = $data['FromUserName'];
		if(!$db ->where($where)-> find()){
			$save = array(
				'openid' => $data['FromUserName'],
				'subscribe' => 1,
				'create_time' => $data['CreateTime'],
				//'update_time' => $data['CreateTime'],
			);
			$uid = db('member_user') -> add($save);
			$save['uid'] = $uid;
			db('member_user_profile') -> add($save);
			$db -> add($save);
		}else{
			$save = array(
				'update_time' => $data['CreateTime'],
			);
			$db -> where($where) -> save($save);
		}
		/**/
		
		if ($data && is_array($data)) {
            //S('weixin', $wechat);
            //S('data', $data);
            /**
             * 你可以在这里分析数据，决定要返回给用户什么样的信息
             * 接受到的信息类型有9种，分别使用下面九个常量标识
             * Wechat::MSG_TYPE_TEXT       //文本消息
             * Wechat::MSG_TYPE_IMAGE      //图片消息
             * Wechat::MSG_TYPE_VOICE      //音频消息
             * Wechat::MSG_TYPE_VIDEO      //视频消息
             * Wechat::MSG_TYPE_MUSIC      //音乐消息
             * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
             * Wechat::MSG_TYPE_LOCATION   //位置消息
             * Wechat::MSG_TYPE_LINK       //连接消息
             * Wechat::MSG_TYPE_EVENT      //事件消息
             *
             * 事件消息又分为下面五种
             * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
             * Wechat::MSG_EVENT_SCAN               //二维码扫描
             * Wechat::MSG_EVENT_LOCATION           //报告位置
             * Wechat::MSG_EVENT_CLICK              //菜单点击
             * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
             */
            //根据来源信息的类型使用不同的方法路径进行处理
            switch ($data['MsgType']) {
                case \Wechat::MSG_TYPE_TEXT:
                    //文本
                    $arr = $this->text($data);
                    break;
                case \Wechat::MSG_TYPE_IMAGE:
                    //图片
                    $arr = image($data);
                    break;
                case \Wechat::MSG_TYPE_EVENT:
                    //事件
                    $arr = $this->event($data);
                    break;
                default:
                    $content = '该功能暂未开放';
                    //回复内容，回复不同类型消息，内容的格式有所不同
                    $type = 'text';
            }
            /////////////////////////////////////////////////////////////////////
            $content = $arr['content'] ? $arr['content'] : $content;
            $type = $arr['type'] ? $arr['type'] : $type;
            /* 响应当前请求(自动回复) */
            //S('content', $content);
            $wechat->response($content, $type);
        }
    }
    /////////////////////////////////
    //分支功能
    protected function text($data)
    {
        switch ($data['Content']) {
            case '云梦':
                $arr['content'] = '恭喜您获得由【ofo骑游】提供的10元租车优惠券一张，您可凭此信息在快车道自行车行享受优惠租车一次，祝您骑行愉快！';
                //回复内容，回复不同类型消息，内容的格式有所不同
                $arr['type'] = 'text';
                //回复消息的类型
				break;
			case '更新':
				$db = D('WechatUser');
				$sql = $db -> where("openid = '$data[openid]'") -> find();
				$uid = $sql['uid'];
				$this->updata($uid);
				$arr['content'] = '当前用户id'.$sql['uid'].$data[openid];
                //回复内容，回复不同类型消息，内容的格式有所不同
                $arr['type'] = 'text';
				break;
            default:
				$from = $data['Content'];
                if ($sql = db('WechatResponse')->where("`from` like '%$from%'")->find()) {
                    $arr['content'] = $sql['to'];
                    $arr['type'] = 'text';
                } else {
                    $arr['content'] = "你是说：".$data['Content']; //回复内容，回复不同类型消息，内容的格式有所不同
                    $arr['type']    = 'text'; //回复消息的类型
                    //$arr = null;
					//exit();
				}
                break;
        }
        return $arr;
    }
    protected function event($data)
    {
        switch ($data['Event']) {
            case \Wechat::MSG_EVENT_SUBSCRIBE:
                //关注
                $arr['content'] = '欢迎关注温州凯乐斯影城！';
                //回复内容，回复不同类型消息，内容的格式有所不同
                $arr['type'] = 'text';
                //回复消息的类型
				$db = D('WechatUser');
				$sql = $db -> where("openid like '$data[openid]'") -> find();
				$uid = $sql['uid'];
				$this->updata($uid);
				
				break;
            case unsubscribe:
                //取消关注
                $db = D('WechatUser');
                $db->subscribe = '0';
                $db->where("openid = '{$data['openid']}'")->save();
				exit();
                break;
            case \Wechat::MSG_EVENT_CLICK:
                //click事件
                $arr = $this->click($data);
                //$arr['content'] = "打开主页";
                //$arr['type']    = 'text'; //回复消息的类型
                break;
            case \Wechat::MSG_EVENT_LOCATION:
                //定位
                //$arr['content'] = "地理位置获取成功！";
                //$arr['type']    = 'text'; //回复消息的类型
                $db = D('WechatUser');
                $db->create();
                $db->subscribe = 1;
                $db->latitude = $data['Latitude'];
                $db->longitude = $data['Longitude'];
                $db->precision = $data['Precision'];
                $db->time = $data['CreateTime'];
                //$data['time'] = $data['CreateTime'];
                $db->where("openid = '{$data['openid']}'")->save();
				exit();
                break;
            default:
                //$arr['content'] = $data['Event']."事件暂未开放"; //回复内容，回复不同类型消息，内容的格式有所不同
                //$arr['type']    = 'text'; //回复消息的类型
                break;
        }
        return $arr;
    }
    protected function click($data)
    {
        switch ($data['EventKey']) {
            case about:
                $arr['content'] = '云梦微生活预留';
                $arr['type'] = 'text';
                //回复消息的类型
                break;
            case free:
                $db = db('WechatUser');
                $sql = $db->where("openid = '{$data['openid']}'")->find();
                S('sql', $sql);
                $url = 'http://' . $_SERVER['HTTP_HOST'] . U('Wechat/item/add?uid=' . $sql[uid]);
                $arr['content'] = '地址' . $url;
                $arr['content'] = "用户{$sql['uid']}:<a href=\"{$url}\">点击发布活动</a>";
                $arr['type'] = 'text';
                //回复消息的类型
                break;
            case look:
                $db = db('WechatUser');
                $sql = $db->where("openid = '{$data['openid']}'")->find();
                $url = 'http://' . $_SERVER['HTTP_HOST'] . U('Wechat/index/index?uid=' . $sql[uid]);
                $arr['content'] = '地址' . $url;
                $arr['content'] = "用户{$sql['uid']}:<a href=\"{$url}\">点击浏览活动</a>";
                $arr['type'] = 'text';
                //回复消息的类型
                break;
            default:
                $arr['content'] = '该功能暂未开放';
                //回复内容，回复不同类型消息，内容的格式有所不同
                $arr['type'] = 'text';
                //回复消息的类型
                break;
        }
        return $arr;
    }
    protected function image($data)
    {
        $arr['content'] = '你是说：' . $data['Content'];
        //回复内容，回复不同类型消息，内容的格式有所不同
        $arr['type'] = 'text';
        //回复消息的类型
        return $arr;
    }
    /////////////////////////////
    //自定义菜单功能
    public function menulist()
    {
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.access_token();
		$arr = json_decode(curl($url), true);
		//dump($arr);
        //echo '<br/>';
		//dump(json_encode($arr));
		return json_encode($arr);
    }
   public function menuadd(){
		if (input('key') != 'luoe123') {
			die('参数错误');
		}
		$open="https://open.weixin.qq.com/connect/oauth2/authorize";
		$auth_url = "http://".$_SERVER["SERVER_NAME"]."/Wechat-Api-auth?url=";
		$open_url = $open."?appid=".C('wx_appid')."&response_type=code&scope=snsapi_base&state=url&redirect_uri=".$auth_url;
		
		//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=https%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect
		
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.access_token();
		$jump = "http://".$_SERVER["SERVER_NAME"]."/Wechat-api-jump?url=";
		
		/*
		$post= array(
			"button" => array(
				array(
					"name" => "官方网站",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"网站首页",
							"url"=>$jump."/",
						),
						array(
							"type"=>"view",
							"name"=>"关于我们",
							"url"=>$jump."/aid/159"
						),				
					),
				),
				array(
					"name" => "科目考试",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"科一考试",
							"url"=>$jump."/Member/Kaoshi-index-type-C1.html"
						),array(
							"type"=>"view",
							"name"=>"科四考试",
							"url"=>$jump."/Member/Kaoshi-index-type-C4.html"
						),
						
					),
				),
				array(
					"name" => "个人中心",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"个人中心",
							"url"=>$jump."/Member"
						),
						array(
							"type"=>"click",
							"name"=>"绑定",
							"key"=>"bangding"
						),
					),
				),
			),
		);
		*/
		
		$post= array(
			"button" => array(
				array(
					"name" => "在线选票",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"在线团购",
							//"url"=>"http://theater.mtime.com/China_Zhejiang_Province_Wenzhou_OuHaiQu/2563/",
							"url" => $open_url.U('/tid/27'),
						),
						array(
							"type"=>"view",
							"name"=>"猫眼电影",
							"url" => "http://m.maoyan.com/#tmp=showtime&cinemaid=943",
						),
						array(
							"type"=>"view",
							"name"=>"百度糯米",
							"url" => "https://mdianying.baidu.com/cinema/detail?cinemaId=930&sfrom=wise_general_app&from=webapp&sub_channel=shoubai_launcher_icon&source=&c=178&cc=&kehuduan=#showing",
						),
						array(
							"type"=>"view",
							"name"=>"微票儿",
							"url" => "http://wx.wepiao.com/cinema_detail.html?cinema_id=1014487&city_id=100&startFrom=0",
						),
						array(
							"type"=>"view",
							"name"=>"时光网",
							"url" => "http://m.mtime.cn/#!/theater/1001/2563/date/20160412/",
						),
					),
				),
				array(
					'name' => "影院商城",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"影吧沙龙",
							"url"=>$open_url.U('/tid/29'),
						),
						array(
							"type"=>"view",
							"name"=>"卖品什锦",
							"url"=>$open_url.U('/tid/35'),
						),
						array(
							"type"=>"view",
							"name"=>"时尚套餐",
							"url"=>$open_url.U('/tid/31'),
						),
						array(
							"type"=>"view",
							"name"=>"活动快讯",
							"url"=>$open_url.U('/tid/28'),
						),
						array(
							"type"=>"view",
							"name"=>"会员天地",
							"url"=>$open_url.U('/Member/shop'),
						),
						
					)
				),
				array(
					'name' => "关于影城",
					"sub_button" => array(
						array(
							"type"=>"view",
							"name"=>"影城介绍",
							"url"=>$open_url.U('/aid/165'),
						),
						array(
							"type"=>"view",
							"name"=>"协作伙伴",
							"url"=>$open_url.U('/aid/166'),
						),
						array(
							"type"=>"view",
							"name"=>"服务建议",
							"url"=>$open_url.U('/aid/167'),
						),
						array(
							"type"=>"view",
							"name"=>"关于我们",
							"url"=>$open_url.U('/aid/164'),
						),
						
						/*
						array(
							"type"=>"view",
							"name"=>"关于我们",
							"url"=>"http://mp.weixin.qq.com/s?__biz=MzA3MzA4NDE5Nw==&mid=201679788&idx=1&sn=ba329b3a8a97fdd93836ff216029ff75&scene=23&srcid=0405cqvRriHVUS3YJzKD5DVN#rd",
						),
						*/
						
					)
				),
			)
		);
		
		
		
		$data = json_encode2($post);
		dump($post);
		dump($data);
		$aaa = curl($url, $data);
		//dump(curl($url));
		dump($aaa);
    }
	
	//////////////////////////////////
	
	
	//获取用户信息
	function updata($uid){
		$db = db('WechatUser');
		$sql = $db -> where("uid = '$uid'") -> find();
		$openid = $sql['openid'];
		//$openid = input('get.openid');
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".access_token()."&openid=".$openid."&lang=zh_CN";
		$user = json_decode(curl($url),true);
		
		//dump($user);
		$db -> where("uid = '$uid'") -> save($user);
		
		$user['uname'] = $user['nickname'];
		db('member_user') -> where("uid = '$uid'") -> save($user);
		
		//$this->success("获取微信资料成功！");
		//dump($user);
		return $user;
	}
	
    //////////////////////////////////
	public function jump(){
		$url = input('get.url');
		
		/*
		session(array('id'=>'wechat','expire'=>60));
		$view = session('view');
		*/
		
		$view = S('view');
		S('view',NULL);
		
		dump($view);
		$db = db('wechat_user');
		$where['openid'] = $view['openid'];
		$sql = $db -> where($where) -> find();
		if($view['openid'] || $sql){
			cookie('uid',$sql['uid']);
			header('Location: ' . $url);
		}else{
			exit("error！授权失败,请重试！");
		}
		
	}
	
    /// view获取openid
    public function auth(){
		//dump(input('get.'));
		$state = input('get.state');
		$code = input('get.code');
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . C('wx_appid') . '&secret=' . C('wx_appsecret') . '&code=' . $code . '&grant_type=authorization_code';
		$r = json_decode(curl($url), true);
		//dump($r);
		$openid = $r['openid'];
		$db = db('WechatUser');
		$sql = $db->where("openid = '$openid'")->find();
		cookie('uid', $sql['uid']);
		echo "欢迎用户【".$sql['nickname']."】,正在为您跳转！";
		//echo "uid：".input('cookie.uid');
		//$url = "http://".$_SERVER['HTTP_HOST'].U('Wechat/item/add?uid='.$sql[uid]);
		switch ($state) {
			case 'index':
				header('Location: ' . U('/'));
				break;
			case 'list':
				header('Location: ' . U('/Portal/tid-31'));
				break;
			case 'help':
				header('Location: ' . U('/Portal/tid-31'));
				break;
			case 'url':
				header('Location: ' . input('url'));
				break;
			default:
				header('Location: ' .$state);
				break;
		}
	}
	
	public function auth2(){
		//dump(input('get.'));
		$state = input('get.state');
		$code = input('get.code');
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.C('wx_appid').'&secret='.C('wx_appsecret').'&code='.$code.'&grant_type=authorization_code';
		$r = json_decode(curl($url), true);
		
		//dump($r);
		
		$openid = $r['openid'];
		
		if(empty($openid)){
			$this -> error("系统故障，刷新中");
		}
		
		$db = db('WechatUser');
		if($sql = $db->where("openid = '$openid'")->find()){
			$auth = A('Admin/Login');
			$auth -> authorization($sql['uid']);
				
			//cookie('uid', $sql['uid']);
			echo "欢迎用户【".$sql['nickname']."】,正在为您跳转！";
		}else{
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$r['access_token']."&openid=".$openid."&lang=zh_CN";
			$data =  json_decode(curl($url),true);
			
			$data['create_time'] = time();
			$data['update_time'] = time();
			$data['status'] = 1;
			$data['uname'] = $data['nickname'];
			$uid = db('member_user') -> add($data);
			
			$data['uid'] = $uid;
			$data['openid'] = $openid;
			$data['subscribe'] = 0;
			$data['time'] = time();
			$db->add($data);
				
			cookie('uid', $uid);
		}
		//$this -> user_info($uid);
		header('Location: ' .$state);
		
	}
    ///////////////////////////
    //客服消息
    public function send($openid=null, $text=null)
    {
		
		$openid = $openid ? $openid : input('post.openid');
		$text = $text ? $text : input('post.text');
		
        //$uid = input('get.openid');
        //$text = input('post.text');
        //$uid = 'ozj3Us5MtH-WdZJ8cvZWfYMuj4CQ';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . access_token();
        $post = array('touser' => $openid, 'msgtype' => 'text', 'text' => array('content' => $text));
        $data = json_encode2($post);
        $aaa = curl($url, $data);
        return $aaa;
    }
    /////////////////////////
    //用户功能
    public function userlist()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . access_token();
        $arr = curl($url);
        $arr2 = json_decode($arr, true);
        S('Wechat_user_list', $arr2[data][openid]);
        dump($arr2[data][openid]);
    }
    public function userxiufu(){
		exit();
        G('begin');
        $user_list = S('Wechat_user_list');
        $user = D('WechatUser');
        $yes = 0;
        $no = 0;
        foreach ($user_list as $val) {
            $data['openid'] = $val;
            //$user -> create($data);
            if ($user->where($data)->find()) {
                echo '<br />重复了' . $val;
                $yes++;
            } else {
                echo '<br />准备写入' . $val;
                $uid = $user->add($data);
				$this->updata($uid);
                $no++;
            }
        }
        echo '<br/>当前写入' . $no . '已存在' . $yes;
        G('end');
        echo '<br/>执行时间：' . G('begin', 'end') . 's';
    }
    /////////////////////////////////////////
    //短信
    protected function sms($tel, $content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://sms-api.luosimao.com/v1/status.json');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . C('mmskey'));
        $res = curl_exec($ch);
        curl_close($ch);
        //$res  = curl_error( $ch );
        dump($res);
    }
    
    /**
	 * 重载设置缓存
	 * @param string $cachename
	 * @param mixed $value
	 * @param int $expired
	 * @return boolean
	 */
	protected function setCache($cachename,$value,$expired){
		return Cache::set($cachename,$value,$expired);
	}

	/**
	 * 重载获取缓存
	 * @param string $cachename
	 * @return mixed
	 */
	protected function getCache($cachename){
		return Cache::get($cachename);
	}

	/**
	 * 重载清除缓存
	 * @param string $cachename
	 * @return boolean
	 */
	protected function removeCache($cachename){
		return Cache::set($cachename,null);
	}
    
    //////////////////////////////////
    //调试
    public function debug()
    {
        dump(S('weixin'));
        dump(S('data'));
		
        dump(S('uid'));
        dump(S('sql'));
        dump(S('code'));
		dump(S('user'));
    }
    public function debug2()
    {
        dump($sql1);
    }
	
}
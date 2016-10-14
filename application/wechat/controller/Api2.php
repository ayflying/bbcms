<?php
namespace app\wechat\controller;
use think\Db;
use think\Cache;
use think\Controller;
use app\wechat\Util\Wechat;


class Api2 {
    
    public $wechat; //微信类对象
    public $data;       //返回信息
    
    
    function __construct (){
        $config = config('wechat');
        $options = array(
            'token'=>$config['token'], //填写你设定的key
            //'encodingaeskey'=> $config['token'], //填写加密用的EncodingAESKey，如接口为明文模式可忽略
            'appid'=>$config['appid'], //填写高级调用功能的app id
            'appsecret'=> $config['appsecret'], //填写高级调用功能的密钥
        );
        
        $this->wechat = new Wechat($options);
        Cache::set('wechat',$this -> data,3600);
    }
    public function index(){
        
        //$token = input('get.Token');
        /* 加载微信SDK */
        
        
        $wechat = $this -> wechat;
        $wechat ->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $wechat->getRev()->getRevType();
        $this -> data = $wechat -> getRevData();    //返回微信服务器发来的信息（数组）
        //getRevData();
       
        Cache::set('type',$type,3600);
        $event = $wechat -> getRevEvent();
        Cache::set('event',$event,3600);
        
        $this -> creat_user(); //检测用户是否存在
        
        switch($type){
            case Wechat::MSGTYPE_TEXT:
                $arr = $this -> text();
                break;
            case Wechat::MSGTYPE_EVENT:
                $arr = $this -> event();
                $arr = array(
                    'type' => 'text',
                    'content' => Wechat::MSGTYPE_EVENT,
                );
                break;
            case Wechat::EVENT_SUBSCRIBE:
                $this -> info($data['FromUserName']);
                $arr['content'] = '欢迎关注';
                //回复内容，回复不同类型消息，内容的格式有所不同
                $arr['type'] = 'text';
            break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $arr = array(
                    'type' => 'text',
                    'content' => 'help info',
                );
        }
        
        /*
        //回复消息类型
        text($text) 设置文本型消息，参数：文本内容
        image($mediaid) 设置图片型消息，参数：图片的media_id
        voice($mediaid) 设置语音型消息，参数：语音的media_id
        video($mediaid='',$title,$description) 设置视频型消息，参数：视频的media_id、标题、摘要
        music($title,$desc,$musicurl,$hgmusicurl='',$thumbmediaid='') 设置回复音乐，参数：音乐标题、音乐描述、音乐链接、高音质链接、缩略图的媒体id
        news($newsData) 设置图文型消息，参数：数组。数组结构见php文件内方法说明
        */
        
        $wechat->$arr['type']($arr['content'])->reply();
    }
    
    //分支功能
    protected function text(){
        $data = $this -> data;
        switch ($data['Content']) {
            case '云梦':
                $arr['content'] = '恭喜您获得由【ofo骑游】提供的10元租车优惠券一张，';
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
    
    protected function event(){
        $data = $this -> data;
        $wechat = $this -> wechat;
        $event = $this->wechat -> getRevEvent();
        cache('event',$event,3600);
        switch ($event){
            
            case Wechat::EVENT_UNSUBSCRIBE:
                Db::name('wechat_user') -> where('openid',$data['FromUserName']) -> setField('subscribe',0);
            break;
        }
        
        return $arr;
    }
    
    /*检测用户是否存在*/
    protected function creat_user(){
        $data = $this -> data;
        if(!Db::name('wechat_user') -> where('openid',$data['FromUserName']) -> value('uid')){
            
            $info = $this -> wechat -> getUserInfo($data['FromUserName']);
            $save = [
                'create_time' => $data['CreateTime'],
                'status' => 1,
                'uname' => $info['nickname'],
                'headimgurl' => $info['headimgurl'],
            ];
            $uid = Db::name('member_user') -> insertGetId($save);
            Db::name('member_user_profile') -> insert(['uid'=>$uid]);
            $info['uid'] = $uid;
            Db::name('wechat_user') -> strict(false) -> insert($info);
            return $uid;
            
        }
    }
    
    /**
    *   用户授权登录
    *
    */
    public function auth()
    {
        
        $config = config('wechat');
        //echo $state = input('state');
		
        $code = input('code');
		$access_token = $this -> wechat -> getOauthAccessToken($code);
        dump($access_token);
        //$user = $this -> wechat -> getOauthUserinfo($access_token['access_token'],$access_token['openid']);
        //dump($user);
        
        /*
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $config('appid') . '&secret=' . $config('appsecret') . '&code=' . $code . '&grant_type=authorization_code';
		$r = json_decode(curl($url), true);
        dump($url);
        
        */
        //Db::name('wechat_user') ->where('openid',$)
        
    }
    
    /*更新用户信息*/
    protected function info($openid){
        $uid = Db::name('wechat_user') -> where('openid',$data['FromUserName']) -> value('uid');
        $info = $this -> wechat -> getUserInfo($data['FromUserName']);
        $save = [
            'update_time' => time(),
            'uname' => $info['nickname'],
            'headimgurl' => $info['headimgurl'],
        ];
         Db::name('member_user') -> where('uid',$uid) -> updata($save);
         Db::name('wechat_user') -> where('uid',$uid) -> strict(false) -> updata($info);
    }
    
    /*
     @param array $data 消息结构{"touser":"OPENID","msgtype":"news","news":{...}}
    */
    public function msg($data){
        $wechat = $this -> wechat;
        
        /*
        $data = [
            'msgtype' => 'news',
            'news' => [
                'articles' => [
                    [
                        'title' => "测试地址",
                        'description' => '没什么描述的内容',
                        'url' => 'http://www.luoe.cn/',
                        'picurl' => 'http://blog.7cuu.com/Uploads/litpic/Portal/572608e44ca68.jpg',
                    ],
                ],
            ],
        ];
        */
        
        /*
        $data = [
            'touser' => 'o0be4s595c7LRG7HRsBfq2zNbTMs',
            'msgtype' => 'text',
            'text' => [
                'content' => date('Y-m-d H:i:s').'不好拉',
            ],
        ];
        */
        
        //$data['touser'] = 'o0be4s595c7LRG7HRsBfq2zNbTMs';
        dump($data);
        
        return $wechat -> sendCustomMessage($data);
        
        
    }
    
    function debug()
    {
        $wechat = $this -> wechat;
        echo "wechat<br/>";
        $data = Cache::get('wechat');
        dump($data);
        echo "type<br/>";
        dump(Cache::get('type'));
        //$wechat = cache('wechat');
        //dump($wechat);
        //dump($wechat -> checkAuth());
        //dump($wechat -> getDatacube('interface','summary'));
        //dump($wechat -> getMenu());
        //dump($wechat -> getUserInfo($data['FromUserName']));

        //dump(Cache::get('event'));
        //$msg = $this -> msg(123);
        //dump($msg);
    }
    
}
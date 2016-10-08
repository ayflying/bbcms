<?php
namespace app\kangle\controller;
use app\common\controller\Common;

class Index extends Common
{
    public $skey;       //密钥
    public $id = 1;
    
    protected $beforeActionList = [
        'load',
    ];
    
    public function load(){
        $options = [
            'expire' => 0,
            'prefix'=>'kangle',
        ];
        cache($options);
        
    }
    
    public function index(){
        
        $config = config('kangle');
        
        $this -> _G['list'] = $config;
        $this -> assign('_G',$this -> _G);
        
        
        return $this -> fetch("./kangle/index");
    }
    
	public function list($id){
        //if(!cache('list',['prefix'=>'kangle'])){
        $config = config('kangle')[$id];
        //$host = 
        if(!cache($config['host'])){
            
            $this -> skey = $config['key'];
            $url = "http://".$config['ip']."/api/index.php";
            
            $post = [
                'json' => 1,
                'c' => 'whm',
                'a' => 'listVh',
            ];
            $post = array_merge ($post,$this -> keys($post['a']));
            $put = curl($url,$post);
            if(empty($put)){
                $this -> error("当前服务器异常");
            }
            $list = json_decode($put,true);
            $list = $list['rows'];
            $list = array_reverse($list);
            cache($config['host'],$list,3600);
        }
        $list = cache($config['host']);
        
        //dump($list);
        $this -> _G['host'] = $config['host'];
        $this -> _G['cname'] = $config['cname'];
        $this -> _G['list'] = $list;
        $this -> assign('_G',$this -> _G);
        
        return $this -> fetch2('./kangle/list');
	}
    
    /*主机添加*/
    public function add($id){
        $config = config('kangle')[$id];
        
        if(request()->isPost()){
            $post2 = input('post.');
            //dump($post);
            
            $this -> skey = $config['key'];
            $url = "http://".$config['ip']."/api/index.php";
            
            $post = [
                'json' => 1,
                'c' => 'whm',
                'a' => 'add_vh',
                'init' => 1,
            ];
            $post = array_merge ($post,$post2,$this -> keys($post['a']));
            $put = curl($url,$post);
            $list = json_decode($put,true);
            //dump($list);
            if($list['result'] == 200){
                cache($config['host'],null);
                $this -> success("创建成功",'list?id='.$id,null,1);
            }else{
                $this -> error("主机创建失败，请重试！");
            }
            
        }else{
            $type = $config['type'];
            $this -> _G['type'] = $type;
            $this -> assign('_G',$this -> _G);
            return $this -> fetch2('./kangle/add');
        }
    }
    
    public function ceshi($id = 3){
        $config = config('kangle')[$id];
        $this -> skey = $config['key'];
        $url = "http://".$config['ip']."/api/index.php";
        
        $post = [
            'json' => 1,
            'c' => 'whm',
            'a' => 'getVh',
            'name' => 'blog',
            //'init' => 1,
        ];
        $post = array_merge ($post,$this -> keys($post['a']));
        $put = curl($url,$post);
        $list = json_decode($put,true);
        dump($list);
        
    }
    
    //protected  function check($url){
    public function info($id){
        $config = config('kangle')[$id];
        
        $this -> skey = $config['key'];
		$url = "http://".$config['ip']."/api/index.php";
        $post = [
			'json' => 1,
			'c' => 'whm',
			'a' => 'info',
		];
        $post = array_merge ($post,$this -> keys($post['a']));
        
        
        $put = curl($url,$post);
        $list = json_decode($put,true);
        //dump($put);
        //if($put){
        $this -> success("检测成功",null,$list,30);
        
        
    }
    
    /*密钥获取*/
    protected  function keys($action, $rand=null){
        empty($rand) && $rand = rand(100000,999999);
        $arr['r'] = $rand;
        $arr['s'] =  md5($action.$this->skey.$rand);
        return $arr;
        
    }
    
    public  function validate_name($name,$id){
        $config = config('kangle')[$id];
        
        $list = cache($config['host']);
        foreach($list as $val){
            if($val['name'] == $name){
                return $this -> error("当前名称已存在",null,"1");
            }
        }
        return $this -> error("",null,"0");
        
    }
    
    public  function restart($host)
    {
        cache($host,null);
        //cache('list',null,['prefix'=>'kangle']);
        $this -> success("操作成功",null,null,1);
    }
}

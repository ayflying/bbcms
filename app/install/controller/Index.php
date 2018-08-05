<?php
namespace app\install\controller;
use \think\Controller;
use \think\Db;
use think\facade\Env;
use \app\member\model\MemberUser;

class Index extends Controller
{
    public $config_path;
    public $config;
    public $db;
    
    
    public function initialize()
    {
        if(file_exists('./public/install.lock')){
            $this -> error("请手动删除./public/install.lock文件再进行安装操作",'/',null,60);
        }
        $this -> config_path = Env::get('config_path').'database.php';
        
        $config = include($this -> config_path);
        $this -> config = $config;
        $this -> db = Db::connect($config);
    }
    
    
    public function index()
    {
        return $this -> fetch('./index');
    }
    
    public function step2()
    {
        $file = $this -> config_path;
        $config = include($file);
        $config_str = file_get_contents($file);
        //
        //dump($config_str);
        if(request()->isPost()){
            
            $post = input('post.');
            
            cookie('user',$post['user'],0);
            unset($post['user']);
            try{
                Db::connect($post) -> query('show tables');
            }catch( \Exception $e){
                $this -> error("无法连接到数据库,请检查数据库配置<br/> ".$e->getMessage(),null,null,10);
            }
            
            //dump($post);
            foreach($post as $key => $val){
                //$old = "'{$key}' => '{$config[$key]}'";
                $old = "/[\'|\"]{$key}[\'|\"](.*?)[\'|\"]{$config[$key]}[\'|\"]/i";
                $new = "'{$key}' => '{$val}'";
                $config_str = preg_replace($old, $new, $config_str, 1);
            }
            
            if(is_writable($file)){
                file_put_contents($file,$config_str);
                $config = array_merge($config,$post);
                $this-> success("（2/3）保存配置文件，开始安装基础表，安装过程中请勿关闭窗口",'step3');
            }else{
                $this -> error($file."文件无写入权限");
            }
        }else{
            $this -> assign('config',$config);
            return $this -> fetch('./step2');
        }
        
    }
    
    public function step3(){
        set_time_limit(3600);
        $path = Env::get('module_path').'sql/1_table.sql';
        $sql = file_get_contents($path);
         //去除多余空行
        $sql = preg_replace("/(\r\n|\n|\r|\t)/i", '', $sql);
        $sql_arr = $this -> format_sql($sql);
        foreach($sql_arr as $val){
            //dump($val);
            $this -> db -> execute($val);
        }
        $this -> success("(3/3)开始写入初始化数据",'step4');
    }
    
    public function step4(){
        set_time_limit(3600);
        $path = Env::get('module_path').'sql/2_sysyem.sql';
        $sql = file_get_contents($path);
        $sql_arr = $this -> format_sql($sql);
        
        $truncate  = [
            'system_settings',
            'member_group',
            'member_user_profile_setting',
            'member_user',
        ];
        foreach($truncate as $val){
            $sql =  "truncate table `".$this -> config['prefix'].$val."`;";
            $this -> db -> execute($sql);
        }
        
        
        foreach($sql_arr as $val){
            $this -> db -> execute($val);
        }
        //创建管理员帐号
        $data = cookie('user');
        $data['gid'] = 1;
        $user = new MemberUser;
        $user  -> save($data);
        $uid = $user->id;
        $this -> db -> name('member_user_profile') ->  insert(['uid' => $uid]);
        //清理缓存，写入安装锁
        cookie('user',null);
        file_put_contents('./public/install.lock','');
        $this -> success("程序安装完成，正在跳转到首页",'/');
    }
    
    
    public function format_sql($sql){
        $sql = str_replace("\r\n","",$sql);
        $sql = preg_replace('/--(.*?)---/', '', $sql);
        $sql = str_replace('bb_',$this -> config['prefix'],$sql);
        $sql_arr = explode(';',$sql);
        $sql_arr= array_filter($sql_arr);
        
        return $sql_arr;
        
    }
    
}

<?php
namespace app\admin\controller;
use think\facade\Config;
use think\Db;
use think\facade\Cache;

class System extends Common{
    public function index(){
		$mod = input('get.mod');
		$db = Db::name('SystemSettings');
		$file = "./config/bbcms.php";
        //$config = file_get_contents($file);
        $config = Config::pull('bbcms');
        
		if(request()->isPost()){
			$post = input('post.');
            
            $str = "<?php\nreturn ";
            $str .= var_export($config,true).";";
            //$str   = iconv("GB2312","UTF-8",$str);
            file_put_contents($file,$str);
			unset($post['config']);
			foreach($post as $k => $val){
				//$db -> value = $val;
				$db -> where("name", $k) -> setField('value',$val);
			}
            
            
            
			return  $this->success(lang('修改完成'),null,null,1);
			//$this -> cache();
		}else{
			$sql = $db -> where("class > '0'") -> select();
			foreach($sql as $k => $val){
				//dump($val);
				$sql[$k]['value'] = htmlspecialchars_decode($val['value']);
				$system[$val['name']] = $val;
			}
			
            
            //读取本地配置文件
            /*
            preg_match_all('/define\([\'|\"]?(.*?)[\'|\"]?,[\'|\"]?(.*?)[\'|\"]?\)/',$config,$arr);
            $config = [];
            foreach($arr[2] as $key => $val){
                $config[$arr[1][$key]] = $val;
            }
            */
            
            $this -> assign('sql',$sql);
            $this -> assign('config',$config);
			return $this->fetch('./system');
			
		}
    }
	
	
	public function theme($dir=NULL){
		$template = './template';
		$file=scandir($template);
		foreach($file as $val){
			if($val != '.' && $val != '..'){
				file_exists($template."/".$val."/config.php") and $list[$val] = include_once($template."/".$val."/config.php");
				$list[$val]['dir'] = $val;
				
			}
		}
		
		if(isset($dir)){
			//$dir = input('get.dir');
			Db::name('system_settings') -> where('name','theme') -> update(['value' => $dir]);
			//修改首页配置文件
            $file = "./config/bbcms.php";
			$config = file_get_contents($file);
			$config = str_replace(Config::get('bbcms.view_path'),'/template/'.$dir,$config);
			file_put_contents($file,$config);
            
            
            
            $file = "./config/template.php";
            $config = file_get_contents($file);
            $template = include($file);
            $config = str_replace($template['view_path'],'./template/'.$dir.'/',$config);
			file_put_contents($file,$config);
            
            
			return $this -> success(lang('切换模板').' ['.$dir.']');
			
		}else{
			//dump($list);
			$this -> assign('list',$list);
			return $this -> fetch('./system_theme');
		}
	}
	
	public function cache(){
        
		//deldir(CACHE_PATH);		//CACHE_PATH 项目模板缓存目录（默认为 RUNTIME_PATH.'cache/'）
		//$this -> deldir(TEMP_PATH);		//TEMP_PATH 应用缓存目录（默认为 RUNTIME_PATH.'temp/'）
		Cache::clear(); 
		return $this -> success(lang("更新缓存完成"),'index','',1);
	}
	
    
}
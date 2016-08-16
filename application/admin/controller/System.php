<?php
namespace app\admin\controller;
use think\Config;

class System extends Common{
    public function index(){
		$mod = input('get.mod');
		$db = db('SystemSettings');
		$file = "./Config/common.php";
		//$config = include($file);//读取本地配置文件
		//$this -> assign('config',$config);
		
		if(request()->isPost()){
			$arr = input('post.');
			//$db->create();
			unset($arr['button']);
			unset($arr['config']);
			foreach($arr as $k => $val){
				//$db -> value = $val;
				$db -> where(["name" => $k]) -> setField('value',$val);//update(['vlaue' => $val]);
			}
			/*
			//修改本地配置文件
			$config = input('post.config');
			$word = "<?php\r\nreturn array(\r\n";
			foreach($config as $key => $val){
				$word .= "'".$key."' => '".$val."',\r\n";
			}
			$word .= ");";
			file_put_contents($file, $word);
			//修改本地配置文件
			*/
			return  $this->success('修改成功','','',1);
			//$this -> cache();
		}else{
			$sql = $db -> where("class > '0'") -> select();
			
			foreach($sql as $k => $val){
				//dump($val);
				$sql[$k]['value'] = htmlspecialchars_decode($val['value']);
				$system[$val['name']] = $val;
			}
			$this -> assign('sql',$sql);
			//dump($sql);
			return $this->fetch('/system');
			
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
			db('system_settings') -> where(['name' => 'theme']) -> update(['value' => $dir]);
			//修改首页配置文件
			$config = file_get_contents('./config.php');
			//$config = strtr($config,VIEW_PATH,'/template/'.$dir.'/');
			$config = str_replace(VIEW_PATH,'./template/'.$dir.'/',$config);
			file_put_contents('./config.php',$config);
			return $this -> success('切换为模板'.$dir);
			
		}else{
			//dump($list);
			$this -> assign('list',$list);
			return $this -> fetch('./system_theme');
		}
	}
	
	public function cache(){
		\think\Cache::clear();
		$this -> deldir(CACHE_PATH);		//CACHE_PATH 项目模板缓存目录（默认为 RUNTIME_PATH.'cache/'）
		$this -> deldir(TEMP_PATH);		//TEMP_PATH 应用缓存目录（默认为 RUNTIME_PATH.'temp/'）
		
		return $this -> success("更新缓存成功",'index','',1);
	}
	
	
	/*遍历删除所有缓存文件*/
	public function deldir($dir){
		$dh = opendir($dir);
		while ($file = readdir($dh)){
			if ($file != "." && $file != ".."){
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)){
					unlink($fullpath);
				}else{
					$this -> deldir($fullpath);
				}
			}
		}
		closedir($dh);
		if (rmdir($dir)){
			return true;
		}else{
			return false;
		}
	}
}
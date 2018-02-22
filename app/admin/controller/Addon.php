<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Addon extends Common{
	private $dir = './addons/';
    
    public function addon_list(){
		$list = Db::name('addon')-> select();
		$dir = $this -> dir;
		$list2 = scandir($dir);
        unset($list2[0]);
        unset($list2[1]);
        
        //$list = Db::name('addon') -> select();
        foreach($list2 as $key => $val){
            $list2[$key] = include($dir. DS. $val. DS. 'config.php');
            $list2[$key]['dir'] = $val;
            foreach($list as $key2 => $val2){
                if($val == $val2['directory']){
                    //检测新版
                    if($val2['version'] < $list2[$key]['version']){
                        $list[$key2]['new_version'] = $list2[$key]['version'];
                    }
                    unset($list2[$key]);
                    break;
                }
            }
        }
        
		$this -> assign('list',$list);
		$this -> assign('list2',$list2);
		
		return $this -> fetch('./addon_list');
	}
    
    
   
    
    /**
    *
    *
    */
    public function addon_setting($id){
        $settings = Db::name('addon') -> where('id',$id) -> value('settings');
        empty($settings) or $settings = json_decode($settings,true);
        if(request() -> isPost()){
            $post = input('post.');
            foreach($settings as $key => $val){
                $settings[$key]['value'] = $post[$key];
            }
            $data = ['settings' => json_encode($settings)];
            Db::name('addon') -> where('id',$id) -> update($data);
            return $this -> success(lang("操作完成"));
        }else{
            $this -> assign('sql',$settings);
            return $this -> fetch('./addon_setting');
        }
    }
    
    
    /**
    *   插件创建
    *
    */
    public function addon_add(){
        
        if(request() -> isPost()){
            $post = input('post.');
            $post['tag'] = [];
            $post['settings'] = [];
            $post['menu'] = [];
            
            $dir =  $this -> dir . $post['directory'];
            if(file_exists($dir)){
                return $this -> error("目录[".$post['directory']."]已存在！");
            }
            
            //Db::name('addon') -> insert($post);
            mkdir($dir);
            
            $str = "<?php\nreturn ".var_export($post,true).";";
            file_put_contents($dir.DS."config.php",$str);
            $this -> success("创造插件成功",'addon_list',null,1);
            
        }else{
            return $this -> fetch('./addon_add');
        }
        
        
    }
    
   
    
    
    public function addon_edit($id){
        $sql = Db::name('addon') -> find($id);
        $this -> assign('sql',$sql);
        return $this -> fetch('./addon_edit');
    }
	
    
    public function addon_del($dir){
        $dirs = $this -> dir . $dir;
        $system = new \app\admin\controller\System();
        $system -> deldir($dirs);
        $this -> success('插件['.$dir."]删除完成",null,null,1);
    }    
	
	
}
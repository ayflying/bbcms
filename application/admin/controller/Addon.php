<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Addon extends Common{
	public function addon_list(){
		
		//$db = Db::name('addon');
		$list = Db::name('addon')-> select();
		$dir = './addons';
		$list2 = scandir($dir);
        unset($list2[0]);
        unset($list2[1]);
        
        //$list = Db::name('addon') -> select();
        foreach($list2 as $key => $val){
            foreach($list as $val2){
                if($val == $val2['directory']){
                    unset($list2['key']);
                    break;
                }
            }
        }
        foreach($list2 as $key => $val){
            $list2[$key] = include($dir. DS. $val. DS. 'config.php');
            $list2[$key]['dir'] = $val;
        }
        
		$this -> assign('list',$list);
		$this -> assign('list2',$list2);
		
		return $this -> fetch('./addon_list');
	}
    
    public function addon_add(){
        
        if(request() -> isPost()){
            $post = input('post.');
            $dir =  "./addons/".$post['directory'];
            if(file_exists($dir)){
                return $this -> error("目录[".$post['directory']."]已存在！");
            }
            
            //Db::name('addon') -> insert($post);
            mkdir($dir);
            
            $str = "<?php
return ".var_export($post,true).";";
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
	
    
    public function addon_delete($directory){
        $system = new \app\admin\controller\System();
        $system -> deldir($directory);
        $this -> success('插件['.$directory."]删除完成",null,null,1);
    }    
	
	
}
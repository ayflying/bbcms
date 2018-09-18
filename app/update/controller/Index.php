<?php
namespace app\update\controller;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;
use think\Controller;

class Index extends Controller
{
    
    public function index(){
        
        $list = Cache::remember('updaelist',function(){
            $file_list = array_merge(Config::get('file'), files(Config::get('dir')));
            //dump(Cache::get('updaelist'));
            
            foreach($file_list as $val){
                if(is_file($val)){
                    $list[] = [
                        'dir' => $val,
                        'md5' => md5_file($val),
                        'size' => filesize($val),
                    ];
                }
            }
            
            return $list;
		});
        
        
        return json($list);
	}
    
    /*
		允许下载文件
		@post.file 接受文件路径
		@post.key 正确时无压缩，错误时压缩传输
	
	*/
	public function download(){
        $download = new Update();
        $download -> download();
    }
    
   
    
}

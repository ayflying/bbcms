<?php
namespace app\update\controller;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;
use think\Controller;

class Index extends Controller
{
    
    public function index(){
        if(!Cache::get('updaelist')){
            $file_list = array_merge(Config::pull('file'), files(Config::pull('dir')));
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
            
            Cache::set('updaelist',$list);
		}
        $list = cache::get('updaelist');
        echo json_encode($list);
        return;
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

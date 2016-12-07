<?php
namespace app\update\controller;
use think\Cache;
use think\Config;
use think\Request;
use think\Controller;

class Index extends Controller
{
    
    public function index(){
        if(!Cache::get('updaelist')){
            $file_list = array_merge(Config::get('file'), files(Config::get('dir')));
            //dump(Cache::get('updaelist'));
            
            foreach($file_list as $val){
                $list[] = [
                    'dir' => $val,
                    'md5' => md5_file($val),
                    'size' => filesize($val),
                ];
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
        $post= Request::instance()->post();
        //$post= Request::instance()->get();
        if(empty($post['key'])){
            $this -> error("key is error!");
        }
        
        $file_path = $post['file'];
        if(!file_exists($file_path)){
			$this -> error('The file does not exist');
		}
        
        
        //过滤安全目录
		foreach(Config::get('dir') as $val){
            if(strpos($file_path, $val) == 0){
                $error = 0;
				break;
            }
		}
        //过滤安全文件
		foreach(Config::get('file') as $val){
			if($val == $file_path){
				$error = 0;
				break;
			}
		}
        //非安全目录跳出
		if($error != '0'){
			return $this -> error("download is error!");
		}
        
        
        //下载文件需要用到的头
        header("Content-type:text/html;charset=utf-8");
        header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length:".filesize($file_path));
		header("Content-Disposition: attachment; filename=".basename($file_path));
        echo file_get_contents($file_path);
        
    }
    
   
    
}

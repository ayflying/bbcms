<?php
namespace app\update\controller;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;
use think\Controller;

class Update extends Controller
{
    public function download(){
        $post= Request::instance()->post();
        if(empty($post['host']) && empty($post['key'])){
            $this -> error("host is error!");
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
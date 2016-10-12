<?php
namespace app\update\controller;
use think\Cache;
use think\Config;
use think\Request;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    
    public function updatelist(){
        
        /*
		foreach(Config::get('file') as $val){
			//$files[$val] = md5_file($val);
            $list[] = [
                'dir' => $val,
                'md5' => md5_file($val),
                'size' => filesize($val),
            ];
		}
        */
        if(!Cache::get('updaelist')){
            //$arr[] = Config::get('file');
			//$arr = $this -> files(Config::get('dir'));
			
        
            //$arr[] = $data;
            
            $file_list = array_merge(Config::get('file'), $this -> files(Config::get('dir')));
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
        //$list = array_merge($files,Cache::get('updaelist'));
        
		//$this->ajaxReturn($list);
        //dump($list);
        echo json_encode($list);
        //echo json_encode(['aa','bbb']);
        
	}
    
    /*
		允许下载文件
		@get.file 接受文件路径
		@get.key 正确时无压缩，错误时压缩传输
	
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
    
    /*///////////////////////////////////////////
		//递归查询服务器文件列表与md5
		$dir array 文件夹列表数组
		return
			array('file_dir' => md5);
	/*//////////////////////////////////////////////
	public function files($dir){
		foreach($dir as $val){
			$dir = $this -> tree($val.'/');
		}
		return $dir;
	}
	/*递归核心函数开始*/
	public function tree($dir){
		static $tree;
		$dir = glob($dir.'*');
		foreach($dir as $val){
			if(is_dir($val)){
				//$tree[] = $val;
				//echo $val."<br/>";
				$this -> tree($val.'/');
			}else{
				$pattern = str_replace('/','\/',APP_PATH);
				//$val = preg_replace("/^".$pattern."/",'APP_PATH',$val);
				//$tree[$val] = md5_file($val);
                $tree[] = $val;
			}
		}
		return $tree;
	}
	/*递归核心函数结束*/
    
}

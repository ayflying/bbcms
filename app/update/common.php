<?php
 /*///////////////////////////////////////////
		//递归查询服务器文件列表与md5
		$dir array 文件夹列表数组
		return
			array('file_dir' => md5);
	/*//////////////////////////////////////////////
	function files($dir){
		foreach($dir as $val){
			$dir = tree($val.'/');
		}
		return $dir;
	}
	/*递归核心函数开始*/
	function tree($dir){
		static $tree;
		$dir = glob($dir.'*');
		foreach($dir as $val){
			if(is_dir($val)){
				//$tree[] = $val;
				//echo $val."<br/>";
				tree($val.'/');
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
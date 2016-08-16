<?php
namespace app\admin\controller;
use think\Config;

class Index extends Common{
	
	public function index(){
		
		$list = [
			"服务器IP" => GetHostByName($_SERVER['SERVER_NAME']),
			"操作系统" => PHP_OS,
			"当前主机名:端口" => $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'], //当前主机名
			"当前时间" => date("Y-m-d H:i:s"),
			"框架版本" => THINK_VERSION,
			"语言" => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
			"PHP版本" => PHP_VERSION,
			'Zend版本' => Zend_Version(),
			"DG库版本" => $this -> GD('GD Version'),
			'系统版本' =>  'V'.THINK_VERSION,
			//"mysql版本" =>  $this->_mysql_version(),
			//"数据库大小" => $this->_mysql_db_size(),
			"服务器类型" =>  $_SERVER["SERVER_SOFTWARE"],
			//'gd' => gd_info(),
			"最大上传尺寸" => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled",
			"最大执行时间" => ini_get("max_execution_time")."秒",
			"当前登录IP" => request()->ip(),
		];
		//dump($arr);
		
		
		$this -> assign('list',$list);
		
		$num = [
			'article' => $this -> article(),
			'user' => $this -> user(),
		];
		$this -> assign('num',$num);
		return $this->fetch('/index');
		
	}
	
	private function article(){
		
		//php获取今日开始时间戳和结束时间戳
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
		//php获取昨日起始时间戳和结束时间戳
		$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
		$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
		//php获取上周起始时间戳和结束时间戳
		$beginLastweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
		$endLastweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
		//php获取本月起始时间戳和结束时间戳
		$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
		$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
		
		$db = db('PortalArticle');
		$sql['num'] = $db ->  count();
		$sql['today'] = $db -> where("create_time >= $beginToday") -> count();
		
		return $sql;
	}
	
	private function GD($string){
		$gd = gd_info();
		return $gd[$string];
	}
	
	private function user(){
		
		$db = db('member_user');
		$sql['num'] = $db -> count();
		return $sql;
	}
	
	/*
	private function _mysql_version(){
		$version = db()->query("select version() as ver");
		return $version[0]['ver'];
	}
	private function _mysql_db_size(){        
		$sql = "SHOW TABLE STATUS FROM ".config('DB_NAME');
		$tblPrefix = config('DB_PREFIX');
		if($tblPrefix != null) {
			$sql .= " LIKE '{$tblPrefix}%'";
		}
		$row = db()->query($sql);
		$size = 0;
		foreach($row as $value) {
			$size += $value["Data_length"] + $value["Index_length"];
		}
		return round(($size/1048576),2).'M';
	}
	*/
	
	function body(){
		
		$this-> display('./index_body');
	}
	
	function login(){
		$this-> display('./login');
	}
	/*
	function _initialize(){
		echo "123123123123123";
	}
	*/
	
}
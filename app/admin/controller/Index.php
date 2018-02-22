<?php
namespace app\admin\controller;
use think\facade\Config;
use think\facade\App;
use think\Db;

class Index extends Common{
	
	public function index(){
		$list = [
			"服务器IP" => GetHostByName($_SERVER['SERVER_NAME']),
			"操作系统" => PHP_OS,
			"当前主机名:端口" => $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'], //当前主机名
			"当前时间" => date("Y-m-d H:i:s"),
            '系统与数据版本' =>  Config::get('version') .' - '. Db::name('system_settings') -> where('name','version') -> value('value'),
			"框架版本" => App::version(),
			"语言" => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
			"PHP版本" => PHP_VERSION,
			'Zend版本' => Zend_Version(),
			"DG库版本" => $this -> GD('GD Version'),
            '最大执行时间' => ini_get("max_execution_time")."秒",
			"数据库版本" =>  $this->_mysql_version(),
			"数据库大小" => $this->_mysql_db_size(),
			"服务器类型" =>  $_SERVER["SERVER_SOFTWARE"],
			"最大上传尺寸" => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled",
			"最大执行时间" => ini_get("max_execution_time")."秒",
			"当前登录IP" => request()->ip(),
            "附件容量" => format_bytes(Db::name('portal_attachment') -> sum('size')),
		];
		//dump($arr);
		
		
		$this -> assign('list',$list);
		
		$num = [
			'article' => $this -> article(),
			'user' => $this -> user(),
		];
		$this -> assign('num',$num);
		return $this->fetch('./index');
		
	}
	
	private function article(){
		$sql['num'] = Db::name('PortalArticle') ->  count();
		$sql['today'] = Db::name('PortalArticle') -> whereTime('create_time', 'today') -> count();
        $sql['yesterday'] = Db::name('PortalArticle') -> whereTime('create_time', 'yesterday') -> count();
        $sql['week'] = Db::name('PortalArticle') -> whereTime('create_time', 'week') -> count();
        $sql['month'] = Db::name('PortalArticle') -> whereTime('create_time', 'month') -> count();
		return $sql;
	}
	
	private function GD($string){
		$gd = gd_info();
		return $gd[$string];
	}
	
	private function user(){
		$sql['num'] = Db::name('member_user') ->  count();
		$sql['today'] = Db::name('member_user') -> whereTime('create_time', 'today') -> count();
        $sql['yesterday'] = Db::name('member_user') -> whereTime('create_time', 'yesterday') -> count();
        $sql['week'] = Db::name('member_user') -> whereTime('create_time', 'week') -> count();
        $sql['month'] = Db::name('member_user') -> whereTime('create_time', 'month') -> count();
		return $sql;
	}
	
    
    private function _mysql_version()
    {
        $version = Db::query("select version() as ver");
        return $version[0]['ver'];
    }
	private function _mysql_db_size(){
        $database = config::pull('database');
		$sql = "SHOW TABLE STATUS FROM ".$database['database'];
		$tblPrefix = $database['prefix'];
		if($tblPrefix != null) {
			$sql .= " LIKE '{$tblPrefix}%'";
		}
		$row = Db::query($sql);
		$size = 0;
		foreach($row as $value) {
			$size += $value["Data_length"] + $value["Index_length"];
		}
        return format_bytes($size);
	}
	
    //计算目录容量
    private function CalcDirectorySize($DirectoryPath)
    {
      $Size = 0;
      $Dir = opendir($DirectoryPath);
      if (!$Dir)
        return -1;
      while (($File = readdir($Dir)) !== false) {
        // Skip file pointers
        if ($File[0] == '.') continue; 
        // Go recursive down, or add the file size
        if (is_dir($DirectoryPath . $File))      
          $Size += $this -> CalcDirectorySize($DirectoryPath . $File . DIRECTORY_SEPARATOR);
        else 
          $Size += filesize($DirectoryPath . $File);    
      }
      closedir($Dir);
      return format_bytes($Size);
    }
    
    function getDirSize($dir)
    {
        $size = 0;
        $dirs = [$dir];
         
        while(@$dir=array_shift($dirs)){
            $fd = opendir($dir);
            while(@$file=readdir($fd)){
                if($file=='.' && $file=='..'){
                    continue;
                }
                $file = $dir.DIRECTORY_SEPARATOR.$file;
                if(is_dir($file)){
                    array_push($dirs, $file);
                }else{
                    $size += filesize($file);
                }
            }
            closedir($fd);
        }
        return $size;
    }
	
	function body(){
		$this-> display('./index_body');
	}
	
	function login(){
		$this-> display('./login');
	}
	
}
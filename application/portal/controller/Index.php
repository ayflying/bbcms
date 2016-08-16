<?php
namespace app\portal\controller;
use app\common\controller\Common;

class Index extends Common{
	
	
	public function index()
    {
		return $this-> fetch('portal/index');
		
    }
	
	function ceshi(){
		$this -> theme("2123")->fetch2('112323');
		
		return $this -> fetch2('portal/index');
		
	}
}

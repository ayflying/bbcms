<?php
namespace app\portal\controller;
use app\common\controller\Common;

class Index extends Common{
	
	
	public function index()
    {
		return $this-> fetch('portal/index');
		
    }
	
}

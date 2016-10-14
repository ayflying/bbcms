<?php
namespace app\wechat\controller;
use think\Db;
use app\member\controller\Common;

class Cart extends Common{
	public function index($aid=null){
		$cart = cookie('cart');
		
		if(!empty($aid)){
			$cart[$aid] = 1;
			cookie('cart',$cart,31536000);
		}
		$aid = array_keys($cart);
		$aid = implode(",",$aid);
		$list = Db::view(['portal_article','a'],'*') -> view(['portal_mod_2','m'],'*','a.aid=m.aid')
		-> where('a.aid','in',$aid) -> select();
		
		$this -> assign('list',$list);
		return $this -> fetch('./wechat/cart');
	}
	
	public function delete($aid){
		$cart = cookie('cart');
		unset($cart[$aid]);
		cookie('cart',$cart,31536000);
		return $aid;
	}
	
	
	
	
	
}
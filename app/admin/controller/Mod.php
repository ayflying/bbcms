<?php
namespace app\admin\controller;
use think\facade\Config;
use think\Db;

use app\portal\model\PortalMod;

class Mod extends Common{
	
	function mod(){
		$list = Db::name('portal_mod') -> order('id desc') -> select();
		$this -> assign('list',$list);
		return $this -> fetch('./mod_list');
	}
	
	function mod_add(){
		
		if(request()->isPost()){
			$post = input('post.');
			$table = 'portal_mod';
			$validate = [
				"name|模型名称" => ['require','unique'=>$table],
			];
			$result = $this -> validate($post,$validate);
			if($result !== true){
				$this -> error($result);
			}
			//验证结束
			$post['data'] = json_encode([]);
			$table_id = Db::name($table) -> insertGetId($post);
			$sql = 'create table '.Config::get('database.prefix').'portal_mod_'.$table_id.' (aid int(11) not null auto_increment primary key)';
			Db::execute($sql);
            
            
			return $this -> success(lang('添加完成'),'mod');
		}else{
			return $this -> fetch('./mod_add');
		}
	}
	
	function mod_edit($id){
		$db = Db::name('portal_mod');
		//$list = $db -> find($id);
        $list  = PortalMod::get($id);
		$prefix = Config::get('database.prefix');
		if(request()->isPost()){
			$post = input('post.');
			//$data = json_decode($list['data'],true);
            $data = $list['data'];
            //添加字段
			if(input('action') == 'add'){
				
				$data[$post['field']] = [$post['name'],$post['type'],$post['value']];
				$list -> data = $data;
                $list -> save();
				$sql = 'ALTER TABLE '.$prefix.'portal_mod_'.$id.' ADD '.$post['field'].' varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci';
				
                Db::execute($sql);
				return $this -> success(lang('添加完成'));
			
			}else{	//修改字段
				
				$data_keys = array_keys($data);
				//格式化post
                foreach($post['field'] as $key => $val){
					$post2[$val] = [$post['name'][$key],$post['type'][$key],$post['value'][$key]];
					if($val != $data_keys[$key]){	//判断字段名是否有修改，同步修改模型表
						$sql = 'alter table '.$prefix.'portal_mod_'.$id.' change '.$data_keys[$key].' '.$val.' varchar(255)';
						Db::execute($sql);
					}
				}
                $list -> data = $post2;
                $list -> save();
				return $this -> success(lang('修改完成 '));
			}
		
		}else{
			//$list['data'] = json_decode($list['data'],true);
		}
		
		$this -> assign('list',$list);
		return $this -> fetch('./mod_edit');
			
	}
	
	public function mod_del($id){
		$db = Db::name('portal_mod');
        $table = PortalMod::where('id',$id) -> field('data,table') -> find();
        $prefix = Config::get('database.prefix');
		if($field = input('field')){	//删除单条
            $sql = 'ALTER TABLE '.$prefix.'portal_mod_'.$id.' DROP COLUMN '.$field;
            $data = $table['data'];
            unset($data[$field]);
            $table -> save(['data'=>$data]);
		}else{
			$sql = "DROP TABLE ".$prefix."portal_mod_".$id;
            $prefix->delete();
            
            
		}		
		Db::execute($sql);
		$this -> success(lang('删除完成'));
	}
	
}
<?php
namespace app\admin\controller;
use think\Config;
use think\Db;

class Mod extends Common{
	
	function mod(){
		$db = Db::name('portal_mod');
		$list = $db -> order('id desc') -> select();
		$this -> assign('list',$list);
		return $this -> fetch('./mod_list');
	}
	
	function mod_add(){
		
		if(request()->isPost()){
			$post = input('post.');
			$table = 'portal_mod';
			$validate = [
				'name|模型名称' => ['require','unique'=>$table],
				'table|数据表名'	=>['require','alphaNum','unique'=>$table],
			];
			$result = $this -> validate($post,$validate);
			if($result !== true){
				$this -> error($result);
			}
			//验证结束
			
			Db::name($table) -> insert($post);
			$sql = 'create table '.config('database')['prefix'].'portal_mod_'.$post['table'].' (aid int(11) not null auto_increment primary key)';
			Db::execute($sql);
			return $this -> success('创建成功');
			
		}else{
			return $this -> fetch('./mod_add');
		}
	}
	
	function mod_edit($id){
		$db = Db::name('portal_mod');
		$list = $db -> find($id);
		$prefix = config('database')['prefix'];
		if(request()->isPost()){
			$post = input('post.');
			$data = json_decode($list['data'],true);
			if(input('action') == 'add'){	//添加字段
				
				$data[$post['field']] = [$post['name'],$post['type'],$post['value']];
				$data = json_encode($data);
				$db -> where('id',$id) -> update(['data'=>$data]);
				$sql = 'ALTER TABLE '.$prefix.'portal_mod_'.$list['table'].' ADD '.$post['field'].' varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci';
				Db::execute($sql);
				return $this -> success("添加成功");
			
			}else{	//修改字段
				
				$data_keys = array_keys($data);
				//格式化post
				foreach($post['field'] as $key => $val){
					$post2[$val] = [$post['name'][$key],$post['type'][$key],$post['value'][$key]];
					if($val != $data_keys[$key]){	//判断字段名是否有修改，同步修改模型表
						$sql = 'alter table '.$prefix.'portal_mod_'.$list['table'].' change '.$data_keys[$key].' '.$val.' varchar(255)';
						echo $sql;
						Db::execute($sql);
					}
				}
				$db -> where('id',$id) -> update(['data'=>json_encode($post2)]);
				return $this -> success("修改成功");
			}
		
		}else{
			$list['data'] = json_decode($list['data'],true);
		}
		
		$this -> assign('list',$list);
		return $this -> fetch('./mod_edit');
			
	}
	
	public function mod_del($id){
		$db = Db::name('portal_mod');
		$table = $db -> field('data,table') -> find($id);
		$prefix = config('database')['prefix'];
		//dump($table);
		if($field = input('field')){	//删除单条
			//$field = input('field');
			$data = json_decode($table['data'],true);
			unset($data[$field]);
			$data = json_encode($data);
			$db -> where('id',$id) -> update(['data'=>$data]);
			$sql = 'ALTER TABLE '.$prefix.'portal_mod_'.$table['table'].' DROP COLUMN '.$field;
		}else{
			$sql = "DROP TABLE ".$prefix."portal_mod_".$table['table'];
			$db -> delete($id);
		}
		
		Db::execute($sql);
		$this -> success("删除成功");
	}
	
}
<?php
namespace app\admin\controller;
use think\Config;
use think\Db;
use app\member\model\MemberUser as  User;

class Member extends Common{
	
	public function ceshi($uid){
		
	}
	
	public function user(){
		$list = User::paginate(PAGE_NUM);
		$page = $list->render();
		$this->assign('page', $page);
		$this -> assign('list',$list);
		return $this -> fetch("./member_user_list");
	}
	
	public function user_add(){
		if(request()->isPost()){
			$post = input('post.');
			$user = new User();
			
			$validate = [
				'email|邮箱' => ['require','email','unique'=>'member_user'],
				'password|密码' => ['length'=>'6,25'],
			];
			$result = $this -> validate($post,$validate);
			if($result !== true){
				$this -> error($result);
			}
			//验证结束
			
			
			if($uid = $user->save($post)){
				Db::name('member_user_profile') -> insert(['uid'=> $uid]);
			return $this->success($uid.'新增成功');
				
			}else{
				return $this -> error($user->getError());
				
			}
			
		}else{
			return $this-> fetch("./member_user_add");
		}
	}
	
	public function user_edit($uid){
		
		if(request()->isPost()){
			$post = input('post.');
			//验证开始
			$validate = [
				'email|邮箱' => ['require','email','unique'=>'member_user,email,'.$uid,],
				'password|密码'	=>['length'=>'6,25'],
			];
			$result = $this -> validate($post,$validate);
			if($result !== true){
				$this -> error($result);
			}
			//验证结束
            
            //密码进行md5加密
			if($post['password'] == null){
                unset($post['password']);
            }else{
                $post['password'] = md5($post['password']);
            }
            
			
            Db::name('member_user') -> where('uid',$uid) -> update($post);
            return $this -> success('编辑完成',null,null,1);
            /*
			if($db -> save($post,['uid' => $uid])){
				return $this->success('编辑完成');
			}else{
				return $this -> error($db->getError());
			}
            */
		}else{
			//$sql = $db -> get($uid);
            $sql = Db::name('member_user') -> find($uid);
			$group = Db::name('member_group') -> select();
			$this -> assign('group',$group);
			$this -> assign('sql',$sql);
			return $this -> fetch('./member_user_edit');
		}
		
	}
	
	/*用户组操作*/
	public function group(){
		$list = Db::name('member_group') -> select();
		$this -> assign('list',$list);
		return $this -> fetch('./member_group_list');
	}
	
	function group_add(){
		if(request()->isPost()){
			Db::name('member_group') -> insert(input('post.'));
			return $this -> success('添加成功','group');
		}else{
            $list = Db::name('member_action') -> where(['status'=>1]) -> select();
            $this -> assign('list',$list);
			return $this -> fetch('./member_group_edit');
		}
	}
	
	function group_edit($gid){
		
		if(request()->isPost()){
            $post = input('post.');
            $post['value'] = implode(',',$post['value']);
			Db::name('member_group') -> where('gid',$gid) -> update($post);
			return $this -> success(lang('修改完成'),null,null,1);
		}else{
			$group = Db::name('member_group') -> find($gid);
            $list = Db::name('member_action') -> where(['status'=>1]) -> select();
			$this -> assign('sql',$group);
            $this -> assign('list',$list);
			return $this -> fetch('./member_group_edit');
		}
	}
	
	function group_delete($gid){
		Db::name('member_group') -> where('gid',$gid) -> delete();
		return $this -> success('删除成功','group');
	}
	
    
    function action($pid=0)
    {
        if($pid > 0){
            $sql = Db::name('member_action') -> where('id',$pid) -> value("name");
            $this -> assign('sql',$sql);
        }
        $list = Db::name('member_action') -> where(['pid' => $pid, 'status' => 1]) -> paginate(PAGE_NUM);
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this -> fetch('./member_action_list');
        
    }
    
    function action_add()
    {
        
        if(request()->isPost()){
            $post = input('post.');
            
           empty($post['status']) && $post['status'] =  1;
            Db::name('member_action') -> insert($post);
            //dump($post);
            $this -> success("添加成功",null,null,1);
            
        }else{
            $list = Db::name('member_action') -> where(['pid'=>0,'status'=>1]) -> select();
            $this -> assign('list', $list);
            return $this -> fetch('./member_action_edit');
        }
        
    }
    
    function action_edit($id){
        if(request()->isPost()){
            $post = input('post.');
            
           empty($post['status']) && $post['status'] =  1;
            Db::name('member_action') -> where('id',$id) -> update($post);
            //dump($post);
            $this -> success(lang('编辑完成'),null,null,1);
            
        }else{
            
            $list = Db::name('member_action') -> where(['pid'=>0,'status'=>1]) -> select();
            $this -> assign('list', $list);
            
            
            $sql = Db::name('member_action') -> find($id);
            $this -> assign('sql', $sql);
            return $this -> fetch('./member_action_edit');
        }
    }
    
    
    public function action_delete($id){
        $where['id|pid'] = $id;
        Db::name('member_action') -> where($where) -> delete();
        return $this -> success("删除成功");
    }
    
    public function action_ajax($id = null, $pid = null){
        
        if(!empty($id)){
            $sql = Db::name('member_action') -> find($id);
            return $this -> success('返回成功',null,$sql,10);
        }
        
        
        if(!empty($pid)){
            $list = Db::name('member_action') -> where(['pid'=>$pid,'status'=>1]) -> select();
            //$this -> assign('list', $list);
            $this -> success("返回成功",null,$list);
            //$this -> fetch("返回成功",null,$list);
        }
        
        
    }
    
}
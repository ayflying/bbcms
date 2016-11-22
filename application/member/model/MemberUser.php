<?php
namespace app\member\model;
use think\Model;

class MemberUser extends Model{
	
	protected $name = 'member_user';
	
	protected $auto = ['email','password'];
	protected $insert = ['status'=>1,'create_ip']; 
	protected $update = ['update_ip'];
	
	protected function setEmailAttr($value){
		return strtolower($value);
	}
    
	protected function setPasswordAttr($value)
    {
        return md5($value);
	}
    
	protected function setCreateIpAttr($value)
    {
		return request()->ip();
	}
    
	protected function setUpdateIpAttr($value)
    {
		return request()->ip();
	}
	
	public function profile(){
		return $this->hasOne('MemberUserProfile','uid');
	}
}
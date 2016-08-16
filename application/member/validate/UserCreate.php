<?php
namespace app\member\validate;
use think\Validate;

class UserCreate extends Validate{
	// 验证规则
    protected $rule = [
        'email'    => ['require','email','unique'=>'member_user'],
		'pword'	=>['length'=>'6,25'],
    ];
	
	protected $message  =   [
        'email.require' => '邮箱不能为空',
		'email.email' => '邮箱格式错误',
		'email.unique' => '该邮箱已存在',
		'pword.length'     => '密码长度为6-25个字符',
    ];
}
<?php
namespace app\portal\model;

use think\Model;

class PortalAttachment extends Model{
	protected $name = 'portal_attachment';
	// 关闭自动写入update_time字段
	protected $updateTime = false;
}
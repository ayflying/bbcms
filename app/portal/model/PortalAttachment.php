<?php
namespace app\portal\model;

use think\Model;

class PortalAttachment extends Model{
	protected $name = 'portal_attachment';
    protected $pk = 'aid';
    
    // 是否开启写入update_time字段
    protected $autoWriteTimestamp = true;
	protected $updateTime = false;
}
<?php
namespace app\portal\controller;
use think\Db;
use think\Request;
use think\Image;
use think\Config;
//use app\member\controller\Common;
use app\common\controller\Common;

use app\portal\model\PortalArticle;
use app\portal\model\PortalAddonarticle;
//use app\portal\model\PortalAttachment;

class Post extends Common{
    
    /*      UEditor 编辑器插件   */
    public function UEditor(){
        config('app_trace',false);  //必须关闭调试
        header("Content-Type:text/html;charset=utf-8");
        //$type = $_REQUEST['type'];
        //$noCache = input('noCache');
        $action=input('action');
        
        switch ($action){
        case 'config':  //基本配置
            $config = Config::get('ueditor');
            return $result =  json_encode($config);
            break;
            
        case Config::get('ueditor.imageActionName'):    //图片上传
            $file = request()->file(Config::get('ueditor.imageFieldName'));
            $url = './uploads/portal';
            if(isset($file)){
                $info = $file->validate(['ext' => 'bmp,jpg,jpeg,png,gif','size' => Config::get('ueditor.imageMaxSize')]) -> move($url);
                $thumb = $this -> thumb($info->getPathname());
            }
            break;
            
        case 'listimage':   //图片列表
            $sql = Db::name('portal_attachment') -> where('uid',$this->uid) -> where('type',in('jpg,png,bmp,gif')) -> select();
            //dump($list);
            foreach($sql as $val){
                $list[] = [
                    'url' => $val['url'],
                    'mtime' => $val['create_time']
                ];
            }
            $arr = [
                "state" => 'SUCCESS',
                "list" => $list,
                "start" => 0,
                "total" => count($list),
            ];
            return json_encode($arr);
            break;
        default:
            break;
        }
        
    
        if(isset($info)){
            $url = str_replace('\\','/',$info->getPathname());
            $arr =[
                "originalName" => $file-> getInfo()['name'] ,
                "name" => $info->getFilename(),
                //"url" => $url.$info->getFilename(),
                "url" => $url,
                "size" => $info->getSize(),
                "type" => $info-> getExtension(),
                "state" => 'SUCCESS',
            ];
            
            $data = [
                'uid' => $this -> uid,
                'original' => $arr['originalName'],
                'name' => $arr['name'],
                'url' => $arr['url'],
                'size' => filesize($arr['url']),    //获取实际大小
                'type' => $arr['type'],
                'create_time' => time(),
            ];
            $id = Db::name("portal_attachment") -> insert($data);
            /*
            $att = new PortalAttachment();
            $att -> save($data);
            */
        }else if(isset($file)){
            
            $arr = [
                'state' => $file->getError(),
            ];
            //exit($file->getError());
        }else{
            $arr = ['state' => '错误代码'.$file['error'].'文件上传失败，请检查php配置参数'];
        }
        if(isset($arr)){
            return json_encode($arr);
        }
    }
    
    
    /*
        内容添加
    */
    public function add($tid){
        $uid = $this -> uid;
        $sql = Db::name('portal_menu') -> find($tid);
        $this -> assign('title',$sql['name'].'发布 - ');
        
        if($sql['mod'] > 0){
            $mod = Db::name('portal_mod')-> field('table,data') -> find($sql['mod']);
            $mod_data = json_decode($mod['data'],true);
        }
        
        if (request()->isPost()){
            $post = input('post.');
            
            $add = new PortalArticle;
            $add -> uid = $uid;
            $add -> tid = $tid;
            $add -> mod = $sql['mod'];
            //$add -> title = $post['title'];
            
            
            //焦点图
            $thumb = $this -> thumb_upload('thumb');
            $add -> litpic =  isset($thumb) ? $litpic = reset($thumb) : null;
            //isset($thumb) && $add -> thumb = json_encode($thumb);
            $add -> thumb = $thumb;
            
            //创建主键写入数据库
            $add -> allowField(true) -> save($post);
            $aid = $add -> aid;
            $add -> addonarticle() ->save(['content'=>$post['content']]);
            //修改附件为当前aid
            Db::name('portal_attachment') -> where('aid','null') ->  where('uid',$uid) -> setField('aid',$aid);
            
            return $this -> success("发布完成",null,null,1);
        }else{
            
            $this -> _G['title'] = $sql['name'].' - ';
            $this -> assign('_G',$this -> _G);
            return $this->fetch('/post/add');
        }
    }
	
	public function edit($aid){
		$uid = $this -> uid;
        
        if(request() -> isAjax()){
            $post = input('post.');
            $article = PortalArticle::get($aid)->thumb;
            //修改thumb
            $thumb_id = $post['thumb_id'];
            //dump($article['thumb']);
            $data['thumb'] = $article;
            $files = request()->file('thumb');
            foreach($files as $file){
                //dump($file);
                $info = $file -> move('./uploads/ceshi');
                $this -> thumb($info->getPathname(),300);
                $file_data = $this -> attachment($info,$aid,$file-> getInfo()['name']);
                $data['thumb'][$thumb_id] = $file_data['url'];
                $data['aid'] = $aid;
                PortalArticle::update($data);
            }
            $this -> success("success");
        }
        
        
		if (request()->isPost()){
            $post = input('post.');
            unset($post['thumb']);
            $article = PortalArticle::get($aid);
            $article -> allowField(true) -> save($post);
            $article -> addonarticle -> save(['content' => $post['content']]);
            $this -> success("编辑完成",null,null,1);
            
        }else{
            $article = PortalArticle::get($aid);
            $article -> addonarticle;
            //dump($article -> toArray());
            $this -> _G['title'] = $article -> title.' - ';
            $this -> _G['article'] = $article;
            $this -> assign('_G',$this -> _G);
            
            return $this->fetch('/post/edit');
        }
		
		
	}
    
    /*
        缩略图上传类
    */
    public function thumb_upload($name = 'file'){
        $files = request()->file($name);
        if(empty($files)){
            return;
        }
        $url = './uploads/thumb/';
        foreach($files as $file){
            $info = $file->validate(['ext' => 'bmp,jpg,jpeg,png,gif'])  -> move($url);
            $this -> thumb($info->getPathname());   //压缩
            $data = [
                'uid' => $this -> uid,
                'original' => $file-> getInfo()['name'],
                'name' => $info->getFilename(),
                'url' => $info->getPathname(),
                'size' => filesize($info->getPathname()),   //获取实际大小
                'type' => $info-> getExtension(),
                'create_time' => time(),
            ];
            $id = Db::name("portal_attachment") -> insert($data);
            $thumb[] = $data['url'];
        }
        return $thumb;
    }
    
    //写入附件数据库
    public function attachment($info,$aid=null,$original=null){
        
        $data = [
                'uid' => $this -> uid,
                'aid' => $aid,
                'original' => $original,
                'name' => $info->getFilename(),
                'url' => $info->getPathname(),
                'size' => filesize($info->getPathname()),   //获取实际大小
                'type' => $info-> getExtension(),
                'create_time' => time(),
            ];
        $id = Db::name("portal_attachment") -> insert($data);
        return $data;
    }
    
    /*图片压缩*/
    public function thumb($file,$x=1280,$y=9999,$dir=null){
        
        $image = \think\Image::open($file);
        if($image->width() > $x || $image->height() > $y){
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
            $image->thumb($x,$y);
            //图片裁剪
            //$image->crop(300, 300, 100, 50);
            //图片旋转
            //$image->flip();
            //empty($dir) and $dir = $file;
            $dir = !empty($dir) ? $dir : $file;
            $image ->save($dir);
        }
        return $image;
    }
    
}
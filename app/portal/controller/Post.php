<?php
namespace app\portal\controller;
use think\Db;
use think\facade\Cache;
use think\facade\Request;
use think\Image;
use think\facade\Config;

use app\common\controller\Common;
use app\portal\model\PortalArticle;
use app\portal\model\PortalAttachment;
use app\portal\model\PortalMod;

class Post extends Common{

   /**
    *   初始化权限，验证是否登录
    *
    */
    public function initialize(){
        parent::initialize();
        if(empty($this -> uid)){
            cookie('from',request()->url());
            $this -> error("正在跳转到登录","member/login/login");
        }
    }

    /*      UEditor 编辑器插件   */
    public function UEditor(){
        Config::set('app_trace',false);  //必须关闭调试
        header("Content-Type:text/html;charset=utf-8");
        //$type = $_REQUEST['type'];
        //$noCache = input('noCache');
        $action=input('action');
        
        switch ($action){
        case 'config':  //基本配置
            //$config = Config::pull('ueditor');
            $config = include APP_PATH."/portal/config/ueditor.php";
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
            $where = [
                ['uid','=',$this -> uid],
                ['type','in','jpg,png,bmp,gif'],
            ];
            $sql = Db::name('portal_attachment') -> where($where) -> select();
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
            //$arr = ['state' => lang('错误').$file['error'].lang('文件上传失败')];
            $arr = ['state' => lang('错误').lang('文件上传失败')];
        }
        if(isset($arr)){
            return json_encode($arr);
        }
    }
    
    //webupload模块
    public function webuploader(){
        $file = request()->file('file');
        $info = $file->move( './uploads/thumb/');
        if($info){
            $this -> thumb($info->getPathName(),800,600);
            $url = str_replace('\\','/',$info->getPathName());
            $data =[
                "uid" => $this -> uid,
                "original" => $file-> getInfo()['name'],
                "name" => $info->getFilename(),
                "url" => $url,
                "size" => $info->getSize(),
                "type" => $info-> getExtension(),
                
            ];
            
            PortalAttachment::create($data);
            //thumb缓存
            $arr = Cache::get("webuploader_".$this->uid);
            $arr[] = $data['url'];
            Cache::set("webuploader_".$this->uid,$arr,7200);
            print_r($arr);
        }
        
    }


    /*
        内容添加
    */
    public function add($tid){
        $uid = $this -> uid;
        $sql = Db::name('portal_menu') -> find($tid);
        //$this -> assign('title',$sql['name'].'发布 - ');
        


        if (request()->isPost()){
            $post = input('post.');
            if(isset($post['mod'])){
                $mod_data = $post['mod'];
                unset($post['mod']);
            }

            $add = new PortalArticle;
            $add -> uid = $uid;
            $add -> tid = $tid;
            $add -> mod = $sql['mod'];
            //$add -> title = $post['title'];

            
            //焦点图
            
            $thumb = $this -> thumb_upload('thumb');
            $add -> thumb = $thumb;
            //$add -> litpic =  isset($thumb) ? reset($thumb) : null;
            //选择合适的图片为预备缩略图
            if(isset($thumb)){
                $read_litpic = reset($thumb);
            }else{
                $read_litpic = Db::name('portal_attachment') -> where('aid','null') ->  where('uid',$uid) -> value("url");
            }
            
            //存在缩略图，开始压缩
            if(isset($read_litpic))
            {
                $dir = "./uploads/litpic/".date("Ymd").'/';
                is_dir($dir) or mkdir($dir.date("Ymd"));
                $litpic = $dir.pathinfo($read_litpic,PATHINFO_BASENAME);
                $this -> thumb($read_litpic,300,9999,$litpic);
                $data = Db::name('portal_attachment') -> field('id,url,size',true) -> where('url',$read_litpic) -> find();
                $data['url'] = $litpic;
                $data['size'] = filesize($litpic);
                Db::name("portal_attachment") -> insert($data);
                $add -> litpic = $litpic;
            }
            $thumb = Cache::pull("webuploader_".$this->uid);
            
            //创建主键写入数据库
            $add -> allowField(true) -> save($post);
            $aid = $add -> aid;
            $add -> addonarticle() ->save(['content'=>htmlspecialchars($post['content'])]);

            //检测当前内容模型
            if($sql['mod'] > 0){
                foreach($mod_data as $key => $val){
                    if(is_array($val)){
                        $mod_data[$key] = implode(',',$val);
                    }
                }
                $mod_data['aid'] = $aid;
                $mod = Db::name('portal_mod_'.$sql['mod'])-> insert($mod_data);
            }

            Cache::clear('list');   //更新缓存

            //修改附件为当前aid
            Db::name('portal_attachment') -> where('aid','null') ->  where('uid',$uid) -> setField('aid',$aid);
            return $this -> success(lang('提交完成'),url('portal/article/index',['aid'=>$aid]),null,1);
            
        }else{
            Cache::rm("webuploader_".$this->uid);
            if($sql['mod'] > 0){
                $mod = PortalMod::where('id',$sql['mod']) -> field('data') -> find();
                $mod_data = $mod['data'];
                foreach($mod_data as $key => $val){
                    $mod_data[$key]['param'] = explode(',',$val[2]);
                }
                $this -> _G['mod'] = $mod_data;
            }
            //$this -> _G['title'] = '发布 - '.$sql['name'];
            $this -> _G['menu'] = $sql;
            return $this->fetch($sql['template_add']);
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
                $info = $file -> move('./uploads/thumb');
                $this -> thumb($info->getPathname(),300);
                $file_data = $this -> attachment($info,$aid,$file-> getInfo()['name']);
                $data['thumb'][$thumb_id] = $file_data['url'];
                $data['aid'] = $aid;
                PortalArticle::update($data);
            }
            $this -> success("success");
        }


		if (request()->isPost()){
            Cache::rm('article_'.$aid);
            $cache = Cache::pull('article_'.$aid);

            $post = input('post.');
            if(isset($post['mod'])){
                $mod_data = $post['mod'];
                unset($post['mod']);
            }
            unset($post['thumb']);
            $article = PortalArticle::get($aid);
            $article -> allowField(true) -> save($post);
            $article -> addonarticle -> save(['content' => $post['content']]);

            //检测模型层是否修改
            if($article->mod > 0 && isset($mod_data)){
                foreach($mod_data as $key => $val){
                    if(is_array($val)){
                        $mod_data[$key] = implode(',',$val);
                    }
                }
                $mod = Db::name('portal_mod_'.$article -> mod) -> where('aid',$aid) -> update($mod_data);
            }


            $this -> success(lang('编辑完成'),url('portal/article/index',['aid'=>$aid]),null,1);

        }else{
            $article = PortalArticle::get($aid);
            $article -> addonarticle;
            Cache::set("webuploader_".$this->uid,$article['thumb'],7200);
            //dump($article -> toArray());
            if($article -> mod > 0){
                $mod = Db::name('portal_mod')-> field('table,data') -> find($article -> mod);
                $mod_data = json_decode($mod['data'],true);
                $mod_value = Db::name('portal_mod_'.$article->mod) -> find($aid);
                foreach($mod_data as $key => $val){

                    $mod_data[$key][2] = explode(',',$val[2]);
                    $value = explode(',',$mod_value[$key]);
                    if(isset($value[2])){
                        $mod_data[$key]['value'] = $value;
                    }else{
                        $mod_data[$key]['value'] = $mod_value[$key];
                    }
                }
                $this -> _G['mod'] = $mod_data;
            }

            //$this -> _G['title'] = $article -> title;
            $this -> _G['article'] = $article;
            $template_edit = Db::name('portal_menu') -> where('tid',$article['tid']) -> cache(true) -> value('template_edit');
            return $this->fetch($template_edit);
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
        $url = './uploads/thumb';
        foreach($files as $file){
            $info = $file->validate(['ext' => 'bmp,jpg,jpeg,png,gif'])  -> move($url);
            $this -> thumb($info->getPathname());   //压缩
            $data = [
                'uid' => $this -> uid,
                'original' => $file -> getInfo()['name'],
                'name' => $info->getFilename(),
                'url' => str_replace('\\','/',$info->getPathname()),
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
                //'create_time' => time(),
            ];
        $id = Db::name("portal_attachment") -> insert($data);
        return $data;
    }

    /*图片压缩*/
    public function thumb($file,$x=1280,$y=9999,$dir=null){

        $image = Image::open($file);
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
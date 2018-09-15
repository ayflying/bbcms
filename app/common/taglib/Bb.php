<?php
namespace app\common\taglib;
use think\template\TagLib;
use think\Db;
use think\Cache;
use think\facade\Url;

use app\portal\model\PortalArticle;

/**
 * 自定义标签库demo
 * 因为composer的原因，本demo放在目前位置，也可以放在\think\template\taglib目录下，区别请在配置文件中参考
 */
class Bb extends TagLib{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'close' => ['attr' => 'time,format', 'close' => 0], //闭合标签，默认为不闭合
        'open' => ['attr' => 'name,type', 'close' => 1],
        'ceshi' => ['attr' => 'name'],
        'menu' => ['attr'=>'pid,row,item','level'=>3],
        'list'  => ['attr' => 'tid,row,order'],
        'article' => ['attr' => 'aid,tid,row,typeid,order,type,time'],
        //'url' => ['attr' => 'aid,tid', 'close' => 0],
        'sql' => ['attr' => 'table,where,row,order,item', 'level' => 5],
        'ad' => ['attr'=>'id', 'close' => 0],
        'flink' => ['attr'=>'id, row', 'level' => 1],
        'header' => ['attr' => 'file', 'close' => 0],
        'prenext' => ['attr'=> 'get','close' => 0],

    ];

    
    

    public function tagCeshi($tag,$content){
        $name = empty($tag['time']) ? time() : $tag['time'];
        $parse = '<?php';
        $parse .= ''.$name.'';

        $parse .= $content;
        
        return $parse;
    }

     /**
     * menu标签解析 循环输出数据集
     * @access public
     * @param array $tag 标签属性
     * @param string $content  标签内容
     * @return string|void
     */
    public function tagMenu($tag,$content) {
        $tid = !empty($tag['tid'])?$tag['tid']:null;
        $row = !empty($tag['row'])?$tag['row']:999;
        $item = !empty($tag['item'])?$tag['item']:'bb';

        $Str = '<?php
            $where = [];
        ';


        if(!empty($tid)){
            $tid = $this -> autoBuildVar($tid); //格式化数组变量
            $Str .= '
                $'.$item.' = Db::name("portal_menu") -> where("tid","'.$tid.'") -> cache(true) -> find();
                $'.$item.'["url"] = Url::build("portal/lists/index?tid='.$tid.'"); ?>';

            $Str  .=   $content;
            return $Str;
        }

        if(isset($tag['pid'])){
            $pid = $this -> autoBuildVar($tag['pid']); //格式化数组变量
            $Str .= ' $where[] = ["pid","=",'.$pid.']; ';
        }
        
        $Str .= '
            $tag_sql = Db::name("portal_menu") -> where($where) -> order("weight desc") -> limit("'.$row.'") -> cache(true) -> select();
            foreach($tag_sql as $key => $'.$item.'):
            $'.$item.'["url"] = Url::build("portal/lists/index?tid=".$'.$item.'["tid"]);
        ?>';
        $Str  .=   $content;
        $Str  .=   '<?php endforeach; ?>';

        if(!empty($Str)) {
            return $Str;
        }
        return ;
    }


    /*
        门户文章调用标签
        * @param array $tag 标签属性
        * @param string $content  标签内容
        * @return string|void
        * @return $key 为编号
    */
    public function tagArticle($tag,$content){
        $aid = !empty($tag['aid']) ? $tag['aid'] : null;
        $tid = !empty($tag['tid']) ? $tag['tid'] : null;
        $row = !empty($tag['row']) ? $tag['row'] : 99;
        $type = !empty($tag['type']) ? $tag['type'] : null;
        $order = !empty($tag['order']) ? $tag['order'] : 'create_time desc';    //当前
        $item = !empty($tag['item'])?$tag['item']:'bb';
        $time = !empty($tag['time'])?$tag['time']:null;

        $aid = $this -> autoBuildVar($aid); //格式化数组变量
        $tid = $this -> autoBuildVar($tid); //格式化数组变量
        $link = null;
        
        
        $cache = "bb_a_".md5($aid."-".$tid."-".$row."-".$type."-".$order."-".$item);
        
        $Str = '<?php
            $where = [
                ["status",">",0],
            ];
            //echo "ceshi:"."$ceshi";
        ';

        if(!empty($type)){
            //$Str .= ' $where[] = ["'.$type.'",'=',"not null"]; ';
            $link .= " -> whereNotNull('$type')";
        }

        if(!empty($tid)){
            $Str .= ' $where[] = ["tid","in",['.$tid.']]; ';
        }
        
        if(!empty($time)){
            $link .= ' ->whereTime("create_time", "'.$time.'") ';
        }

        $Str .= ' 
            $db = model("portal/PortalArticle");
            //$db = new PortalArticle;
            $relation = ["attachment","menu"];
        ';

        if(!empty($aid)){
            $Str .= ' $tag_sql = $db -> all("'.$aid.'",$relation);
            ';
        }else{
            
            //自建缓存
            $Str .='
                /*
                $tag_sql = Cache::remember("'.$cache.'",function() use ($db,$where,$relation){
                    
                    return $tag_sql = $db -> all(function($query) use ($where){
                        $query -> where($where) '.$link.' -> limit('.$row.') -> order("'.$order.'") -> cache(true);
                    },$relation);
                    
                });
                */
                
                
                $tag_sql = $db -> all(function($query) use ($where){
                    $query -> where($where) '.$link.' -> limit('.$row.') -> order("'.$order.'") -> cache(true);
                },$relation);
                
                
            ';
            
            /*
            $Str .= '
                $tag_sql = $db -> where($where) -> limit('.$row.') -> order("'.$order.'") -> cache(true) -> select();
                echo "456".time();
                $db -> addonarticle -> content;
            ';
            */
            
        }


        //循环获取模型
        $Str .= ' foreach($tag_sql as $key => $'.$item.'):
            if($'.$item.'["mod"] > 0){
                $mod_table = "portal_mod_".$'.$item.'["mod"];
                $'.$item.'["mod"] = Db::name($mod_table)-> where("aid",$'.$item.'["aid"]) -> cache(true)  -> find();
            }
            $'.$item.'["url"] = Url::build("portal/Article/index?aid=".$'.$item.'["aid"]);
            $'.$item.'["turl"] = Url::build("portal/Lists/index?tid=".$'.$item.'["tid"]);
        ?>';

        $Str .= $content;
        $Str .=   '<?php endforeach; ?>';

        //PortalArticle
        if(!empty($Str)) {
            return $Str;
        }
        return ;
    }

    /**
	数据库查询
	* @param array $tag 标签属性
	* @param string $content  标签内容
	* @return string|void
	*/
    public function tagSql($tag,$content){
        $table =   !empty($tag['table'])?$tag['table']:null;
		$where =   !empty($tag['where'])?$tag['where']:null;
		$row =   !empty($tag['row'])?$tag['row']:null;
		$order =   !empty($tag['order'])?$tag['order']:null;
		$item =   !empty($tag['item'])?$tag['item']:'bb';
		//$cache = !empty($tag['cache'])?$tag['cache']:'true';



		$Str = '<?php ';
		$Str .= '$tag_sql = Db::name("'.$table.'") -> where("'.$where.'") -> limit("'.$row.'") -> order("'.$order.'") -> select(); ';
		$Str .=   ' foreach($tag_sql as $'.$item.'): ';
		$Str .= '?>';
		$Str .= $content;
		$Str .=   '<?php endforeach; ?>';
		if(!empty($Str)) {
            return $Str;
        }
        return ;
    }
    
    
    /**
	上一篇，下一篇
	* @param array $tag 标签属性
	* @param string $content  标签内容
	* @return string|void
	*/
    public function tagPrenext($tag,$content){
        
        $get = !empty($tag['get']) ? $tag['get'] : null;
        //dump($_G);
        
        if($get == 'pre'){
            $Str = '<?php
                $where = [
                    ["aid","<",$_G["article"]["aid"] ],
                    ["tid","=",$_G["article"]["tid"]],
                ];
                $bb = Db::name("portal_article") -> where($where) -> order("aid desc") -> find();
            ';
        }
        
        if($get == 'next'){
            
            $Str = '<?php
                $where = [
                    ["aid",">",$_G["article"]["aid"] ],
                    ["tid","=",$_G["article"]["tid"]],
                ];
                $bb = Db::name("portal_article") -> where($where) -> order("aid") -> find();
            ';
        }
        $Str .= "?> ";
        $Str .= '<a href="{:url("portal/article/index",["aid" => $bb.aid])}" >{:$bb.title}</a>';
        
        $Str .= $content;
        
        return $Str;
    }
    
    
    /**
	广告
	* @param array $tag 标签属性
	* @param string $content  标签内容
	* @return string|void
	*/
	public function tagAd($tag,$content){
		$id = $tag['id'];
		//$where = $tag['where'];
		$Str = '<?php ';
		$Str .= ' $tag_sql = Db::name("operate_ad") -> where("id",'.$id.') -> cache(true) -> find(); ';
		$Str .= ' if($tag_sql["status"] != 0 || $tag_sql["end_time"] > time()): ';
		$Str .= ' echo htmlspecialchars_decode($tag_sql["value"]); ';
		$Str .= ' endif; ';

		//$Str .= ' $tag_sql = ""; ';
		//$Str .= ' endif;  echo htmlspecialchars_decode($tag_sql["value"]); ';
		$Str .= ' ?>';
		if(!empty($Str)) {
            return $Str;
        }
        return ;
	}

    /**
	 友情链接
	* @param array $tag 标签属性
	* @param string $content  标签内容
	* @return string|void
	*/
	public function tagFlink($tag,$content){
		$id =   !empty($tag['id']) ? $tag['id'] : null;
		$row =   !empty($tag['row']) ? $tag['row'] : 99;

		//$where = $tag['where'];
		$Str = '<?php ';
		$Str .= ' $tag_sql = Db::name("operate_flink") -> limit('.$row.') -> cache(true) -> select(); ';
		$Str .=   ' foreach($tag_sql as $bb): ';
		$Str .= '?>';
		//$Str .= $this->tpl->parse($content);
        $Str .= $content;

		$Str .=   '<?php endforeach; ';
		$Str .= '?>';

		if(!empty($Str)) {
            return $Str;
        }
        return ;
	}

    /**
     * 这是头部引用模板
     * @param unknown $tag
     * @return string
     */
    public function tagHeader($tag)
    {
        $file =   !empty($tag['file']) ? $tag['file'] : '/common/header';
        echo "asdgasgsdfgh";
        $Str = '<?php ';

        $Str .= '
            $template = [
                "view_path" => "/template/",
            ];
            //$this -> engine($template);
            {//include file="'.$file.'" }
            view("'.$file.'");

        ';

        $Str .= '  {//include file="./common/header" title="" keywords="" description=""} ';

        $Str .= ' ?>';
        return $Str;
    }

    /**
     * 这是一个闭合标签的简单演示
     * @param unknown $tag
     * @return string
     */
    public function tagClose($tag)
    {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time = empty($tag['time']) ? time() : $tag['time'];
        $parse = '<?php ';
        $parse .= 'echo date("' . $format . '",' . $time . ');';
        $parse .= '
        ?>';
        return $parse;
    }
    /**
     * 这是一个非闭合标签的简单演示
     * @param unknown $tag
     * @return string
     */
    public function tagOpen($tag, $content)
    {
        $type = empty($tag['type']) ? 0 : 1; // 这个type目的是为了区分类型，一般来源是数据库
        $name = $tag['name']; // name是必填项，这里不做判断了
        $parse = '<?php ';
        $parse .= '$test_arr=[[1,3,5,7,9],[2,4,6,8,10]];'; // 这里是模拟数据
        $parse .= '$__LIST__ = $test_arr[' . $type . '];';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

}
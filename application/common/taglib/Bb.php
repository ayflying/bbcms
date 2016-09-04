<?php
namespace app\common\taglib;
use think\template\TagLib;
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
        'close'     => ['attr' => 'time,format', 'close' => 0], //闭合标签，默认为不闭合
        'open'      => ['attr' => 'name,type', 'close' => 1],
        'ceshi'      => ['attr' => 'name'],
        'menu'  => ['attr'=>'topid,row,item','level'=>3],
        'list'  => ['attr'=>'tid,row,order'],
        'article'   => ['attr'=>'aid,tid,row,typeid,order,type'],
        //'url'     => ['attr' => 'aid,tid', 'close' => 0],
        'sql' => ['attr'=>'table,where,row,order,item','level'=>5],
        'ad' => ['attr'=>'id','close'=>0],
        'flink' => ['attr'=>'id,row','level'=>1],
        
        
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
        $topid = !empty($tag['topid'])?$tag['topid']:0;
        $row = !empty($tag['row'])?$tag['row']:999;
        $item = !empty($tag['item'])?$tag['item']:'bb';
        
        $topid = $this -> autoBuildVar($topid); //格式化数组变量
        
        $parseStr = '<?php
            $where = [];
        ';
        
        $parseStr .= '
            $tag_sql = \think\Db::name("PortalMenu") -> where("topid",'.$topid.') -> order("weight desc") -> limit("'.$row.'") -> select();
            foreach($tag_sql as $key => $'.$item.'):
            //input("tid") and $'.$item.'["action"] = "click";
            $'.$item.'["url"] = url("/tid/".$'.$item.'["tid"]);
        ?>';
        $parseStr  .=   $content;
        //$parseStr .= $this->tpl->parse($content);
        $parseStr  .=   '<?php endforeach; ?>';
        
        if(!empty($parseStr)) {
            return $parseStr;
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
        
        $aid = $this -> autoBuildVar($aid); //格式化数组变量
        $tid = $this -> autoBuildVar($tid); //格式化数组变量
        
        
        $Str = '<?php 
        $where = array(
            "status" => ["GT",0],
        ); 
        
        ';
        
        if(!empty($tid)){
            $Str .= ' $where["tid"] = '.$tid.'; ';
        }
        
        /*
        $Str .= ' $tag_sql = think\Db::view("portal_article","aid,tid,title,uid")
        -> view("portal_addonarticle","content","portal_addonarticle.aid = portal_article.aid")
        -> view("portal_menu",["name"=>"tname","mod"],"portal_article.tid = portal_menu.tid")
        -> where($where) -> limit('.$row.') -> order("'.$order.'")-> select('.$aid.'); ';
        */
        $Str .= ' $db = model("portal/PortalArticle");
        $relation = ["addonarticle","attachment"];
        ';
        
        if(!empty($aid)){
            $Str .= ' $tag_sql = $db -> all("'.$aid.'",$relation); ';
        }else{
            
            $Str .= '
            $tag_sql = $db -> all(function($query) use ($where){
                $query->where($where)  ->limit('.$row.')->order("'.$order.'");
            },$relation);
            
            
          
            ';
          
        }
        //循环获取模型
        $Str .= ' foreach($tag_sql as $key => $'.$item.'):
            if($'.$item.'["mod"] > 0){
                $mod_table = "portal_mod_".$'.$item.'["mod"];
                $'.$item.'["mod"] = db($mod_table) -> find($'.$item.'["aid"]);
            }
            $'.$item.'["url"] = url("/aid/".$'.$item.'["aid"]);
            $'.$item.'["turl"] = url("/tid/".$'.$item.'["tid"]);
            //dump($'.$item.');
            
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
	广告
	* @param array $tag 标签属性
	* @param string $content  标签内容
	* @return string|void
	*/
	
	public function tagAd($tag,$content){
		$id = $tag['id'];
		//$where = $tag['where'];
		$Str = '<?php ';
		$Str .= ' $tag_sql = db("announce_ad") -> find('.$id.'); ';
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
		$id =   !empty($tag['id'])?$tag['id']:null;
		$row =   !empty($tag['row'])?$tag['row']: 99;
        
		//$where = $tag['where'];
		$Str = '<?php ';
		$Str .= ' $tag_sql = db("announce_flink")  -> limit('.$row.') -> select(); ';
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
        $parse .= ' ?>';
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
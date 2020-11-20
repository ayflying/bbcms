<?php

namespace app\common\controller;

use think\Controller;
use think\facade\Cache;
use think\facade\Config;
use think\Db;
use think\facade\Hook;
use think\facade\Debug;

class Common extends Controller
{

    public $uid;
    //public $settings;
    public $_G;


    public function initialize()
    {
        Debug::remark('begin');

        //检测当前用户UID
        $user = Db::name('member_user')->where('guid', cookie('guid'))->cache(true)->find();

        $this->uid = $user['uid'] ?? 0;
        $this->authority($user['guid'] ?? 0);      //权限检测



        if (!cache('settings')) {
            $list = Db::name('system_settings')->select();
            foreach ($list as $val) {
                if ($val['name'] == 'statistics') {
                    $val['value'] = htmlspecialchars_decode($val['value']);
                }
                $settings[$val['name']] = $val['value'];
            }
            Cache::set('settings', $settings);
        }
        $this->_G = [
            'system' => Cache::get('settings'),
        ];

        if(!empty($user['uid'])){
            $user['uid'] > 0 && $this->_G['user'] = $user;
        }
        //dump($this -> settings);


    }


    public function authority($gid)
    {

        if(empty($gid)){
            return;
        }
        $where = [
            'module' => strtolower(request()->module()),
            'controller' => strtolower(request()->controller()),
            'action' => strtolower(request()->action()),
        ];

        /*
         * todo 权限控制没有使用
        //$list = Db::name('member_action') -> where('module',$where['module']) -> select();
        $group = Db::name('member_group')->cache('group_' . $gid)->find($gid);
        $group_arr = explode(',', $group['value'] ?? "");
        $action = Db::name('member_action')->where('id', 'not in', $group_arr)->cache('action_' . $group['value'] ?? "")->select();
        */
    }


    /**
     * 创建静态页面
     * @access protected
     * @htmlfile 生成的静态文件名称
     * @htmlpath 生成的静态文件路径
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @return string
     */
    protected function buildHtml($htmlfile = '', $htmlpath = '', $templateFile = '')
    {
        $content = $this->fetch($templateFile);
        $htmlpath = !empty($htmlpath) ? $htmlpath : './html/';
        $htmlfile = $htmlpath . $htmlfile . '.' . config('url_html_suffix');
        $File = new \think\template\driver\File();
        $File->write($htmlfile, $content);
        return $content;
    }


    /**
     * 解析和获取模板内容 用于输出
     *  重写系统fetch方法
     *  该方法会自动检测当前主题的模板是否存在
     * @access public
     * @param string $template 模板文件名或者内容
     * @param array $vars 模板输出变量
     * @param array $config 模板参数
     * @param bool $renderContent 是否渲染内容
     * @return string
     * @throws \Exception
     */

    protected function fetch($template = '', $vars = [], $config = [], $renderContent = false)
    {

        $config = array_merge($config, Config::pull('template'));
        $config['tpl_replace_string'] = [
            '__Tpl__' => Config::get('bbcms.view_path'),
            '__PUBLIC__' => '/public',
        ];
        //dump($config);
        $file = $config['view_path'] . $template . '.' . $config['view_suffix'];
        if (!is_file($file)) {

            $config['tpl_replace_string']['__Tpl__'] = "/template/default";
            $config['view_path'] = "./template/default/";
            $template = './template/default/' . $template . "." . $config['view_suffix'];
        }
        //dump($config);

        $this->assign('_G', $this->_G);
        return $this->view->fetch($template, $vars, $config, $renderContent);
    }


}
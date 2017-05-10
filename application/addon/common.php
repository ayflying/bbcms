<?php

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);

// 注册类的根命名空间
//\think\Loader::addNamespace('addons',ADDON_PATH);

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 * @return array
 */
 
function get_addon_config($name=null)
{
    empty($name) and $name = input('name');
    /*
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $addon = new $class();
        return $addon->getConfig();
    } else {
        return [];
    }
    */
    return include(ADDON_PATH . $name . DS . 'config.php');
}

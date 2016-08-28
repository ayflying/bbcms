# bbcms
BBCMS是基于Thinkphp5开发的一款内容管理系统。

# 环境要求
程序基于Thinkphp开发的，因此本身没有什么特别模块要求，具体的应用系统运行环境要求视开发所涉及的模块。ThinkPHP底层运行的内存消耗极低，而本身的文件大小也是轻量级的，因此不会出现空间和内存占用的瓶颈。

## 支持的服务器和数据库环境
*   支持Windows/Unix服务器环境
*   可运行于包括Apache、IIS和nginx在内的多种WEB服务器和模式
*   支持Mysql、MsSQL、PgSQL、Sqlite、Oracle、Ibase、Mongo以及PDO等多种数据库和连接

## PHP版本要求
*   PHP5.4以上版本

#	文件目录
```
├─ Addons						插件目录
├─ application					应用目录
│├─ addon						插件模块  
│├─ admin						管理模块
││├─ controller				后台功能目录
││├─ view						后台模板目录
│├─ common					公共模块    
│├─ member					用户模块    
│├─ portal						内容模块
│├─ config.php					网站全局配置
│├─ common.php				全局函数配置
│├─ database.php			数据库配置
│├─ helper.php				助手函数配置
│├─ route.php					路由配置
├─ public							公共资源目录
├─ runtime						缓存与日志模块
├─ template						模板目录
│├─ default						默认模板
├─ thinkphp						框架目录
├─ uploads						上传附件目录
├─ .htaccess					伪静态规则
├─ index.php					入口文件
├─ robots.txt					搜索引擎定义
```

# 版权所有
湖北络易科技有限公司版权所有
[http://www.luoe.cn](http://www.luoe.cn)
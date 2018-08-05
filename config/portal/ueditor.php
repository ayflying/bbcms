<?php


return [
	'imageActionName'=> "uploadimage",
	"imageFieldName"=> "upfile", 
	"imageMaxSize"=> 1024*1024*8,
	"imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
	"imageCompressEnable"=> true, /* 是否压缩图片,默认是true */
	"imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
	"imageInsertAlign" => "none", /* 插入的图片浮动方式 */
	"imageUrlPrefix" => "/", /* 图片访问路径前缀 */

	/* 涂鸦图片上传配置项 */
	'scrawlActionName' => 'uploadscrawl',/* 执行上传涂鸦的action名称 */
	"scrawlFieldName" => "upfile", /* 提交的图片表单名称 */

	/* 抓取远程图片配置 */
	"catcherLocalDomain" => ["127.0.0.1", "localhost", "img.baidu.com"],
	"catcherActionName" => "catchimage", /* 执行抓取远程图片的action名称 */
	
	/* 上传文件配置 */
	'fileActionName' => 'uploadfile',/* controller里,执行上传视频的action名称 */
	"fileFieldName" => "upfile", /* 提交的文件表单名称 */
	"fileUrlPrefix" => "", /* 文件访问路径前缀 */
	"fileMaxSize" => 1024*1024*8, /* 上传大小限制，单位B，默认50MB */
	
	/* 列出指定目录下的图片 */
	"imageManagerActionName" => "listimage",
	"imageManagerUrlPrefix"=> '/', /* 图片访问路径前缀 */
];
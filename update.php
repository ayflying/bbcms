<?php
/*
/*  在线升级文件
/*  用于升级本系统
/*
*/

include "./config.php";

$get = $_GET['action'];

switch($get){
    case 'file':
        download_file();
        break;
    case 'sql':
        download_sql();
        break;
    default:
        exit();
}


function download_file(){
    $url = UPDATE_URL . '/update/download';
    $file = './update.txt';
    file_exists($file) && $list = json_decode(file_get_contents('./update.txt'),true);
    if(empty($list)){
        unlink($file);
        echo json_encode(['size'=>0]);
        return null;
        exit;
    }
    $arr = array_shift($list);
    //$arr['count'] = count($list);
    $post = [
        'host' => $_SERVER['HTTP_HOST'],
        'file' => $arr['dir'],
    ];
    $put = curl($url,$post);
    
    $file_sub_path = dirname($post['file']).'/';
    file_exists($file_sub_path) or mkdir($file_sub_path,0777,true);
    file_put_contents($post['file'],$put);
    file_put_contents($file, json_encode($list));
    echo json_encode($arr);
}

/*
* curl函数
* @param str $url
* @param array $post
*/
function curl($url,$post=NULL){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if(isset($post)){	// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	
	$output = curl_exec($ch);
	curl_close($ch);
	
	return $output;
}
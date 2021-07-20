<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	ini_set('date.timezone','Asia/Shanghai');
    
	$files = $_GET["files"];
    $zipname =  $_SERVER['DOCUMENT_ROOT']. '/Things_env_setup[' . date('YmdHis') . '].rar';
    $files = explode(",",$files);
    $zip = new ZipArchive();
    $result = $zip -> open($zipname, ZipArchive::OVERWRITE | ZipArchive::CREATE);
    if($result === true) {
        foreach ($files as $file) {
            $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/Setup/' . $file,$file);
        }
    }
    $zip -> close();

    header('Cache-Control: must-revalidate');
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Transfer-Encoding: Binary');
    header('Accept-Ranges: bytes');
    header('Expires: 0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zipname));
    header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");

    $fp = fopen($zipname, 'rb');
    fseek($fp, 0);
 
    // 开启缓冲区
    ob_start();
    // 分段读取文件
    while (!feof($fp)) {
        $chunk_size = 1024 * 1024 * 2; // 2MB
        echo fread($fp, $chunk_size);
        ob_flush(); // 刷新PHP缓冲区到Web服务器
        flush(); // 刷新Web服务器缓冲区到浏览器
        sleep(1); // 每1秒 下载 2 MB
    }
    // 关闭缓冲区
    ob_end_clean();
    fclose($fp);
    unlink($zipname);
    exit;
?>

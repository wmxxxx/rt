<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    $file = $_GET["file"];
    $filename = dirname(dirname(dirname(__FILE__))) . '/Resources/upload/repair/' . $file;
    if(!file_exists($filename)){
        echo 'Not Found' . $filename;
    }else{
        header('Accept-Ranges: bytes');
        header('Accept-Length: ' . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file);
        header('Content-Type: application/octet-stream; name=' . $file);
        if(is_file($filename) && is_readable($filename)){
            $file = fopen($filename, "r");
            echo fread($file, filesize($filename));
            fclose($file);
        }
    }
?>

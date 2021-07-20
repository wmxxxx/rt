<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    include_once("../lib/base.php");
    date_default_timezone_set("PRC");
    
    $tag = $_GET["tag"];
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/Plugins/" . $tag . "/";
    $temp_name = $_FILES["script_fileUpload"]["tmp_name"];
    $file_name = $_FILES['script_fileUpload']['name'];
	$file_ext = array_pop(explode(".", $file_name));
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
    $ext = array("txt","sql");
    if(in_array($file_ext,$ext)){
        if(move_uploaded_file ($temp_name, $file_path . iconv("UTF-8", "GB2312", "setup.sql")))  
        {
            echo '{ status: 1}';
        }else{
	        echo '{ status: 0}';
        }
    }else{
        echo '{ status: -1, msg: "' . join('、',$ext) . '"}';
    }
?>

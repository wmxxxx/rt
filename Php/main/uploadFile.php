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
    ini_set('date.timezone','Asia/Beijing');
    $book = $_GET["book"];
    $page = $_GET["page"];
    $user = $_GET["user"];
    $file_path = "../../Resources/file/";
    $file_name = $_FILES['book_fileUpload']['name'];
    $temp_name = $_FILES["book_fileUpload"]["tmp_name"];
	$file_ext = array_pop(explode(".", $file_name));
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
    $file_name_new = date('YmdHis',time());
    $ext = array("doc","docx","ppt","pptx","xls","xlsx","pdf","txt","text","png","jpeg","gif","bmp","ico");
    if(in_array($file_ext,$ext)){
        if(move_uploaded_file ($temp_name, $file_path . iconv("UTF-8", "GB2312", $file_name_new . "." . $file_ext)))  
        {
            $sql = "exec proc_A_UploadBookFile '1',null,'" . $file_name_new . "','" . array_shift(explode('.', $file_name)) . "','" . $file_ext . "','" . $book . "'," . $page . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            $db -> execute($sql);
            echo '{ status: 1}';
        }else{
	        echo '{ status: 0}';
        }
    }else{
        echo '{ status: -1, msg: "' . join('、',$ext) . '"}';
    }
?>

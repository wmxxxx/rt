<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    include_once("../lib/base.php");
    
    $book = $_GET["book"];
    $user = $_GET["user"];
    $file_path = "../../Resources/images/book/";
    $file_name = $_FILES['file_coverUpload']['name'];
    $temp_name = $_FILES["file_coverUpload"]["tmp_name"];
	$file_ext = array_pop(explode(".", $file_name));
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
    $file_name_new = $book . "." . $file_ext;
    $ext = array("png","jpeg","bmp","ico","gif");
    
    if(in_array($file_ext,$ext)){
        if(move_uploaded_file ($temp_name, $file_path . $file_name_new))  
        {
            $sql = "exec proc_A_BookOperate '5','" . $book . "',null,'" . $file_name_new . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            $db -> execute($sql);
            echo '{ status: 1 }';
        }else{
	        echo '{ status: 0 }';
        }
    }else{
        echo '{ status: -1, msg: "' . join('、',$ext) . '"}';
    }
    
?>

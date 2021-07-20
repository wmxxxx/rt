<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    $userId = $_GET["userId"];
    $file_name = $_FILES['file_headUpload']['name'];
    $temp_name = $_FILES["file_headUpload"]["tmp_name"];
	$file_ext = array_pop(explode(".", $file_name));
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
    
    $ext = array("png","jpeg","bmp","ico");
    if(in_array($file_ext,$ext)){
        if(move_uploaded_file ($temp_name, "../../Resources/images/user/" . $userId . ".png"))  
        {
            echo '{ status: 1}';
        }else{
	        echo '{ status: 0}';
        }
    }else{
        echo '{ status: -1, msg: "' . join('、',$ext) . '"}';
    }
?>

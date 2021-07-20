<?php
    header("Content:text/html;charset=utf-8");
    include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/base.php");
    include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/plugin/share.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/Php/utils/warningUtils.php");
	
    $se = array();
    array_push($se,"1554396455");
	echo json_encode(_GETDATA($se));
?>
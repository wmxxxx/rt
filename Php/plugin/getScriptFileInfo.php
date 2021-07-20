<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    
    date_default_timezone_set("PRC");
	$tag = $_POST["tag"];
    
    $file = $_SERVER['DOCUMENT_ROOT'] . "/Plugins/" . $tag . "/setup.sql";
    
    $result = array(
		"status" => 0,
		"size" => "",
		"date" => ""
	);
    if(file_exists($file)){
        $result["status"] = 1;
        $result["size"] = round(filesize($file)/1024,2);
        $result["date"] = date('Y-m-d H:i:s',filemtime($file));
    }
    echo json_encode($result);
?>

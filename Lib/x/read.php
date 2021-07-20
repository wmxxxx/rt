<?php
	header("Content:text/html;charset=utf-8");
	include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/base.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/lib/x/x.php");
	$param = array(
		"deviceCode" => 597153517918,
		"cmdName" => "GetTimeStrategy"
	);
	$result = _curl("http://".$_SERVER['HTTP_HOST']."/API/IoT/sendDeviceCustomCmd/","post",array("param" => json_encode($data)));
	echo json_encode($result);
?>
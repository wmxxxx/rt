<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(__FILE__))."/share.php");
	$de_log = new stdClass();
	// 数据准备
	$arg = isset($_POST["arg"]) ? $_POST["arg"] : array();
	$res = new stdClass();
	$res -> F_id = 0;
	$res -> F_name = $_POST["name"];
	$res -> F_app = $_POST["pCode"];
	$res -> node = $_POST["nodelist"];
	$res -> command = array();
	$res-> command[$_POST["code"]] = $arg;
	$result = array($res);
	$result = sendCommand($result,$_POST["type"]);
	ob_clean();
	echo json_encode($result);
?>
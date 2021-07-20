<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	$InfoId = $_GET["infoid"];

	$sql = "select * from [Things+].[dbo].[TB_command_impl_config] where F_mx_id='".$InfoId."' order by F_value_code asc";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->data = $result;
	$obj->count = count($result);
	$obj->code = "0";
	ob_clean();
	echo json_encode($result);
?>
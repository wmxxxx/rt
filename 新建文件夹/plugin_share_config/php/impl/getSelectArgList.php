<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["infoid"];
	$sql = "select F_code as code,F_name+'(传入参数)' as name from [Things+].[dbo].[TB_command_interface_arg] where F_info_id='".$InfoId."'";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->data = $result;
	$obj->count = count($result);
	$obj->code = "0";
	ob_clean();
	echo json_encode($result);
?>
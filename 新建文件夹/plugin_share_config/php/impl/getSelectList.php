<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["infoid"];
	$sql = "select F_template_id from [Things+].[dbo].[TB_command_impl] where F_id='".$InfoId."'";
	$result = $db -> query($sql);
	$InfoId = $result[0] -> F_template_id;
	$sql = "select F_ValueLabel as code,F_ValueName+'(设备变量)' as name,F_ReadWrite as type,F_KV from [Things].[dbo].[tb_A_Value] where F_TemplateCode=".$InfoId."";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->data = $result;
	$obj->count = count($result);
	$obj->code = "0";
	ob_clean();
	echo json_encode($result);
?>
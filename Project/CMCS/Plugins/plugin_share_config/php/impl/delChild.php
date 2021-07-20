<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["id"];
	$sql = "delete from [Things+].dbo.TB_command_impl_mx where F_id='".$InfoId."';";
	$sql .= "delete from [Things+].dbo.TB_command_impl_contion where F_mx_id='".$InfoId."';";
	$sql .= "delete from [Things+].dbo.TB_command_impl_config where F_mx_id='".$InfoId."';";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->aaData = $result;
	ob_clean();
	echo json_encode($obj);
?>
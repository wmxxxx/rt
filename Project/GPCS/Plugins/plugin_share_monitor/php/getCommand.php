<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result -> com = $db -> query("select * from [Things+].[dbo].[TB_command_interface] order by F_remark");
	foreach($result -> com as $res){
		$res -> sub = $db -> query("select * from [Things+].[dbo].[TB_command_interface_arg] where F_info_id ='".$res -> F_id."' order by F_code");
	}
	$result -> tpl = array();
	$tpl = $db -> query("select * from [Things].[dbo].[tb_A_Template]");
	foreach($tpl as $t){
		$result -> tpl[$t -> F_TemplateCode] = $t -> F_TemplateLabel;
	}
	ob_clean();
	echo json_encode($result);
?>

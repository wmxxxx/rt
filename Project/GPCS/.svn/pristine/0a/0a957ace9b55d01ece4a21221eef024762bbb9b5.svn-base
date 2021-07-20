<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	
	$base = base();
	$device = $db -> query("select a.*,b.F_DeviceTypeID,b.F_TemplateLabel from [Things].[dbo].[tb_A_IoTNode] a,[Things].[dbo].[tb_A_Template] b where a.F_TemplateCode = b.F_TemplateCode and a.F_NodeCode =".$_POST["id"]);
	$config = $db -> query("select * from [Things+].[dbo].[TB_Share_Config] where F_Type = 'monitor_".$base -> version."' and F_App =".$_POST["app"]);
	$command = $db -> query("select * from [Things+].[dbo].[TB_command_interface]");
	foreach($command as $com){
		$com -> sub = $db -> query("select * from [Things+].[dbo].[TB_command_interface_arg] where F_info_id ='".$com -> F_id."' order by F_code");
	}
	$result = array(
		"device" => $device[0],
		"config" => json_decode($config[0] -> F_Config),
		"command" => $command
	);
	ob_clean();
	echo json_encode($result);
?>

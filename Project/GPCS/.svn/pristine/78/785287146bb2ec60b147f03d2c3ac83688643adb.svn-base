<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	
	$base = base();
	$result = curl($base -> ip."/API/RData/GetDeviceVariantData/?device_code=".$_GET["id"]);
	$result = intactAttr(array($result),$_GET["app"]);
	$pid = $db -> query("select F_EntityID from [Things].[dbo].[tb_B_EntityTreeToDevice] where F_DeviceID = ".$_GET["id"]);
	$pid = $pid[0];
	$result[0] -> access = checkAccess($_GET["app"],$pid -> F_EntityID,1);
	ob_clean();
	echo json_encode($result[0]);
?>

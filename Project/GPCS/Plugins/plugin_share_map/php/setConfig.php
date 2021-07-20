<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$base = base();
	$dataItem=$_POST["dataItem"];
	$app=$_POST["app"];
	$result = $db -> execute("delete [Things+].[dbo].[TB_Share_Config] where F_Type = 'map_".$base -> version."' and F_App =".$app.";insert into [Things+].[dbo].[TB_Share_Config] values('map_".$base -> version."',".$app.",'".json_encode($dataItem)."')");
	echo json_encode($dataItem);
?>
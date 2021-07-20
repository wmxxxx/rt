<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$base = base();
	$app = $_POST["app"];
	$data = $_POST["data"];
	$sql = $db -> execute("delete [Things+].[dbo].[TB_Share_Config] where F_App = ".$app." and F_Type = 'monitor_".$base -> version."' insert into [Things+].[dbo].[TB_Share_Config] values('monitor_".$base -> version."',".$app.",'".json_encode(json_decode($data))."')");
	ob_clean();
	echo json_encode(array("errCode" => $sql));
?>

<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	
	session_start();
	$id = $_POST["id"];
	$log = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_id = '".$id."'");
	$sql = "delete [Things+].[dbo].[TB_Share_Model] where F_id = '".$id."';";
	$sql .= "delete [Things+].[dbo].[TB_Share_Relation] where F_cycle_id = '".$id."' or F_action_id = '".$id."';";
	$sql .= "delete [Things+].[dbo].[TB_Share_Action] where F_action_id = '".$id."';";
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','模型删除：".json_encode($log)."')";
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
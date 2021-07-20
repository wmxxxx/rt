<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");
	
	session_start();
	$data = $_POST["data"];
	$sql = "";
	$id = uniqid("a");
	$log = "创建";
	if(isset($_POST["id"])){
		$id = $_POST["id"];
		$sql .= "delete [Things+].[dbo].[TB_Share_Model] where F_id = '".$id."';";
		$sql .= "delete [Things+].[dbo].[TB_Share_Action] where F_action_id = '".$id."';";
		$log = "修改";
	}
	$sql .= "insert into [Things+].[dbo].[TB_Share_Model] values('".$id."','".$data["name"]."','','','action','','',".$_POST["app"].",'".date("Y-m-d H:i:s")."');";
	foreach($data["action"] as $act){
		$sql .= "insert into [Things+].[dbo].[TB_Share_Action] values('".uniqid("m")."','".$id."','".$act["time"]."','".$act["action"]."','".$act["poll"]."','".$act["num"]."','".$act["value"]."');";
	}
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','管理分组".$log."：".json_encode($data)."')";
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
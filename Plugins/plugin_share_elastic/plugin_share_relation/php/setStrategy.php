<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	session_start();
	$sql = '';
	$id = uniqid("r");
	$log = "创建";
	if(isset($_POST["id"])){
		$id = $_POST["id"];
		$sql .= "delete [Things+].[dbo].[TB_Share_Relation] where F_id = '".$id."';";
		$log = "修改";
	}
	$data = $_POST["data"];
	if(!$data["user"])$data["user"] = array();
	$sql .= "insert into [Things+].[dbo].[TB_Share_Relation] values('".$id."','".$data["name"]."','".implode(",",$data["group"])."','".$data["cycle"]."','".$data["action"]."',".$data["open"].",".$data["push"].",'".implode(",",$data["user"])."',".$_POST["app"].");";
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','策略关联".$log."：".json_encode($data)."')";
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
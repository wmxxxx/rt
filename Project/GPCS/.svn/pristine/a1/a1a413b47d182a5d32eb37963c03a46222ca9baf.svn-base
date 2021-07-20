<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	session_start();
	$id = $_POST["id"];
	$node = $_POST["node"];
	// 删除分组下所有设备
	$sql = "delete [Things+].[dbo].[TB_Share_GroupToNode] where F_group_id = '".$id."';";
	// 分组插入传入设备
	if($node != ""){
		foreach(explode(",",$node) as $n){
			$sql .= "insert into [Things+].[dbo].[TB_Share_GroupToNode] values('".$n."','".$id."');";
		}
	}
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','".$id."关联设备修改为".($node == "" ? 0 : count(explode(",",$node)))."台')";
	$result = $db -> multi_query($sql);
	ob_clean();
	echo json_encode($result);
?>
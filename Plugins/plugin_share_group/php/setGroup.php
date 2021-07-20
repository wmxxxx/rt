<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	session_start();
	$log = "创建";
	$sql = '';
	// 有id更新分组信息，没有id创建分组
	if(isset($_POST["id"])){
		$sql = "update [Things+].[dbo].[TB_Share_Group] set F_name = '".$_POST["name"]."',F_code = '".$_POST["num"]."' where F_id = '".$_POST["id"]."'";
		$log = "修改";
	}else{
		$sql = "insert into [Things+].[dbo].[TB_Share_Group] values('".uniqid("g")."','".$_POST["name"]."','".$_POST["num"]."','".date("Y-m-d H:i:s")."','".$_POST["app"]."')";
	}
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','动作模型".$log."：".json_encode($_POST)."')";
	$result = $db -> execute($sql);
	ob_clean();
	echo json_encode($result);
?>
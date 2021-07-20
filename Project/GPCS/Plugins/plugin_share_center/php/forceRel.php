<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	session_start();
	$node = $_POST["node"];
	$group = $db -> query("select * from [Things+].[dbo].[TB_Share_Group] where F_code = 'force' and F_project_code = '".$_POST["app"]."'");
	$group = $group[0];
	$sql = "delete [Things+].[dbo].[TB_Share_GroupToNode] where F_group_id = '".$group -> F_id."';";
	foreach(explode(",",$node) as $n){
		$sql .= "insert into [Things+].[dbo].[TB_Share_GroupToNode] values(".$n.",'".$group -> F_id."');";
	}
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','强制控制关联设备修改为".count(explode(",",$node))."台')";
	$result = $db -> multi_query($sql);
	ob_clean();
	echo json_encode($result);
?>

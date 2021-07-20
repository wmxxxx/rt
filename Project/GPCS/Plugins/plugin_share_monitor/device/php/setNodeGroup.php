<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$groups = $_POST["groups"];
	$group = $db -> query("select * from [Things+].[dbo].[TB_Share_Group] where F_project_code = ".$_POST["app"]." and F_id not in('sys_basic','sys_free')");
	$g_id = array();
	foreach($group as $g){
		array_push($g_id,$g -> F_id);
	}
	$sql = "delete [Things+].[dbo].[TB_Share_GroupToNode] where F_node_id = ".$_POST["id"]." and F_group_id in ('".implode("','",$g_id)."');";
	foreach($groups as $g){
		$sql .= "insert into [Things+].[dbo].[TB_Share_GroupToNode] values(".$_POST["id"].",'".$g."');";
	}
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>

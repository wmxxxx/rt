<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$data = $_POST["data"];
	$sql = "select a.*,(select COUNT(b.F_node_id) from [Things+].[dbo].[TB_Share_GroupToNode] b where a.F_group_id like '%'+b.F_group_id+'%') as num from [Things+].[dbo].[TB_Share_Relation] a where a.F_id not like '%force%' and a.F_app = ".$_POST["app"];
	if($data["group"] != "")$sql .= " and a.F_group_id like '%".$data["group"]."%'";
	if($data["cycle"] != "")$sql .= " and a.F_cycle_id = '".$data["cycle"]."'";
	if($data["action"] != "")$sql .= " and a.F_action_id = '".$data["action"]."'";
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
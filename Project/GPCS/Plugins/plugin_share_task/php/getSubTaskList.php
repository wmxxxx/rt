<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$node = $db -> query("select F_EntityID,F_EntityName from [Things].[dbo].[tb_B_EntityTreeModel] where F_NodeTemplate is not null");
	$name = array();
	foreach($node as $n){
		$name[$n -> F_EntityID] = $n -> F_EntityName;
	}
	$start = ($_POST["page"] - 1) * $_POST["limit"] + 1;
	$end = $_POST["page"] * $_POST["limit"];
	$result = array(
		"code" => 0,
		"msg" => "",
		"count" => count($db -> query("select F_id from [Things+].[dbo].[TB_Share_Log] where F_task_id = '".$_POST["id"]."'")),
		"data" => array()
	);
	$res = $db -> query("select * from (select ROW_NUMBER() over(order by b.F_code,a.F_send) as num,a.*,b.* from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid where a.F_task_id = '".$_POST["id"]."') k where k.num between ".$start." and ".$end." order by k.num");
	foreach($res as $r){
		$r -> F_EntityName = $name[$r -> F_node_id];
	}
	$result["data"] = $res;
	ob_clean();
	echo json_encode($result);
?>
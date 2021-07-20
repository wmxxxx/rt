<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$str = $db -> query("select distinct c.*,c.F_name as name,d.* from [Things+].[dbo].[TB_Share_GroupToNode] a,[Things+].[dbo].[TB_Share_Group] b,[Things+].[dbo].[TB_Share_Relation] c,[Things+].[dbo].[TB_Share_Model] d where a.F_group_id = b.F_id and b.F_project_code = ".$_POST["app"]." and a.F_node_id = ".$_POST["id"]." and c.F_group_id like '%'+a.F_group_id+'%' and c.F_cycle_id = d.F_id and c.F_id not like '%force%'");
	foreach($str as $s){
		$s -> sub = $db -> query("  select a.*,b.F_name from [Things+].[dbo].[TB_Share_Action]a,[Things+].[dbo].[TB_command_interface] b where a.F_command_id = b.F_id and a.F_action_id = '".$s -> F_action_id."' order by a.F_time");
	}
	$result = array(
		"group" => $db -> query("select a.F_id,a.F_name,COUNT(b.F_node_id) as ck from [Things+].[dbo].[TB_Share_Group] a left join [Things+].[dbo].[TB_Share_GroupToNode] b on a.F_id = b.F_group_id and b.F_node_id = ".$_POST["id"]." where a.F_id not like '%force%' and F_project_code = ".$_POST["app"]." group by a.F_id,a.F_name"),
		"strategy" => $str
	);
	ob_clean();
	echo json_encode($result);
?>

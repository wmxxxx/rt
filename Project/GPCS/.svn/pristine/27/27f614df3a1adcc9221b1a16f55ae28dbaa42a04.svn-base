<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");
	
	$app = $_POST["app"];
	$sql = "";
	// 判断是否有强制控制分组
	$group = $db -> query("select * from [Things+].[dbo].[TB_Share_Group] where F_code = 'force' and F_project_code = '".$app."'");
	if(!count($group)){
		$group_id = uniqid("force_");
		$sql .= "insert into [Things+].[dbo].[TB_Share_Group] values('".$group_id."','强制控制','force','".date("Y:m:d H:i:s")."',".$app.");";
	}
	$rel = $db -> query("select * from [Things+].[dbo].[TB_Share_Relation] where F_id like  '%force%' and F_app = '".$app."'");
	if(!count($rel)){
		$cycle_id = uniqid("force_");
		$action_id = uniqid("force_");
		$sql .= "insert into [Things+].[dbo].[TB_Share_Relation] values('".uniqid("force_")."','强制控制','".$group_id."','". $cycle_id."','".$action_id."',1,0,'',".$app.");";
		$sql .= "insert into [Things+].[dbo].[TB_Share_Model] values('".$cycle_id."','强控周期','1970-01-01','2099-12-31','cycle','0',0,'".$app."','".date("Y:m:d H:i:s")."');";
		$sql .= "insert into [Things+].[dbo].[TB_Share_Model] values('".$action_id."','强控动作','08:00:00','17:00:00','action','02:00:00',0,'".$app."','".date("Y:m:d H:i:s")."');";
		$sql .= "insert into [Things+].[dbo].[TB_Share_Action] values('force_am_".$app."','".$action_id."','00:00','3fbc403827abe4f36defb0d1da815743',120,3,'');";
		$sql .= "insert into [Things+].[dbo].[TB_Share_Action] values('force_pm_".$app."','".$action_id."','17:00','3fbc403827abe4f36defb0d1da815743',120,3,'');";
	}
	$db -> query($sql);
	// 获取相关信息
	$time = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_id like '%force%' and F_type = 'action' and F_app = ".$app);
	$qz = array();
	$result = array(
		"qz" => $db -> query("select b.F_node_id from [Things+].[dbo].[TB_Share_Group] a,[Things+].[dbo].[TB_Share_GroupToNode] b where a.F_id like '%force%' and a.F_project_code = '".$app."' and a.F_id = b.F_group_id"),
		"time" => $time[0],
		"tx" => array()
	);
	foreach($result["qz"] as $q){
		array_push($qz,$q -> F_node_id);
	}
	$result["tx"] = $db -> query("select distinct b.F_node_id from [Things+].[dbo].[TB_Share_Relation] a,[Things+].[dbo].[TB_Share_GroupToNode] b where a.F_id not like '%force%' and a.F_group_id like '%'+b.F_group_id+'%' and a.F_app = ".$app." and b.F_node_id not in ('".implode("','",$qz)."')");
	ob_clean();
	echo json_encode($result);
?>

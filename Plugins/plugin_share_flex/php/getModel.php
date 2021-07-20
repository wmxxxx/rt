<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result = array(
		"model" => array(),
		"group" => $db -> query("select a.*,(select COUNT(b.F_node_id) from [Things+].[dbo].[TB_Share_GroupToNode] b where b.F_group_id = a.F_id) as num from [Things+].[dbo].[TB_Share_Group] a where a.F_project_code = ".$_POST["app"]." and F_id not like '%force%' order by F_code")
	);
	$model = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_id like '%flex%' and F_app = ".$_POST["app"]);
	if(count($model)){
		$result["model"] = $model;
	}else{
		$sql = "insert into [Things+].[dbo].[TB_Share_Model] values('".uniqid("flex_")."','','20-26','20-26','temphum','0',0,".$_POST["app"].",'".date("Y-m-d H:i:s")."');";
		$db -> query($sql);
		$result["model"] = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_id like '%flex%' and F_app = ".$_POST["app"]);
	}
	ob_clean();
	echo json_encode($result);
?>
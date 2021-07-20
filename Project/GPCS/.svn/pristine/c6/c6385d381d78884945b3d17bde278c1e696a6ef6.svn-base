<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	// 获取系统所有分组，过滤强制控制分组
	$result = $db -> query("select a.*,(select COUNT(b.F_node_id) from [Things+].[dbo].[TB_Share_GroupToNode] b where b.F_group_id = a.F_id) as num from [Things+].[dbo].[TB_Share_Group] a where a.F_project_code = ".$_POST["app"]." and F_id not like '%force%' order by F_code");
	ob_clean();
	echo json_encode($result);
?>
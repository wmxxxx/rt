<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result = $db -> query("select a.*,b.F_EntityName from [Things+].[dbo].[TB_Share_Log] a,[Things].[dbo].[tb_B_EntityTreeModel] b where a.F_node_id = b.F_EntityID and a.F_task_id = '".$_POST["id"]."'");
	ob_clean();
	echo json_encode($result);
?>
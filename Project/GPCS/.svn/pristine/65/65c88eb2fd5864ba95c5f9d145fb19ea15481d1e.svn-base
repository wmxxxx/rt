<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	// 获取分组下所有设备
	$result = $db -> query("select * from [Things+].[dbo].[TB_Share_GroupToNode] where F_group_id = '".$_POST["id"]."'");
	ob_clean();
	echo json_encode($result);
?>
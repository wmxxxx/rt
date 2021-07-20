<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$sub = $db -> query("select F_id from [Things+].[dbo].[TB_Share_Log] where F_task_id = '".$_POST["id"]."'");
	$sub_id = array();
	foreach($sub as $s){
		array_push($sub_id,$s -> F_id);
	}
	$result = $db -> query("delete [Things+].[dbo].[TB_Share_Task] where F_id = '".$_POST["id"]."';delete [Things+].[dbo].[TB_Share_Log] where F_task_id = '".$_POST["id"]."';delete [Things+].[dbo].[TB_Share_Back] where F_bid in ('".implode("','",$sub_id)."')");
	ob_clean();
	echo json_encode($result);
?>
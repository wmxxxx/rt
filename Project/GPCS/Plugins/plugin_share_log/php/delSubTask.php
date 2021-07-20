<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result = $db -> query("delete [Things+].[dbo].[TB_Share_Log] where F_id in ('".implode("','",$_POST["id"])."');delete [Things+].[dbo].[TB_Share_Back] where F_bid in ('".implode("','",$_POST["id"])."');");
	ob_clean();
	echo json_encode($result);
?>
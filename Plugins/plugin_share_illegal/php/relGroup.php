<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");
	
	$result = $db -> query("update [Things+].[dbo].[TB_Share_Model] set F_name = '".$_POST["group"]."' where F_id = '".$_POST["id"]."'");
	ob_clean();
	echo json_encode($result);
?>
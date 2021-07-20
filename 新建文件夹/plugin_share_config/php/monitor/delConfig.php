<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$base = base();
	$result = $db -> query("delete [Things+].[dbo].[TB_Share_Config] where F_Type = 'monitor_".$base -> version."' and F_App = '".$_GET["app"]."'");
	ob_clean();
	echo 1;
?>
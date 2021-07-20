<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$base = base();
	$result = $db -> query("select F_Config from [Things+].[dbo].[TB_Share_Config] where F_Type = 'map_".$base -> version."' and F_App =".$_POST["app"]);
	ob_clean();
	echo $result ? $result[0] -> F_Config : 0;
?>
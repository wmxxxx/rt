<?php
	header("Content:text/html;charset=utf-8;");
	include_once(dirname(dirname(__FILE__))."/share.php");

	$base = base();
	// 获取系统配置信息，没有则加载默认配置
	$result = $db -> query("select F_Config from [Things+].[dbo].[TB_Share_Config] where F_App =".$_POST["app"]." and F_Type = 'monitor_".$base -> version."'");
	$result = count($result) ? $result[0] -> F_Config : checkConfig($_POST["app"]);
	ob_clean();
	echo $result;
?>

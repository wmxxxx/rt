<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$base = base();
	// 获取基础配置信息，没有则加载默认配置
	$result = '';
	if(isset($_POST["data"])){
		$db -> query("update [Things+].[dbo].[TB_Share_Config] set F_Config = '".json_encode($_POST["data"])."' where F_Type = 'base_".$base -> version."' and F_App = 0");
		$result = "已更新";
	}else{
		$result = checkBase();
	}
	ob_clean();
	echo $result;
?>
<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	// 获取系统的所有模型
	$result = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_app = ".$_POST["app"]." and F_id not like '%force%' order by F_time");
	ob_clean();
	echo json_encode($result);
?>
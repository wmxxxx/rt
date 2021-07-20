<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$result = array(
		command => $db -> query("select * from [Things+].[dbo].[TB_command_interface] order by F_remark"),
		system => $db -> query("select F_ProjectNo,F_ProjectName from [Things].[dbo].[tb_A_Project]")
	);
	ob_clean();
	echo json_encode($result);
?>

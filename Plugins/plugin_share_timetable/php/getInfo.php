<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result = $db -> query("select * from [Things+].[dbo].[TB_Common_TimeTable] where F_nodeid in 
(".$_POST["node"].") and F_date >= '".$_POST["sdate"]."' and F_date <= '".$_POST["edate"]."' order by F_nodeid,F_date");
	ob_clean();
	echo json_encode($result);
?>

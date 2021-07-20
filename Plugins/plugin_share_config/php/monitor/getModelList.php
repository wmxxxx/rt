<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$result = $db -> query("select * from [Things].[dbo].[tb_B_EntityTreeType]");
	ob_clean();
	echo json_encode($result);
?>

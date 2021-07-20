<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$result = array(
		"run" => $db -> query("select * from [Things+].[dbo].[TB_Share_RunningState] where F_App = '".$_POST["app"]."' and F_NodeID = '".$_POST["nodeid"]."' and F_Date >= '".$_POST["start"]."' and F_Date <= '".$_POST["end"]."' and F_Tab =".$_POST["tab"]." order by F_Date"),
		"time" => $db -> query("select * from dbo.fun_GetMonthWorkTime(".date('Y').",".date('m').")")
	);
	$result["time"] = $result["time"][0];
	ob_clean();
	echo json_encode($result);
?>

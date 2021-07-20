<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");
	
	session_start();
	$app = $_POST["app"];
	$start = explode(":",$_POST["start"]);
	$end = explode(":",$_POST["end"]);
	$lx = explode(":",$_POST["lx"]);
	$sTime = $start[0]*60 + $start[1]*1;
	$eTime = $end[0]*60 + $end[1]*1;
	$lTime = $lx[0]*60 + $lx[1]*1;
	$sql = "update [Things+].[dbo].[TB_Share_Model] set F_start = '".$_POST["start"]."',F_end = '".$_POST["end"]."',F_type_val = '".$_POST["lx"]."' where F_id like '%force%' and F_type = 'action' and F_app = ".$app.";";
	$sql .= "update [Things+].[dbo].[TB_Share_Action] set F_poll_time = ".$lTime.",F_poll_num = ".intval($sTime/$lTime)." where F_id = 'force_am_".$app."';";
	$sql .= "update [Things+].[dbo].[TB_Share_Action] set F_time = '".$end[0].":".$end[1]."',F_poll_time = ".$lTime.",F_poll_num = ".intval((1440-$eTime)/$lTime)." where F_id = 'force_pm_".$app."';";
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','强制控制时段修改为开始时间：".$_POST["start"]."结束时间：".$_POST["end"]."轮询间隔：".$_POST["lx"]."')";
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>

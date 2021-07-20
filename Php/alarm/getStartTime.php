<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select convert(varchar(10),MIN(F_DateTime),120) as F_StartTime from dbo.tb_A_Event a,dbo.tb_A_EventToUser b where b.F_PushUser = " . $_SESSION['user']['code'] . " and b.F_ACKStatus = 0 and a.F_EventCode = b.F_EventCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

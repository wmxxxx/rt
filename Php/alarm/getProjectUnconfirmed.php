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
    
	$project = $_POST["project"];
	$sql = "select b.F_Rank,COUNT(a.F_EventCode) as F_UnconfirmedNum from dbo.tb_A_EventToUser a,dbo.tb_A_Event b where a.F_PushUser = " . $_SESSION['user']['code'] . " AND a.F_ACKStatus = 0  and b.F_ProjectNo = " . $project . " and a.F_EventCode = b.F_EventCode group by b.F_Rank";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

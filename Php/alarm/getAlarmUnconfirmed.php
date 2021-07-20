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
    
	$sql = "SELECT COUNT(F_EventCode) AS F_UnconfirmedNum FROM dbo.tb_A_EventToUser WHERE F_PushUser = " . $_SESSION['user']['code'] . " AND F_ACKStatus = 0";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

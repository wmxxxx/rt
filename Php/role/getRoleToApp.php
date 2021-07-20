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
    
	$role = $_POST["role"];
	$sql = "select a.F_AppNo,b.F_ProjectName,b.F_AppType from dbo.tb_A_RoleToApp a,(select F_ProjectNo,F_ProjectName,'1' as F_AppType from tb_A_Project union select F_AgentCode,F_AgentName,'2' as F_AppType from tb_A_Agent) b where a.F_RoleCode=" . $role . " and a.F_AppNo = b.F_ProjectNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

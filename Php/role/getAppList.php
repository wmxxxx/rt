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
    
	$sql = "select F_ProjectNo,F_ProjectAbbr,F_ProjectColor,F_ProjectTag,'1' as F_ProjectType from dbo.tb_A_Project union select F_AgentCode,F_AgentAbbr,F_ProjectColor,F_ProjectTag,'2' as F_ProjectType from dbo.tb_A_Agent";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

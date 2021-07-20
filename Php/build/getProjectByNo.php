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
	$sql = "select F_ProjectNo,F_ProjectName,F_ProjectAbbr,F_ProjectTag,F_ProjectFrame from (select F_ProjectNo,F_ProjectName,F_ProjectAbbr,F_ProjectTag,F_ProjectFrame from dbo.tb_A_Project union select F_AgentCode,F_AgentName,F_AgentAbbr,'','' from dbo.tb_A_Agent) t where F_ProjectNo=" . $project;
	$result = $db -> query($sql);
	echo json_encode($result);
?>

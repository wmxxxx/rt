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
	
	$sql = "select distinct a.F_ProjectNo,a.F_ProjectName,a.F_ProjectTag,c.F_FunctionTag from dbo.tb_A_Project a,dbo.tb_A_ProjectToMenu b,dbo.tb_A_Function c,dbo.tb_A_Plugins d where d.F_PluginTag = 'plugin_alarm_block' and c.F_PluginCode = d.F_PluginCode and c.F_FunctionCode = b.F_FunctionCode and b.F_ProjectNo = a.F_ProjectNo order by a.F_ProjectNo";
	$result = $db -> query($sql);
    
	echo json_encode($result);
?>

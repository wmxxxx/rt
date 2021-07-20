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
	$sql = "select dbo.fun_GetFunctionToTree(c.F_PluginCode,b.F_FunctionCode) as F_TreeNo,b.F_FunctionCode,c.F_PluginCode,c.F_PluginTag,c.F_GuideMode,c.F_IsConfig,c.F_ConfType,c.F_EnergyConfig,c.F_TemplateConfig,c.F_DeviceConfig,dbo.fun_GetFunctionEnvVar(b.F_FunctionCode) as F_EnvVar,d.F_MenuCode,d.F_MenuTag,d.F_MenuType,d.F_MenuPosition,d.F_IsHasChild from tb_A_Project a,dbo.tb_A_Function b,tb_A_Plugins c,tb_A_ProjectToMenu d where a.F_ProjectNo = '" . $project . "' and a.F_GuideFunction = b.F_FunctionCode and b.F_PluginCode = c.F_PluginCode and a.F_ProjectNo = d.F_ProjectNo and a.F_GuideFunction = d.F_FunctionCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

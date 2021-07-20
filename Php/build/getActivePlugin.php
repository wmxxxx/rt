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
	$tag = $_POST["tag"];
	$sql = "select a.F_MenuCode,a.F_MenuTag,a.F_MenuType,a.F_MenuPosition,a.F_IsHasChild,dbo.fun_GetFunctionToTree(c.F_PluginCode,b.F_FunctionCode) as F_TreeNo,b.F_FunctionCode,c.F_PluginCode,c.F_PluginTag,c.F_GuideMode,c.F_IsConfig,c.F_ConfType,c.F_EnergyConfig,c.F_TemplateConfig,c.F_DeviceConfig,dbo.fun_GetFunctionEnvVar(b.F_FunctionCode) as F_EnvVar from tb_A_ProjectToMenu a,dbo.tb_A_Function b,tb_A_Plugins c where a.F_ProjectNo = '" . $project . "' and b.F_FunctionTag = '" . $tag . "' and a.F_FunctionCode = b.F_FunctionCode and b.F_PluginCode = c.F_PluginCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

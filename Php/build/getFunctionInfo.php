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
	
	$pro = $_POST["pro"];
	$fun = $_POST["fun"];
	$sql = "select a.F_MenuCode,a.F_MenuTag,a.F_MenuPosition,a.F_FunctionCode,a.F_IsHasChild,c.F_PluginCode,c.F_PluginTag,c.F_GuideMode,dbo.fun_GetFunctionToTree(C.F_PluginCode,A.F_FunctionCode) AS F_TreeNo,dbo.fun_GetFunctionEnvVar(a.F_FunctionCode) as F_EnvVar from dbo.tb_A_ProjectToMenu a left outer join dbo.tb_A_Function b on a.F_FunctionCode = b.F_FunctionCode left outer join dbo.tb_A_Plugins c on b.F_PluginCode = c.F_PluginCode where a.F_ProjectNo = '" . $pro . "' and b.F_FunctionTag = '" . $fun . "' ";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

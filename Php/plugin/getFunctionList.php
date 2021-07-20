<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $type = $_POST["type"];
    if($type == ''){
        $sql = "select a.F_FunctionCode,a.F_FunctionName,a.F_FunctionTag,c.F_IsConfig,c.F_ConfType,c.F_EnergyConfig,c.F_TemplateConfig,c.F_DeviceConfig,c.F_GuideMode,b.F_FunctionTypeNo,b.F_FunctionTypeTag,b.F_FunctionTypeName,c.F_PluginCode,c.F_PluginName,c.F_PluginTag,dbo.fun_GetFunctionEnvVar(a.F_FunctionCode) as F_EnvVar,dbo.fun_GetFunctionToTree(c.F_PluginCode,a.F_FunctionCode) as F_TreeNo from tb_A_Function a,dbo.tb_A_FunctionType b,dbo.tb_A_Plugins c where a.F_FunctionTypeNo = b.F_FunctionTypeNo and a.F_PluginCode = c.F_PluginCode order by b.F_FunctionTypeNo,a.F_CreateDate";
    }else{
	    $sql = "select a.F_FunctionCode,a.F_FunctionName,a.F_FunctionTag,c.F_IsConfig,c.F_ConfType,c.F_EnergyConfig,c.F_TemplateConfig,c.F_DeviceConfig,c.F_GuideMode,b.F_FunctionTypeNo,b.F_FunctionTypeTag,b.F_FunctionTypeName,c.F_PluginCode,c.F_PluginName,c.F_PluginTag,dbo.fun_GetFunctionEnvVar(a.F_FunctionCode) as F_EnvVar,dbo.fun_GetFunctionToTree(c.F_PluginCode,a.F_FunctionCode) as F_TreeNo from tb_A_Function a,dbo.tb_A_FunctionType b,dbo.tb_A_Plugins c where (a.F_FunctionTypeNo='" . $type . "' or cast(a.F_PluginCode as varchar) = '" . $type . "' or cast(a.F_FunctionCode as varchar) = '" . $type . "') and  a.F_FunctionTypeNo = b.F_FunctionTypeNo and a.F_PluginCode = c.F_PluginCode order by b.F_FunctionTypeNo,a.F_CreateDate";
    }
	$result = $db -> query($sql);
	echo json_encode($result);
?>

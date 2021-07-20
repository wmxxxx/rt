<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$plugin = $_POST["plugin"];
    if($plugin == ''){
        $sql = "select F_PluginCode,F_PluginName,F_PluginTag,F_PluginCategory,F_GuideMode,F_PluginTypeNo,b.F_FunctionTypeTag as F_PluginTypeTag,b.F_FunctionTypeName as F_PluginTypeName,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,dbo.fun_GetPluginFunCount(F_PluginCode) as F_FunCount from tb_A_Plugins a,dbo.tb_A_FunctionType b where a.F_PluginTypeNo = b.F_FunctionTypeNo order by F_PluginTypeNo,F_PluginCode";
    }else if(strlen($plugin) == 1){
        $sql = "select F_PluginCode,F_PluginName,F_PluginTag,F_PluginCategory,F_GuideMode,F_PluginTypeNo,b.F_FunctionTypeTag as F_PluginTypeTag,b.F_FunctionTypeName as F_PluginTypeName,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,dbo.fun_GetPluginFunCount(F_PluginCode) as F_FunCount from tb_A_Plugins a,dbo.tb_A_FunctionType b where F_PluginTypeNo='" . $plugin . "' and a.F_PluginTypeNo = b.F_FunctionTypeNo order by F_PluginTypeNo,F_PluginCode";
    }else{
        $sql = "select F_PluginCode,F_PluginName,F_PluginTag,F_PluginCategory,F_GuideMode,F_PluginTypeNo,b.F_FunctionTypeTag as F_PluginTypeTag,b.F_FunctionTypeName as F_PluginTypeName,F_IsConfig,F_ConfType,F_EnergyConfig,F_TemplateConfig,F_DeviceConfig,dbo.fun_GetPluginFunCount(F_PluginCode) as F_FunCount from tb_A_Plugins a,dbo.tb_A_FunctionType b where F_PluginCode=" . $plugin . " and a.F_PluginTypeNo = b.F_FunctionTypeNo order by F_PluginTypeNo,F_PluginCode";
    }
	$result = $db -> query($sql);
	echo json_encode($result);
?>

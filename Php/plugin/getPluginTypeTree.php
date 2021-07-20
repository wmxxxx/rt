<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select 1 as rank,F_FunctionTypeNo as type,'' as tag,F_FunctionTypeNo + '_' + F_FunctionTypeTag as id,F_FunctionTypeName as name,'0' as pId,0 as hasfun from tb_A_FunctionType union select 2 as rank,a.F_PluginTypeNo as type,a.F_PluginTag as tag,cast(a.F_PluginCode as varchar) as id,a.F_PluginName as name,a.F_PluginTypeNo + '_' + b.F_FunctionTypeTag as pId,dbo.fun_PluginIsMappingFun(a.F_PluginCode) as hasfun from tb_A_Plugins a,dbo.tb_A_FunctionType b where a.F_PluginTypeNo = b.F_FunctionTypeNo order by pId,type,id";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

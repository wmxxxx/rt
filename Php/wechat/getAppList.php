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
    
	$sql = "select a.F_AppCode,a.F_AppName,a.F_AppTag,a.F_AppType,b.F_FunctionTypeNo,cast(b.F_FunctionCode as varchar) + '-' + c.F_PluginTag as F_FunctionCode from tb_A_MobileApp a left outer join tb_A_Function b on a.F_FunctionCode = b.F_FunctionCode left outer join tb_A_Plugins c on b.F_PluginCode = c.F_PluginCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

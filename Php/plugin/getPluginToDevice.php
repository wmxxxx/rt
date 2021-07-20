<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$fun = $_POST["fun"];
	$sql = "select F_GroupID as id,F_GroupName as name,0 as pId,case when b.F_DeviceCode is null then 0 else 1 end as checked from dbo.tb_B_DictTreeModel a left outer join tb_A_PluginToDevice b on b.F_FunctionCode = '" . $fun . "' and a.F_GroupID = b.F_DeviceCode where a.F_ObjectGroup = '2' or F_ObjectGroup = '3' ";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

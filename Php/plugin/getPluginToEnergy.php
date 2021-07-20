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
	
	$fun = $_POST["fun"];
    $sql = "select a.F_EntityID as id,a.F_EntityName as name,a.F_ParentID as pId,case when b.F_EnergyCode is null then 0 else 1 end as checked from dbo.tb_B_EntityTreeModel a left outer join dbo.tb_A_PluginToEnergy b on b.F_FunctionCode = " . $fun . " and b.F_EnergyCode = a.F_EntityID where a.F_EntityTreeNo in (select F_EntityTreeNo from dbo.tb_B_EntityTreeType where F_EntityTreeType = '3') and (a.F_EntityDepth=1 or a.F_EntityDepth=2)";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

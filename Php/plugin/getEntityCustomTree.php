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
	$plugin = $_POST["plugin"];
    $sql = "select a.F_ObjectGroup as type,dbo.fun_GetNodeAorVType(a.F_EntityID) as av,a.F_EntityID as id,a.F_EntityName as name,a.F_ParentID as pId,case when b.F_EntityID is null then 0 else 1 end as checked from dbo.tb_A_PluginToTree t,dbo.tb_B_EntityTreeModel a left outer join tb_A_PluginToEntity b on b.F_FunctionCode=" . $fun . " and b.F_PluginCode = " . $plugin . " and a.F_EntityTreeNo = b.F_EntityTreeNo and a.F_EntityID = b.F_EntityID where t.F_FunctionCode=" . $fun . " and t.F_PluginCode = " . $plugin . " and t.F_EntityTreeNo = a.F_EntityTreeNo order by a.F_EntityDepth,a.F_OrderTag";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_POST["tree"];
    $fun = $_POST["fun"];
	$sql = "select a.F_EntityID as id,isnull(a.F_ObjectGroup,'') as otype,a.F_EntityName as name,a.F_ParentID as pId,case when b.F_EntityID is null then 0 else 1 end as checked from tb_B_EntityTreeModel a left outer join tb_A_PluginToEntity b on b.F_FunctionCode=" . $fun . " and a.F_EntityTreeNo=b.F_EntityTreeNo and a.F_EntityID=b.F_EntityID where a.F_EntityTreeNo='" . $tree . "' order by a.F_EntityDepth,a.F_OrderTag";
	$result = $db -> query($sql);
    
    echo json_encode($result);
?>

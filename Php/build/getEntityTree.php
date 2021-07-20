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
	
	$tree = $_POST["tree"];
	$no = $_POST["no"];
    $sql = "select dbo.fun_GetEntityTreeTypeByNo(a.F_EntityTreeNo) as tType,dbo.fun_GetNodeAorVType(a.F_EntityID) as av,a.F_EntityID as id,a.F_EntityName as name,a.F_ParentID as pId,case when b.F_EntityID is null then 0 else 1 end as checked,a.F_ObjectGroup as type from dbo.tb_B_EntityTreeModel a left outer join tb_A_ProjectToTree b on b.F_ProjectNo = " . $no . " and a.F_EntityTreeNo = b.F_EntityTreeNo and a.F_EntityID = b.F_EntityID where a.F_EntityTreeNo = " . $tree . " order by pId";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

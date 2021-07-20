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
	$role = $_POST["role"];
    $sql = "";
    if($role == ""){
        $sql = "select F_ObjectGroup as type,dbo.fun_GetNodeAorVType(F_EntityID) as av,F_EntityID as id,F_EntityName as name,F_ParentID as pId,0 as checked from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = '" . $tree . "' order by pId";
    }else{
        $sql = "select F_ObjectGroup as type,dbo.fun_GetNodeAorVType(a.F_EntityID) as av,a.F_EntityID as id,a.F_EntityName as name,a.F_ParentID as pId,case when b.F_EntityID is null then 0 else 1 end as checked from dbo.tb_B_EntityTreeModel a left outer join tb_A_RoleToTree b on b.F_RoleCode=" . $role . " and a.F_EntityTreeNo = b.F_EntityTreeNo and a.F_EntityID = b.F_EntityID where a.F_EntityTreeNo = '" . $tree . "' order by pId";
    }
	$result = $db -> query($sql);
	echo json_encode($result);
?>

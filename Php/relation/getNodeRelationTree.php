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
    $sql = "select dbo.fun_GetEntityTreeTypeByNo(F_EntityTreeNo) as tType,dbo.fun_GetNodeAorVType(F_EntityID) as av,F_EntityID as id,F_EntityName as name,F_ParentID as pId from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = '" . $tree . "' order by pId";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

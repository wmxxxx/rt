<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_GET["tree"];

	$sql = "select cast(F_DictTreeNo as varchar)+'-'+cast(F_GroupID as varchar)+'-'+cast(isnull(F_ObjectTypeID,0) as varchar)+'-'+F_ObjectGroup as F_GroupID,F_GroupName,F_IsHasChild from tb_B_DictTreeModel where F_DictTreeNo='" . $tree . "' and F_ParentID='0' order by F_ObjectGroup,F_GroupID";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

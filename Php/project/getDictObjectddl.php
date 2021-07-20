<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$type = $_POST["type"];
	$sql = "select F_GroupID,dbo.fun_GetDictFullName(F_GroupID,1) as F_GroupName from dbo.tb_B_DictTreeModel where F_DictTreeNo = " . $type;
	$result = $db -> query($sql);
	echo json_encode($result);
?>

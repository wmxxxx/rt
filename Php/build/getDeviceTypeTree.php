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
	
    $sql = "select a.F_GroupID as id,a.F_GroupName as name,0 as pId from dbo.tb_B_DictTreeModel a where a.F_ObjectGroup = '2' or F_ObjectGroup = '3'";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

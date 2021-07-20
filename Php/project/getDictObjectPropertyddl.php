<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$group = $_POST["group"];
	$sql = "select a.F_PropertyID,b.F_PropertyName from dbo.tb_B_DictTreeProperty a,dbo.tb_B_ObjectProperty b where a.F_GroupID = " . $group . " and a.F_PropertyID = b.F_PropertyID order by F_OrderNum";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

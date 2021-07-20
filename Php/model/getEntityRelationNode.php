<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$groupTree = $_POST["groupTree"];
	$group = $_POST["group"];
	$nodeTree = $_POST["nodeTree"];
	$sql = "select cast(F_DeviceTreeNo as varchar)+'-'+cast(F_DeviceID as varchar) as F_DeviceID from tb_B_EntityTreeToDevice where F_EntityTreeNo = '" . $groupTree . "' and F_EntityID = '" . $group . "' and F_DeviceTreeNo = '" . $nodeTree . "'";
    $result = $db -> query($sql);
	echo json_encode($result);
?>

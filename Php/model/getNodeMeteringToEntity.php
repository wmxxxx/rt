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
	$nodeTree = $_POST["nodeTree"];
	$node = $_POST["node"];
    $sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar) as F_EntityID from tb_B_EntityTreeMetering where F_EntityTreeNo = '" . $groupTree . "' and F_NodeTreeNo = '" . $nodeTree . "' and F_NodeID = '" . $node . "' and F_EndDate is null";
    $result = $db -> query($sql);
	echo json_encode($result);
?>

<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$groupTree = $_POST["groupTree"];
	$group = $_POST["group"];
	$nodeTree = $_POST["nodeTree"];
	$nodeSql = $_POST["nodeSql"];
	$start = $_POST["start"];
	$end = $_POST["end"];
	$user = $_POST["user"];
    $sql = "exec proc_B_EntityTreeMetering '" . $groupTree . "','" . $group . "','" . $nodeTree . "','" . $nodeSql . "','" . $start . "','" . $end . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

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
	$nodeTree = $_POST["nodeTree"];
	$node = $_POST["node"];
	$groupSql = $_POST["groupSql"];
	$user = $_POST["user"];
    $sql = "exec proc_B_NodeMeteringToEntity '" . $groupTree . "','" . $nodeTree . "','" . $node . "','" . $groupSql . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

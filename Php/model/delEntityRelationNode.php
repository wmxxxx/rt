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
	$node = $_POST["node"];
	$user = $_POST["user"];
    $sql = "exec proc_B_DelEntityRelationNode '" . $groupTree . "','" . $group . "','" . $node . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

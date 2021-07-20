<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$treeNo = $_GET["treeNo"];
	$drop = $_GET["drop"];
	$target = $_GET["target"];
	$user = $_GET["user"];
    $sql = "exec proc_B_EntityTreeNodeDrog '" . $treeNo . "','" . $drop . "','" . $target . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

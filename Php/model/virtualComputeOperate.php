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

	$ncode = $_POST["ncode"];
	$vlabel = $_POST["vlabel"];
	$ftext = $_POST["ftext"];
	$fhtml = $_POST["fhtml"];
	$nv = $_POST["nv"];
	$type = $_POST["type"];
	$user = $_POST["user"];
    $sql = "exec proc_A_VirtualComputeOperate '" . $ncode . "','" . $vlabel . "','" . $ftext . "','" . $fhtml . "','" . $nv . "','" . $type . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

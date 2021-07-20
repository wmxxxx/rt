<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2017-07-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$project = $_POST["project"];
	$device = $_POST["device"];
	$param = $_POST["param"];
    $sql = "exec proc_A_ProjectToDParam '" . $project . "','" . $device . "','" . $param . "'";
    echo json_encode($db -> execute($sql));
?>

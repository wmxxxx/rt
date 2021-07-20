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
	
	$sys = $_POST["sys"];
	$frame = $_POST["frame"];
    $sql = "update tb_A_Project set F_ProjectFrame = " . $frame . " where F_ProjectNo = " . $sys;
    echo json_encode($db -> execute($sql));
?>

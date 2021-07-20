<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $fun = $_POST["fun"];
    $plugin = $_POST["plugin"];
    $tree = $_POST["tree"];
    $name = $_POST["name"];

	$sql = "exec proc_B_RenameEntityTree " . $fun . "," . $plugin . "," . $tree . ",'" . $name . "'";
	echo json_encode($db -> execute($sql));
?>

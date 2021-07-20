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
    
	$fun = $_POST["fun"];
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
    $sql = "exec proc_A_CustomToTree " . $fun . "," . $plugin . ",'" . $tree . "'";
    echo json_encode($db -> execute($sql));
?>

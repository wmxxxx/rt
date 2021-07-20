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
	
	$fun = $_POST["fun"];
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
	$tId = $_POST["tId"];
	$pId = $_POST["pId"];
    
    $sql = "exec proc_A_PluginToCustom " . $fun . "," . $plugin . "," . $tree . "," . $tId . ",'" . $pId . "'";
    echo json_encode($db -> execute($sql));
?>

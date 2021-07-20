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
	
	$d_type = $_POST["d_type"];
	$fun = $_POST["fun"];
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
    
    $sql = "exec proc_A_PluginToDevice " . $fun . "," . $plugin . "," . $tree . "," . $d_type;
    echo json_encode($db -> execute($sql));
?>

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
    
    $tree = $_POST["tree"];
	$result = $db -> query("exec proc_A_GetNodeRelationSum " . $tree);
    echo json_encode($result);
?>

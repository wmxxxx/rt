<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_POST["tree"];
    $fun = $_POST["fun"];

	$sql = "select F_EntityTreeRename from tb_A_PluginToEntityRename where F_FunctionCode=" . $fun . " and F_EntityTreeNo=" . $tree;
	$result = $db -> query($sql);
	echo json_encode($result);
?>

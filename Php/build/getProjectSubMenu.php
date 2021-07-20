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
	
	$project = $_POST["project"];
	$menu = $_POST["menu"];
	$sql = "exec proc_A_GetUserProjectMenu " . $project . "," . $_SESSION['user']['code'] . ",'" . $menu . "','2'";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

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
    
	$role = $_POST["role"];
	$fun = $_POST["fun"];
	$user = $_POST["user"];
    $sql = "exec proc_A_RoleToFun '" . $role . "','" . $fun . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

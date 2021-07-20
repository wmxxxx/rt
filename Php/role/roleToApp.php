<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$type = $_POST["type"];
	$role = $_POST["role"];
	$app_no = $_POST["app_no"];
	$app_type = $_POST["app_type"];
	$user = $_POST["user"];
    $sql = "exec proc_A_RoleToApp '" . $type . "','" . $role . "'," . $app_no . "," . $app_type . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

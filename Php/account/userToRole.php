<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
    $code = $_POST["code"];
	$role = $_POST["role"];
    $user = $_POST["user"];
    $sql = "exec proc_A_UserToRole " . $code . "," . ($role == 0 ? 'null' : $role) . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    echo json_encode($db -> execute($sql));
?>

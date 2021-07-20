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
	
	$type = $_POST["type"];
	$user = $_POST["user"];
	
	$sql = "select * from dbo.tb_A_BookInfo where F_TypeNo =" . $type . " and F_MakeUser='" . $user . "'";
    echo json_encode($db -> query($sql));
?>

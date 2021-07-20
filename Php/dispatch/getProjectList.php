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
	
	$sql = "select a.F_ProjectNo,a.F_ProjectName from dbo.tb_A_Project a";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

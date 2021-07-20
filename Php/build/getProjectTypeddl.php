<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select distinct F_ProjectType from dbo.tb_A_Project";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_TypeNo as value,F_TypeName as name from tb_A_LogType order by F_TypeNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

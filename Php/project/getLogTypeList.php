<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$type = $_POST["type"];
    $sql = "select F_TypeNo,F_TypeName from tb_A_EventType where F_LTypeNo like'%" . $type . "%'";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

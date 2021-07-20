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
	
	$sys = $_POST["sys"];
	$sql = "select a.F_FunctionCode,a.F_FunctionName from tb_A_Function a,dbo.tb_A_ProjectToMenu b where b.F_ProjectNo='" . $sys . "' and a.F_FunctionCode = b.F_FunctionCode order by F_FunctionCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

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
	
	$fun = $_POST["fun"];
    $sql = "select cast(a.F_EntityTreeNo as varchar) + '-' + b.F_EntityTreeType as F_EntityTreeNo from dbo.tb_A_PluginToEntity a,tb_B_EntityTreeType b where a.F_FunctionCode=" . $fun . " and a.F_EntityTreeNo = b.F_EntityTreeNo";
    $result = $db -> query($sql);
	echo json_encode($result);
?>

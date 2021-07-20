<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$plugin = $_POST["plugin"];
	$var = $_POST["key"];
	$sql = "select a.F_FunctionCode,a.F_FunctionName,isnull(b.F_EnvVarValue,'') as F_EnvVarValue from dbo.tb_A_Function a left outer join dbo.tb_A_FunctionEnvVar b on a.F_FunctionCode = b.F_FunctionCode and b.F_EnvVarKey = '" . $var . "' where a.F_PluginCode = " . $plugin;
	$result = $db -> query($sql);
	echo json_encode($result);
?>

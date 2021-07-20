<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$app = $_POST["app"];
	$tpl = $_POST["tpl"];
	$sql = "select a.F_TemplateName,b.F_ValueCode,b.F_ValueName,b.F_ValueLabel from dbo.tb_A_Template a,dbo.tb_A_Value b where a.F_AppCode = '" . $app . "' and a.F_TemplateID = '" . $tpl . "' and a.F_TemplateCode = b.F_TemplateCode and b.F_IsRefer = 1";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

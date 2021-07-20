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
    $sql = "select a.F_TemplateCode as id,a.F_TemplateName as name,0 as pId,case when b.F_TemplateCode is null then 0 else 1 end as checked from dbo.tb_A_Template a left outer join dbo.tb_A_PluginToTemplate b on b.F_FunctionCode = " . $fun . " and b.F_TemplateCode = a.F_TemplateCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

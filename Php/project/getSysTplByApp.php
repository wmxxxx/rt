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
	$sql = "select F_TemplateID,F_TemplateName from tb_A_Template where F_IsRefer = 1 and F_AppCode='" . $app . "'";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

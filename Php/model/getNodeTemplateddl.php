<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_TemplateCode,F_TemplateName from tb_A_Template";
	$result = $db -> query($sql);
    $resArray = array();
    array_push($resArray,array('无',''));
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_TemplateName,$obj -> F_TemplateCode));
    }
	echo json_encode($resArray);
?>

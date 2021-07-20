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
	
	$group = $_POST["group"];
    $sql = "select a.F_PropertyID,a.F_PropertyName from dbo.tb_B_ObjectProperty a,dbo.tb_B_DictTreeProperty b where b.F_GroupID = " . $group . " and b.F_PropertyID = a.F_PropertyID and a.F_DictionaryType in ('1','2')";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

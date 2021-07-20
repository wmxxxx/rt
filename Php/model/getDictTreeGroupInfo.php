<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_GET["tree"];
    $group = $_GET["group"];

	$sql = "select F_GroupName as text_GroupName,F_GroupTag as text_GroupTag,F_ObjectGroup as rdo_ObjectGroup,F_ObjectTypeID as cmb_ObjectTypeList from tb_B_DictTreeModel where F_DictTreeNo='" . $tree . "' and F_GroupID='" . $group . "'";
	$result = $db -> query($sql);
    
    $resObj = new stdClass();
    $resObj -> success = true;
    $resObj -> data = $result[0];
	echo json_encode($resObj);
?>

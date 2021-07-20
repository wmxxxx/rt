<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$groupTree = $_POST["groupTree"];
	$group = $_POST["group"];
	$nodeTree = $_POST["nodeTree"];
	$sql = "select distinct cast(F_StartDate as varchar) as F_StartDate,isnull(cast(F_EndDate as varchar),'') as F_EndDate from tb_B_EntityTreeMetering where F_EntityTreeNo = '" . $groupTree . "' and F_EntityID = '" . $group . "' and F_NodeTreeNo = '" . $nodeTree . "' order by F_StartDate desc";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

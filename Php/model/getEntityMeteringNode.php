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
	$start = $_POST["start"];
	$end = $_POST["end"];
    if($start == '' && $end == ''){
	    $sql = "select cast(F_NodeTreeNo as varchar)+'-'+cast(F_NodeID as varchar) as F_NodeID,cast(F_Rate * 100 as int) as F_Rate from tb_B_EntityTreeMetering where F_EntityTreeNo = '" . $groupTree . "' and F_EntityID = '" . $group . "' and F_NodeTreeNo = '" . $nodeTree . "' and F_EndDate is null";
    }else{
        $sql = "select cast(F_NodeTreeNo as varchar)+'-'+cast(F_NodeID as varchar) as F_NodeID,cast(F_Rate * 100 as int) as F_Rate from tb_B_EntityTreeMetering where F_EntityTreeNo = '" . $groupTree . "' and F_EntityID = '" . $group . "' and F_NodeTreeNo = '" . $nodeTree . "' and '" . $start . "' = F_StartDate and '" . ($end == '' ? '9999-12-31' : $end) . "' <= isnull(F_EndDate,'9999-12-31')";
    }
    $result = $db -> query($sql);
	echo json_encode($result);
?>

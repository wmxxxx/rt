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
    $entity = $_GET["entity"];
    $date = $_GET["date"];

	$sql = "proc_B_GetEntityPropertyValue '" . $tree . "','" . $entity . "','" . $date . "'";
	$result = $db -> query($sql);
    
    $resObj = new stdClass();
    $resObj -> success = true;
    $resObj -> data = new stdClass();
    foreach ($result as $obj){
        $resObj -> data -> {$obj -> F_PropertyID} = $obj -> F_PropertyValue;
    }
	echo json_encode($resObj);
?>

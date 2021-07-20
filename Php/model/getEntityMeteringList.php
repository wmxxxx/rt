<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_POST["tree"];
    $entity = $_POST["entity"];
	$sql = "exec proc_B_GetEntityMeteringNode '" . $tree . "','" . $entity . "'";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

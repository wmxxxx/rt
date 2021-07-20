<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_ObjectTypeID,F_ObjectTypeName from tb_B_ObjectType order by F_ObjectTypeIndex";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_ObjectTypeName,$obj -> F_ObjectTypeID));
    }
	echo json_encode($resArray);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$ObjectTypeID = $_POST["ObjectTypeID"];
    if($ObjectTypeID == ""){
	    $sql = "select F_GroupTypeID,F_GroupTypeName from tb_B_GroupType where F_ObjectTypeID is null";
    }else{
        $sql = "select F_GroupTypeID,F_GroupTypeName from tb_B_GroupType where F_ObjectTypeID=" . $ObjectTypeID;
    }
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_GroupTypeName,$obj -> F_GroupTypeID));
    }
	echo json_encode($resArray);
?>

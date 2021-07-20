<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_GroupID,F_GroupName from tb_B_DictTreeModel";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_GroupName,$obj -> F_GroupID));
    }
	echo json_encode($resArray);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_GroupID as value,dbo.fun_GetDictFullName(F_GroupID,0) as name from dbo.tb_B_DictTreeModel where F_ObjectGroup = '2' or F_ObjectGroup = '3'";
	$result = $db -> query($sql);
    $resArray = array();
    array_push($resArray,array('无',''));
    foreach ($result as $obj){
        array_push($resArray,array($obj -> name,$obj -> value));
    }
	echo json_encode($resArray);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_DictTreeNo as value,F_DictTreeName as name from tb_B_DictTreeType order by F_DictTreeNo";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> name,$obj -> value));
    }
	echo json_encode($resArray);
?>

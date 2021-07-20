<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_DictionaryID,F_DictionaryName from tb_B_KeyValueTable";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_DictionaryName,$obj -> F_DictionaryID));
    }
	echo json_encode($resArray);
?>

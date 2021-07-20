<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_EntityTreeNo as value,F_EntityTreeName as name from tb_B_EntityTreeType where F_EntityTreeType = '1' order by F_EntityTreeNo";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> name,$obj -> value));
    }
	echo json_encode($resArray);
?>

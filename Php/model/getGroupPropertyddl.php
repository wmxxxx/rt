<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $group = $_POST["group"];
	$sql = "select a.F_PropertyID as value,b.F_PropertyName as name from tb_B_DictTreeProperty a,tb_B_ObjectProperty b where a.F_GroupID = '" . $group . "' and a.F_PropertyID = b.F_PropertyID";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        array_push($resArray,array($obj -> name,$obj -> value));
    }
	echo json_encode($resArray);
?>

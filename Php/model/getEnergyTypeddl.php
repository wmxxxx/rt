<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_EntityID as value,dbo.fun_GetEntityFullName(F_EntityID) as name from dbo.tb_B_EntityTreeModel where F_EntityTreeNo in(select F_EntityTreeNo from dbo.tb_B_EntityTreeType where F_EntityTreeType = '3') and F_EntityDepth=2 order by F_EntityTreeNo,F_EntityID";
	$result = $db -> query($sql);
    $resArray = array();
    array_push($resArray,array('无',''));
    foreach ($result as $obj){
        array_push($resArray,array($obj -> name,$obj -> value));
    }
	echo json_encode($resArray);
?>

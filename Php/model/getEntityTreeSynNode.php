<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $node = explode("-",$_POST["node"]);
    $treeNo = $node[0];
	$sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar) as F_EntityID,F_EntityID as EntityID,F_EntityName,F_IsHasChild,F_ParentID,F_EntityDepth,F_ObjectGroup,dbo.fun_GetNodeAorVType(F_EntityID) as F_AV from tb_B_EntityTreeModel where F_EntityTreeNo='" . $treeNo . "' and F_ParentID <> '0' order by F_EntityDepth,F_OrderTag";
	$result = $db -> query($sql);
    
    $resArray = array();
    $nodeArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_EntityID;
        $o -> text = $obj -> F_EntityName;
        $o -> iconCls = $obj -> F_ObjectGroup == '3' ? ($obj -> F_AV == 1 ? 'facility' : 'device_virtual') : ($obj -> F_AV == 1 ? 'node' : 'meter_virtual');
        $o -> leaf = ($obj -> F_IsHasChild == 0 ? true : false);
        $o -> checked = false;
        $o -> children = array();

        if($obj -> F_EntityDepth == 2){
            array_push($resArray,$o);
            $nodeArray[$obj -> EntityID] = $o;
        }else{
            array_push($nodeArray[$obj -> F_ParentID] -> children,$o);
            $nodeArray[$obj -> EntityID] = $o;
        }
    }
    echo json_encode($resArray);
?>

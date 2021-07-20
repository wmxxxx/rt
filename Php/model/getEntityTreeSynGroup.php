<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $node_tree = $_POST["node_tree"];
    $node = explode("-",$_POST["node"]);
    $treeNo = $node[0];
    $sql = "";
    if($node_tree == ""){
        $sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar) as F_EntityID,F_EntityID as EntityID,case when F_EntitySName is null or F_EntitySName = '' then F_EntityName else F_EntityName + '|' + F_EntitySName end as F_EntityName,F_IsHasChild,F_ParentID,F_EntityDepth,0 as F_Metering from tb_B_EntityTreeModel where F_EntityTreeNo='" . $treeNo . "' and F_ParentID <> '0' and (F_ObjectGroup = '1' or F_ObjectGroup is null or F_ObjectGroup = '') order by F_EntityDepth,F_OrderTag";
    }else{
        $sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar) as F_EntityID,F_EntityID as EntityID,case when F_EntitySName is null or F_EntitySName = '' then F_EntityName else F_EntityName + '|' + F_EntitySName end as F_EntityName,F_IsHasChild,F_ParentID,F_EntityDepth,dbo.fun_GetEntityMeteringStatus(F_EntityID," . $node_tree . ") as F_Metering from tb_B_EntityTreeModel where F_EntityTreeNo='" . $treeNo . "' and F_ParentID <> '0' and (F_ObjectGroup = '1' or F_ObjectGroup is null or F_ObjectGroup = '') order by F_EntityDepth,F_OrderTag";
    }
	
	$result = $db -> query($sql);
    
    $resArray = array();
    $nodeArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_EntityID;
        $o -> text = $obj -> F_EntityName;
        $o -> iconCls = $obj -> F_Metering == 1 ? 'metering' : 'nometering';
        $o -> leaf = ($obj -> F_IsHasChild == 0 ? true : false);
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

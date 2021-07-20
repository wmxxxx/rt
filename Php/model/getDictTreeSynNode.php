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
	$sql = "select cast(F_DictTreeNo as varchar)+'-'+cast(F_GroupID as varchar)+'-'+cast(isnull(F_ObjectTypeID,0) as varchar)+'-'+F_ObjectGroup as F_GroupID,F_GroupName,F_IsHasChild,F_GroupID as GroupID,F_ParentID,F_GroupDepth,F_ObjectGroup from tb_B_DictTreeModel where F_DictTreeNo='" . $treeNo . "' and F_ParentID <> '0' order by F_ObjectGroup,F_GroupDepth,F_GroupID";
	$result = $db -> query($sql);
    
    $resArray = array();
    $nodeArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_GroupID;
        $o -> text = $obj -> F_GroupName;
        $o -> leaf = ($obj -> F_IsHasChild == 0 ? true : false);
        $o -> children = array();

        if($obj -> F_GroupDepth == 2){
            array_push($resArray,$o);
            $nodeArray[$obj -> GroupID] = $o;
        }else{
            array_push($nodeArray[$obj -> F_ParentID] -> children,$o);
            $nodeArray[$obj -> GroupID] = $o;
        }
    }
    echo json_encode($resArray);
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $group_tree = $_GET["group_tree"];
    $node_tree = $_GET["node_tree"];
    
    if($group_tree == ''){
        $sql = "select dbo.fun_GetEntityTreeTypeByNo(a.F_EntityTreeNo) as F_EntityTreeType,cast(a.F_EntityTreeNo as varchar)+'-'+cast(a.F_EntityID as varchar) as F_EntityID,a.F_EntityID as EntityID,a.F_EntityName,a.F_IsHasChild,a.F_ParentID,a.F_EntityDepth,a.F_ObjectGroup,dbo.fun_GetNodeAorVType(a.F_EntityID) as F_AV,isnull(b.F_NodeNo,'') as F_NodeNo,isnull(b.F_Location,'') as F_Location,isnull(b.F_Remark,'') as F_Remark,0 as F_Status,0 as F_Checked from tb_B_EntityTreeModel a left outer join dbo.tb_A_IoTNode b on a.F_EntityID = b.F_NodeCode where a.F_EntityTreeNo='" . $node_tree . "' order by a.F_EntityDepth,a.F_OrderTag";
    }else{
        if(array_key_exists('group',$_GET)){
            $sql = "select dbo.fun_GetEntityTreeTypeByNo(a.F_EntityTreeNo) as F_EntityTreeType,cast(a.F_EntityTreeNo as varchar)+'-'+cast(a.F_EntityID as varchar) as F_EntityID,a.F_EntityID as EntityID,a.F_EntityName,a.F_IsHasChild,a.F_ParentID,a.F_EntityDepth,a.F_ObjectGroup,dbo.fun_GetNodeAorVType(a.F_EntityID) as F_AV,isnull(b.F_NodeNo,'') as F_NodeNo,isnull(b.F_Location,'') as F_Location,isnull(b.F_Remark,'') as F_Remark,dbo.fun_GetNodeRelationStatus(" . $group_tree . ",a.F_EntityID) as F_Status,dbo.fun_GetEntityRelNodeStatus(" . $group_tree . "," . $_GET["group"] . ",a.F_EntityID) as F_Checked from tb_B_EntityTreeModel a left outer join dbo.tb_A_IoTNode b on a.F_EntityID = b.F_NodeCode where a.F_EntityTreeNo='" . $node_tree . "' order by a.F_EntityDepth,a.F_OrderTag";
        }else{
            $sql = "select dbo.fun_GetEntityTreeTypeByNo(a.F_EntityTreeNo) as F_EntityTreeType,cast(a.F_EntityTreeNo as varchar)+'-'+cast(a.F_EntityID as varchar) as F_EntityID,a.F_EntityID as EntityID,a.F_EntityName,a.F_IsHasChild,a.F_ParentID,a.F_EntityDepth,a.F_ObjectGroup,dbo.fun_GetNodeAorVType(a.F_EntityID) as F_AV,isnull(b.F_NodeNo,'') as F_NodeNo,isnull(b.F_Location,'') as F_Location,isnull(b.F_Remark,'') as F_Remark,dbo.fun_GetNodeRelationStatus(" . $group_tree . ",a.F_EntityID) as F_Status,0 as F_Checked from tb_B_EntityTreeModel a left outer join dbo.tb_A_IoTNode b on a.F_EntityID = b.F_NodeCode where a.F_EntityTreeNo='" . $node_tree . "' order by a.F_EntityDepth,a.F_OrderTag";
        }
    }
	$result = $db -> query($sql);
    
    $resArray = array();
    $nodeArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_EntityID;
        $o -> name = $obj -> F_EntityName;
        $o -> checked = $obj -> F_Checked == 1 ? true : false;
        $o -> no = $obj -> F_NodeNo;
        $o -> iconCls = $obj -> F_Status == 1 ? ($obj -> F_ObjectGroup == '2' ? ($obj -> F_AV == 1 ? 'icon-meter-green' : 'icon-meter-virtual') : ($obj -> F_ObjectGroup == '3' ? ($obj -> F_AV == 1 ? 'icon-facility-green' : 'icon-facility-virtual') : ($obj -> F_EntityTreeType == '2' ? 'icon-meter-green' : 'icon-facility-green'))) : ($obj -> F_ObjectGroup == '2' ? 'icon-meter-red' : ($obj -> F_ObjectGroup == '3' ? 'icon-facility-red' : ($obj -> F_EntityTreeType == '2' ? 'icon-meter-red' : 'icon-facility-red')));
        $o -> leaf = $obj -> F_IsHasChild == 0 ? true : false;
        $o -> location = $obj -> F_Location;
        $o -> remark = $obj -> F_Remark;
        if($obj -> F_IsHasChild == 1){
            $o -> state = $obj -> F_EntityDepth == 1 ? 'open' : 'closed';
        }
        $o -> children = array();

        if($obj -> F_EntityDepth == 1){
            array_push($resArray,$o);
            $nodeArray[$obj -> EntityID] = $o;
        }else{
            array_push($nodeArray[$obj -> F_ParentID] -> children,$o);
            $nodeArray[$obj -> EntityID] = $o;
        }
    }
    echo json_encode($resArray);
?>

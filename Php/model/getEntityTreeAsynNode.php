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
    $entity = $node[1];
	$sql = "select dbo.fun_GetNodeAorVType(F_EntityID) as av,cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar)+'-'+isnull(cast(F_TemplateID as varchar),'')+'-'+isnull(cast(F_NodeTemplate as varchar),'')+'-'+isnull(F_ObjectGroup,'')+'-'+isnull(cast(F_IsDisplay as varchar),'1')+'-'+isnull(F_OrderTag,'')+'-'+isnull(cast(F_EnergyTypeID as varchar),'')+'-'+isnull(cast(F_DeviceTypeID as varchar),'')+'-'+isnull(F_MapTag,'') + '-' + cast(dbo.fun_GetNodeAorVType(F_EntityID) as varchar) as F_EntityID,case when F_EntitySName is null or F_EntitySName = '' then F_EntityName else F_EntityName + '|' + F_EntitySName end as F_EntityName,F_IsHasChild from tb_B_EntityTreeModel where F_EntityTreeNo='" . $treeNo . "' and F_ParentID = '" . $entity . "' order by F_EntityDepth,F_OrderTag,F_ObjectGroup";
	$result = $db -> query($sql);
    
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_EntityID;
        $o -> text = $obj -> F_EntityName;
        $o -> leaf = ($obj -> F_IsHasChild == 0 ? true : false);
        array_push($resArray,$o);
    }
    echo json_encode($resArray);
?>

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
	$sql = "select 'node' as F_NodeType,cast(F_EntityTreeNo as varchar) + '-' + cast(F_EntityID as varchar) + '-node-s' as F_EntityID,F_EntityName,case when F_IsHasChild = 1 then 1 when F_NodeTemplate is null or F_NodeTemplate = '' then 0 else 1 end as F_IsHasChild,F_OrderTag as F_OrderNum,dbo.fun_GetNodeAorVType(F_EntityID) as F_AV from tb_B_EntityTreeModel where F_EntityTreeNo='" . $treeNo . "' and F_ParentID = '" . $entity . "' union select 'param' as F_NodeType,cast(F_EntityID as varchar) + '-' + cast(b.F_ValueLabel as varchar) +  '-param-s' as F_EntityID,F_ValueName as F_EntityName,0 as F_IsHasChild,cast(F_OrderNum as varchar) F_OrderNum,0 as F_AV from dbo.tb_B_EntityTreeModel a,dbo.tb_A_Value b where a.F_EntityTreeNo='" . $treeNo . "' and a.F_EntityID = '" . $entity . "' and a.F_NodeTemplate = b.F_TemplateCode and b.F_IsDisplay = 1 and b.F_ValueProperty='1' order by F_NodeType,F_OrderNum";
	
    $result = $db -> query($sql);
    
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_EntityID;
        $o -> text = $obj -> F_EntityName;
        $o -> iconCls = $obj -> F_NodeType == 'param' ? 'param' : ($obj -> F_AV == 1 ? 'facility' : 'device_virtual');
        $o -> leaf = ($obj -> F_IsHasChild == 0 ? true : false);
        array_push($resArray,$o);
    }
    echo json_encode($resArray);
?>

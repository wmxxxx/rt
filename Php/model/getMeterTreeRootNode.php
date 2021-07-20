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
    $sql = "";
    if($node_tree == ""){
        $sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar)+'-'+isnull(cast(F_TemplateID as varchar),'')+'-'+isnull(cast(F_NodeTemplate as varchar),'')+'-'+isnull(F_ObjectGroup,'')+'-'+cast(F_IsDisplay as varchar) as F_EntityID,case when F_EntitySName is null or F_EntitySName = '' then F_EntityName else F_EntityName + '|' + F_EntitySName end as F_EntityName,F_IsHasChild,0 as F_Metering from tb_B_EntityTreeModel where F_EntityTreeNo='" . $group_tree . "' and F_ParentID='0'";
    }else{
        $sql = "select cast(F_EntityTreeNo as varchar)+'-'+cast(F_EntityID as varchar)+'-'+isnull(cast(F_TemplateID as varchar),'')+'-'+isnull(cast(F_NodeTemplate as varchar),'')+'-'+isnull(F_ObjectGroup,'')+'-'+cast(F_IsDisplay as varchar) as F_EntityID,case when F_EntitySName is null or F_EntitySName = '' then F_EntityName else F_EntityName + '|' + F_EntitySName end as F_EntityName,F_IsHasChild,dbo.fun_GetEntityMeteringStatus(F_EntityID," . $node_tree . ") as F_Metering from tb_B_EntityTreeModel where F_EntityTreeNo='" . $group_tree . "' and F_ParentID='0'";
    }
	
	$result = $db -> query($sql);
	echo json_encode($result);
?>

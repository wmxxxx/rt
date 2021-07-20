<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $tree = $_POST["tree"];
    $entity = $_POST["entity"];
	$sql = "select ROW_NUMBER()OVER(ORDER BY a.F_OrderTag) as F_RowNum,a.F_OrderTag,a.F_EntityTreeNo,a.F_EntityID,case when a.F_EntitySName is null or a.F_EntitySName = '' then a.F_EntityName else a.F_EntityName + '|' + a.F_EntitySName end as F_EntityName,a.F_EntityDepth,a.F_ObjectGroup,case a.F_ObjectGroup when 1 then '管理类' when 2 then '表具类' when 3 then '设备类' end as F_ObjectGroupName,a.F_TemplateID,b.F_GroupName as F_TemplateName,dbo.fun_GetNodeEnergyType(a.F_EntityID) as F_EnergyTypeID,dbo.fun_GetEnergyTypeName(dbo.fun_GetNodeEnergyType(a.F_EntityID)) as F_EnergyTypeName,dbo.fun_GetNodeDeviceType(a.F_EntityID) as F_DeviceTypeID,dbo.fun_GetDeviceTypeName(dbo.fun_GetNodeDeviceType(a.F_EntityID)) as F_DeviceTypeName,a.F_NodeTemplate,c.F_TemplateName as F_NodeTemplateName,case a.F_IsDisplay when 0 then '隐藏' else '显示' end as F_IsDisplay,dbo.fun_GetEntityToMeterCount(a.F_EntityID) as F_MeterCount,dbo.fun_GetEntityToDeviceCount(a.F_EntityID) as F_DeviceCount from dbo.tb_B_DictTreeModel b,dbo.tb_B_EntityTreeModel a left outer join tb_A_Template c on a.F_NodeTemplate = c.F_TemplateCode where a.F_EntityTreeNo = '" . $tree . "' and a.F_ParentID = '" . $entity . "' and a.F_TemplateID = b.F_GroupID";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

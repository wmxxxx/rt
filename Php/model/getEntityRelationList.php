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
	$sql = "select ROW_NUMBER()OVER(ORDER BY a.F_DeviceTreeNo,a.F_DeviceID) as F_RowNum,a.F_DeviceTreeNo,d.F_EntityTreeName as F_DeviceTreeName,a.F_DeviceID,b.F_GroupName as F_DeviceTypeName,c.F_EntityName as F_DeviceName from dbo.tb_B_EntityTreeToDevice a,dbo.tb_B_DictTreeModel b,dbo.tb_B_EntityTreeModel c,dbo.tb_B_EntityTreeType d where a.F_EntityTreeNo =" . $tree . " and a.F_EntityID = " . $entity . " and dbo.fun_GetNodeDeviceType(a.F_DeviceID) = b.F_GroupID and c.F_EntityTreeNo = a.F_DeviceTreeNo and c.F_EntityID = a.F_DeviceID and a.F_DeviceTreeNo = d.F_EntityTreeNo";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

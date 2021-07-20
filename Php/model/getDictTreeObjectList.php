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
	$sql = "select ROW_NUMBER()OVER(ORDER BY a.F_GroupDepth) as F_RowNum,dbo.fun_GetDictFullName(F_GroupID,1) as F_GroupName,case F_ObjectGroup when 1 then '管理类' when 2 then '表具类' when 3 then '设备类' end as F_ObjectGroup,b.F_ObjectTypeName,a.F_GroupTag,a.F_GroupDepth from dbo.tb_B_DictTreeModel a,dbo.tb_B_ObjectType b where a.F_DictTreeNo = " . $tree . " and a.F_ObjectTypeID = b.F_ObjectTypeID";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

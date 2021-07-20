<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select ROW_NUMBER()OVER(ORDER BY F_EntityTreeNo) AS F_RowNum,F_EntityTreeNo,F_EntityTreeName,F_EntityTreeType,case F_EntityTreeType when '1' then '管理模型' when '2' then '表具模型' when '3' then '字典模型' when '4' then '设备模型' end as F_EntityTreeTypeName ,F_TreeDepth,F_CreateUser,convert(varchar,F_CreateDate,120) as F_CreateDate,F_Memo from tb_B_EntityTreeType";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

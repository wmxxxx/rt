<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select ROW_NUMBER()OVER(ORDER BY F_DictTreeNo) AS F_RowNum,F_DictTreeNo,F_DictTreeName,F_TreeDepth,F_CreateUser,convert(varchar,F_CreateDate,120) as F_CreateDate,F_Memo from tb_B_DictTreeType";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

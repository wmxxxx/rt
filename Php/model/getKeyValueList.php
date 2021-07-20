<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $dictionaryID = $_POST["dictionaryID"];

	$sql = "select ROW_NUMBER()OVER(ORDER BY F_Key) as F_RowNum,F_DictionaryID,F_Key,F_Value from tb_B_KeyValueList where F_DictionaryID='" . $dictionaryID . "' order by F_Key";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_DictionaryID,F_DictionaryName,F_IsReadOnly from tb_B_KeyValueTable";
	$result = $db -> query($sql);
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

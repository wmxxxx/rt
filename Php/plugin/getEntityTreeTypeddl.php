<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select cast(F_EntityTreeNo as varchar) + '-' + F_EntityTreeType as value,F_EntityTreeName as name from tb_B_EntityTreeType where F_EntityTreeType = '1' or F_EntityTreeType = '2' or F_EntityTreeType = '4' order by F_EntityTreeNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

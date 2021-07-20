<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_EntityTreeNo as id,F_EntityTreeName as name,'0' as pId from tb_B_EntityTreeType where F_EntityTreeType = '3' order by F_EntityTreeNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

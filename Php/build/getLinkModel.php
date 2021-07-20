<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$no = $_POST["no"];
    $sql = "select distinct a.F_EntityTreeNo as id,b.F_EntityTreeName as name,0 as pId from dbo.tb_A_ProjectToTree a,dbo.tb_B_EntityTreeType b where a.F_ProjectNo = " . $no . " and b.F_EntityTreeType in ('1','2','4') and a.F_EntityTreeNo = b.F_EntityTreeNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

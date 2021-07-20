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
    $sql = "select F_EntityTreeNo as id,F_EntityTreeName as name,0 as pId from dbo.tb_B_EntityTreeType where F_EntityTreeType in ('1','2','4') and F_EntityTreeNo not in (select distinct F_EntityTreeNo from dbo.tb_A_ProjectToTree where F_ProjectNo = " . $no . ")";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

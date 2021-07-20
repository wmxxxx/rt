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
    
	$sql = "select distinct a.F_RoleCode,a.F_RoleName from tb_A_Role a,dbo.tb_A_LoginUser b where a.F_RoleCode = b.F_RoleCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

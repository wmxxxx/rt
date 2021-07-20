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
    
	$sql = "select 0 as pId,cast(F_UserCode as varchar) + '-' + cast(F_UserID as varchar) as id,F_UserName as name,F_UserName + '[' + F_Email + ']' as ename,F_Email as email from tb_A_LoginUser order by F_UserType,F_UserID";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

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
    
	$sql = "select F_RoleCode,F_RoleName,case when F_RoleGroup = '' then '未分组' else F_RoleGroup end as F_RoleGroup,F_Kanban,F_Project from tb_A_Role order by F_RoleGroup,F_RoleCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

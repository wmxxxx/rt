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
	
	$role = $_POST["role"];
    $sql = "select cast(F_ProjectNo as varchar) as id,F_ProjectName as name,'0' as pId,0 as checked,0 as type,0 as num from dbo.tb_A_Project where F_ProjectNo in (select F_AppNo from tb_A_RoleToApp where F_RoleCode = " . $role . ") union select cast(a.F_ProjectNo as varchar) + '_' + cast(a.F_MenuCode as varchar)  as id,a.F_MenuName as name,case cast(a.F_ParentCode as varchar) when '0' then cast(a.F_ProjectNo as varchar) else cast(a.F_ProjectNo as varchar) + '_' + cast(a.F_ParentCode as varchar) end as pId,case when b.F_MenuCode is null then 0 else 1 end as checked,F_MenuType as type,F_MenuIndex as num from dbo.tb_A_ProjectToMenu a left outer join dbo.tb_A_RoleToMenu b on b.F_RoleCode = " . $role . " and a.F_ProjectNo = b.F_ProjectNo and a.F_MenuCode = b.F_MenuCode where a.F_ProjectNo in (select F_AppNo from tb_A_RoleToApp where F_RoleCode = " . $role . ") order by type,num";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

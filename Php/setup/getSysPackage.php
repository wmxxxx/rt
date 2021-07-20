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
	
    $sql = "select cast(F_ProjectNo as varchar) as id,F_ProjectName as name,'0' as pId,'sys' as tag,0 as num from dbo.tb_A_Project union select cast(F_ProjectNo as varchar) + '_' + cast(F_MenuCode as varchar)  as id,F_MenuName as name,case cast(F_ParentCode as varchar) when '0' then cast(F_ProjectNo as varchar) else cast(F_ProjectNo as varchar) + '_' + cast(F_ParentCode as varchar) end as pId,'menu' as tag,F_MenuIndex as num from dbo.tb_A_ProjectToMenu union select 'sys_app' as id,'移动应用模块' as name,'0' as pId,'' as tag,0 as num union select cast(F_AppCode as varchar) as id,F_AppName as name,'sys_app' as pId,'app' as tag,0 as num from dbo.tb_A_MobileApp order by num";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

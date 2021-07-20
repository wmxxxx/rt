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
	
	$sql = "select a.F_TemplateCode,a.F_TemplateName,a.F_TemplateLabel,a.F_TemplateType,a.F_EnergyTypeID,isnull(b.F_EntityName,'-') as F_EnergyTypeName,a.F_DeviceTypeID,isnull(c.F_GroupName,'-') as F_DeviceTypeName,a.F_IsRefer,a.F_AppCode,isnull(d.F_AppName,'-') as F_AppName,a.F_TemplateID from tb_A_Template a left outer join tb_B_EntityTreeModel b on a.F_EnergyTypeID=b.F_EntityID left outer join dbo.tb_B_DictTreeModel c on a.F_DeviceTypeID=c.F_GroupID left outer join dbo.tb_A_IoTApp d on a.F_AppCode = d.F_AppCode";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

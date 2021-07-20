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
	include_once("../lib/file.php");
	ini_set('date.timezone','Asia/Shanghai');
	
	$etree = $_GET["etree"];
	$dtree = $_GET["dtree"];
    $sql = "select F_EntityID,dbo.fun_GetEntityFullName(F_EntityID) as F_EntityFullName,F_DeviceID,dbo.fun_GetEntityFullName(F_DeviceID) as F_DeviceFullName from dbo.tb_B_EntityTreeToDevice where F_EntityTreeNo = " . $etree . " and F_DeviceTreeNo = " . $dtree . " order by dbo.fun_GetEntityTreeDepth(F_EntityID),dbo.fun_GetEntityOrderTag(F_EntityID)";
	$result = $db -> query($sql);
    $csv = "对象编号,对象名称,设备编号,设备名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    foreach ($result as $obj){
        $F_EntityID = strval($obj -> F_EntityID);
        $F_EntityFullName = $obj -> F_EntityFullName;
        $F_DeviceID = strval($obj -> F_DeviceID);
        $F_DeviceFullName = $obj -> F_DeviceFullName;
        $csv .= $F_EntityID . "," . $F_EntityFullName . "," . $F_DeviceID . "," . $F_DeviceFullName . "\n";
    }
    $filename = urlencode('设备关系配置模板（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

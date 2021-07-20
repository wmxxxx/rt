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
    $sql = "select F_EntityID,dbo.fun_GetEntityFullName(F_EntityID) as F_EntityFullName,F_NodeID,dbo.fun_GetEntityFullName(F_NodeID) as F_NodeFullName,cast(F_StartDate as varchar) as F_StartDate,isnull(cast(F_EndDate as varchar),'') as F_EndDate,F_Rate from dbo.tb_B_EntityTreeMetering where F_EntityTreeNo = " . $etree . " and F_NodeTreeNo = " . $dtree . " order by dbo.fun_GetEntityTreeDepth(F_EntityID),dbo.fun_GetEntityOrderTag(F_EntityID),F_StartDate";
	
    $result = $db -> query($sql);
    $csv = "对象编号,对象名称,设备编号,设备名称,开始时间,截止时间,纳入比例\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    foreach ($result as $obj){
        $F_EntityID = strval($obj -> F_EntityID);
        $F_EntityFullName = $obj -> F_EntityFullName;
        $F_NodeID = strval($obj -> F_NodeID);
        $F_NodeFullName = $obj -> F_NodeFullName;
        $F_StartDate = $obj -> F_StartDate;
        $F_EndDate = $obj -> F_EndDate;
        $F_Rate = strval($obj -> F_Rate);
        $csv .= $F_EntityID . "," . $F_EntityFullName . "," . $F_NodeID . "," . $F_NodeFullName . "," . $F_StartDate . "," . $F_EndDate . "," . $F_Rate . "\n";
    }
    $filename = urlencode('计量关系配置模板（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

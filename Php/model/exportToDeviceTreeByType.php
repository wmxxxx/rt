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
	
	$type = $_GET["type"];
    $sql = "select F_EntityID,dbo.fun_GetEntityFullName(F_EntityID) as F_EntityFullName from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $type . " order by F_EntityDepth,F_OrderTag";
	$result = $db -> query($sql);
    $csv = "设备编号,设备名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 

    foreach ($result as $obj){
        $F_EntityID = strval($obj -> F_EntityID);
        $F_EntityFullName = $obj -> F_EntityFullName;
        $csv .= $F_EntityID . "," . $F_EntityFullName . "\n";
    }
    $filename = urlencode('设备模型字典信息表（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

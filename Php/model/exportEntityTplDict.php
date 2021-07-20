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
    
    $sql = "select F_GroupID,dbo.fun_GetDictFullName(F_GroupID,1) as F_GroupName from dbo.tb_B_DictTreeModel where F_ObjectGroup = '1' and F_ObjectTypeID is not null order by F_DictTreeNo,F_GroupDepth";
	$result = $db -> query($sql);
    $csv = "对象模板编号,对象模板名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $app_name = '';
    foreach ($result as $obj){
        $F_GroupID = strval($obj -> F_GroupID);
        $F_GroupName = $obj -> F_GroupName;
        $csv .= $F_GroupID . "," . $F_GroupName . "\n";
    }
    $filename = urlencode('对象模板字典信息表（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

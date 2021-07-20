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
    $sql = "select 1 as F_RowNum,F_EntityID,F_ParentID,F_EntityName,ISNULL(F_EntitySName,'') as F_EntitySName,F_EntityDepth,F_TemplateID,F_OrderTag,dbo.fun_GetEntityFullName(F_EntityID) as F_EntityFullName from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $type . " union select 2 as F_RowNum,MAX(F_EntityID) + 1 as F_EntityID,null as F_ParentID,null as F_EntityName,null as F_EntitySName,null as F_EntityDepth,null as F_TemplateID,null as F_OrderTag,'此行开始添加新的对象节点' as F_EntityFullName from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $type . " order by F_RowNum,F_EntityDepth,F_OrderTag";
	$result = $db -> query($sql);
    $csv = "对象编号,父对象编号,对象名称,对象简称,对象模板,排序号,备注\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $app_name = '';
    foreach ($result as $obj){
        $F_EntityID = strval($obj -> F_EntityID);
        $F_ParentID = strval($obj -> F_ParentID);
        $F_EntityName = $obj -> F_EntityName;
        $F_EntitySName = $obj -> F_EntitySName;
        $F_TemplateID = strval($obj -> F_TemplateID);
        $F_OrderTag = $obj -> F_OrderTag;
        $F_EntityFullName = $obj -> F_EntityFullName;
        $csv .= $F_EntityID . "," . $F_ParentID . "," . $F_EntityName . "," . $F_EntitySName . "," . $F_TemplateID . "," . $F_OrderTag . "," . $F_EntityFullName . "\n";
    }
    $filename = urlencode('对象管理模型配置模板（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

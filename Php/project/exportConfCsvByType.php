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
    $sql = "select b.F_GroupName,a.F_EntityID,a.F_EntityName from dbo.tb_B_EntityTreeModel a,dbo.tb_B_DictTreeModel b where a.F_TemplateID = " . $type . " and b.F_GroupID = " . $type;
	$result = $db -> query($sql);
    $csv = "对象编号,对象名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $dict_name = '';
    foreach ($result as $obj){
        if($dict_name == '') $dict_name = $obj -> F_GroupName;
        $id = $obj -> F_EntityID;
        $name = $obj -> F_EntityName;
        $csv .= $id . "," . $name . "\n";
    }
    $filename = urlencode($dict_name . '-配置对象参照表（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

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
    
	$group = $_GET["group"];
	$tag = $_GET["tag"];
    $sql = "select b.F_PropertyName,dbo.fun_GetEntityPropertyValue(a.F_EntityID,b.F_PropertyIdentifier) as F_PropertyValue,a.F_EntityName from dbo.tb_B_EntityTreeModel a,dbo.tb_B_ObjectProperty b where a.F_TemplateID = " . $group . " and b.F_PropertyID = " . $tag;
	$result = $db -> query($sql);
    $csv = "对象属性值,对象属性名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $property_name = '';
    foreach ($result as $obj){
        if($property_name == '') $property_name = $obj -> F_PropertyName;
        $id = $obj -> F_PropertyValue;
        $name = $obj -> F_EntityName;
        $csv .= $id . "," . $name . "\n";
    }
    $filename = urlencode($property_name . '-配置对象属性参照表（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

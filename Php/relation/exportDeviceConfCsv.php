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
    
	$tree = $_GET["tree"];
    $tags = array();
    $csv = "设备编号,设备名称,设备模板,能源类型编号,能源类型名称,设备类型编号,设备类型名称,通讯编号,安装位置,父设备编号,计量/隶属对象（多个分号分割）"; 
    
    $sql = "select a.F_PropertyID,a.F_PropertyIdentifier,a.F_PropertyName from dbo.tb_B_ObjectProperty a,dbo.tb_B_DictTreeProperty b where b.F_GroupID = (select top 1 F_TemplateID from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $tree . " and F_TemplateID is not null) and b.F_PropertyID = a.F_PropertyID and a.F_PropertyTypeID <> '4' order by b.F_OrderNum";
	$result = $db -> query($sql);
    foreach ($result as $title){
        $csv .= "," . $title -> F_PropertyName . "/" . $title -> F_PropertyIdentifier;
        array_push($tags,$title -> F_PropertyIdentifier);
    }
    $csv .= "\n";
    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $sql = "exec proc_A_ExportDeviceTree " . $tree;
	$result = $db -> query($sql);
    $tree_name = '';
    foreach ($result as $obj){
        if($tree_name == '') $tree_name = $obj -> F_EntityTreeName;
        $code = strval($obj -> F_NodeCode);
        $name = strval($obj -> F_NodeName);
        $tname = $obj -> F_TemplateName;
        $ecode = strval($obj -> F_EnergyTypeCode);
        $ename = $obj -> F_EnergyTypeName;
        $dcode = strval($obj -> F_DeviceTypeCode);
        $dname = strval($obj -> F_DeviceTypeName);
        $no = strval($obj -> F_NodeNo);
        $addr = $obj -> F_Location;
        $parent = strval($obj -> F_ParentCode);
        $to = $obj -> F_ToEntity;
        $csv .= $code . "," . $name . "," . $tname . "," . $ecode . "," . $ename . "," . $dcode . "," . $dname . "," . $no . "," . $addr . "," . $parent . "," . $to;
        foreach ($tags as $tag){
            $csv .= "," . strval($obj -> $tag);
        }
        $csv .= "\n";
    }
    $filename = urlencode($tree_name . '-设备配置关系模板（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

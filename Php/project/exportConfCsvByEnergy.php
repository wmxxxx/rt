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
    
	$energy = $_GET["energy"];
    $sql = "exec proc_A_ExportNodeInfo null,null,1," . $energy . ",null,null";
	$result = $db -> query($sql);
    $csv = "应用名称,能源类型,设备类型,设备编号,设备名称,通讯编号,安装位置,父设备编号,设备层级,物理分项,管理分项,计量/隶属对象（多个逗号分割）,运维编号,管理编号,坐标-经度,坐标-纬度,品牌,电表相线,水表口径\n"; 

    $energy_name = '';
    foreach ($result as $obj){
        if($energy_name == '') $energy_name = $obj -> F_EnergyTypeName;
        $app = $obj -> F_AppName;
        $ename = $obj -> F_EnergyTypeName;
        $dname = strval($obj -> F_DeviceTypeName);
        $code = strval($obj -> F_NodeCode);
        $name = strval($obj -> F_NodeName);
        $no = strval($obj -> F_NodeNo);
        $addr = $obj -> F_Location;
        $parent = strval($obj -> F_ParentCode);
        $rank = strval($obj -> F_NodeRank);
        $item = $obj -> F_EnergyItem;
        $property = $obj -> F_EnergyProperty;
        $to = $obj -> F_ToEntity;
        $ocode = $obj -> F_OperationCode;
        $mcode = $obj -> F_ManagementCode;
        $longitude = $obj -> F_Longitude;
        $latitude = $obj -> F_Latitude;
        $brand = $obj -> F_NodeBrand;
        $phase = $obj -> F_NodePhase;
        $diameter = $obj -> F_NodeDiameter;
        $csv .= $app . "," . $ename . "," . $dname . "," . $code . "," . $name . "," . $no . "," . $addr . "," . $parent . "," . $rank . "," . $item . "," . $property . "," . $to . "," . $ocode . "," . $mcode . "," . $longitude . "," . $latitude . "," . $brand . "," . $phase . "," . $diameter . "\n";
    }
    $filename = urlencode($energy_name . '-设备配置关系模板（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

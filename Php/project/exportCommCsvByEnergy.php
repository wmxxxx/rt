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
    $sql = "exec proc_A_ExportNodeInfo null,null,1," . $energy . ",null";
	$result = $db -> query($sql);
    $csv = "能源类型,设备类型,设备编号,设备名称,安装位置,通讯编号,通讯时间,通讯状态\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $energy_name = '';
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    foreach ($result as $obj){
        if($energy_name == '') $energy_name = $obj -> F_EnergyTypeName;
        $node = $obj -> F_NodeCode;
        $ename = $obj -> F_EnergyTypeName;
        $dname = strval($obj -> F_DeviceTypeName);
        $code = strval($obj -> F_NodeCode);
        $name = strval($obj -> F_NodeName);
        $addr = $obj -> F_Location;
        $no = strval($obj -> F_NodeNo);
        $commtime = $node_cache -> $node -> commtime;
        $online = $node_cache -> $node -> online == true ? '在线' : '离线';
        $csv .= $app . "," . $ename . "," . $dname . "," . $code . "," . $name . "," . $addr . "," . $no . "," . $commtime . "," . $online . "\n";
    }
    $filename = urlencode($energy_name . '-设备通讯状态清单（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

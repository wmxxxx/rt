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
	
	$device = $_POST["device"];
    $sql = "exec proc_A_GetDeviceParamData " . $device;
	$result = $db -> query($sql);
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    foreach ($result as $node){
        $device_label = $device . '-' . $node -> F_ValueLabel;
        $param_data_cache = json_decode($redis -> get($device));
        if($param_data_cache && property_exists($param_data_cache,$device_label)){
            $node -> F_ReadingDate = $param_data_cache -> $device_label -> commtime;
            $node -> F_DataValue = $param_data_cache -> $device_label -> value;
            $node -> F_ODataValue = $param_data_cache -> $device_label -> ovalue;
        }else{
            $node -> F_ReadingDate = '-';
            $node -> F_DataValue = '-';
            $node -> F_ODataValue = '-';
        }
    }
	echo json_encode(array('totalCount' => count($result),'rows' => $result));
?>

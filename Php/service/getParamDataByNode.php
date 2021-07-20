<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");

	$node = $_POST["node"];
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    $param_data_cache = json_decode($redis -> get($node));
    $tpl_param_cache = json_decode($redis -> get("tpl_param"));
    
    $node_tpl = $node_cache -> $node -> tpl;
    $tpl_param_array = $tpl_param_cache -> $node_tpl;
                
    $result = array();
    for($j = 0;$j < count($tpl_param_array);$j++){
        $node_label = $node . '-' . $tpl_param_array[$j] -> label;
        if(property_exists($param_data_cache,$node_label)){
            $data = new stdClass;
	        $data -> code = $tpl_param_array[$j] -> code;
	        $data -> name = $tpl_param_array[$j] -> name;
	        $data -> ccycle = $tpl_param_array[$j] -> ccycle;
	        $data -> scycle = $tpl_param_array[$j] -> scycle;
            $data -> commtime = $param_data_cache -> $node_label -> commtime;
            $data -> ovalue = $param_data_cache -> $node_label -> ovalue;
            if(property_exists($param_data_cache -> $node_label,'storagetime')){
                $data -> storagetime = $param_data_cache -> $node_label -> storagetime;
            }else{
                $data -> storagetime = '-';
            }
            if(property_exists($param_data_cache -> $node_label,'error_time')){
                $data -> error_time = $param_data_cache -> $node_label -> error_time;
                $data -> error_value = $param_data_cache -> $node_label -> error_value;
                $data -> error_msg = $param_data_cache -> $node_label -> error_msg;
            }else{
                $data -> error_time = '';
                $data -> error_value = '';
                $data -> error_msg = '';
            }
            array_push($result,$data);
        }
    }
	echo json_encode($result);
?>

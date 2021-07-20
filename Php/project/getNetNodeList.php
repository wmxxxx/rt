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
	
	$type = $_POST["type"];
	$code = $_POST["code"];
	$key = $_POST["key"];
    $page = $_POST["page"];
    $page_num = $_POST["page_num"];
    $sql = "exec proc_A_GetNetNodeList '" . $type . "','" . $code . "','" . $key . "'";
	$resSet = $db -> query($sql);
    $result = new stdClass;
    $result -> total = ceil(count($resSet) / $page_num);
    $result -> count = count($resSet);
    $result -> data = array();
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    if($type == "node"){
        $param_data_cache = json_decode($redis -> get($code));
         for($i = $page_num * ($page - 1);$i < count($resSet) && $i < $page_num * $page;$i++){
            $node_label = $code . '-' . $resSet[$i] -> F_ValueLabel;
            if($param_data_cache && property_exists($param_data_cache,$node_label)){
                $resSet[$i] -> F_ReadingDate = $param_data_cache -> $node_label -> commtime;
                $resSet[$i] -> F_ODataValue = $param_data_cache -> $node_label -> ovalue;
                $resSet[$i] -> F_DataValue = $param_data_cache -> $node_label -> value;
                if(property_exists($param_data_cache -> $node_label,'error_time')){
                    $resSet[$i] -> error_time = $param_data_cache -> $node_label -> error_time;
                    $resSet[$i] -> error_value = $param_data_cache -> $node_label -> error_value;
                    $resSet[$i] -> error_msg = $param_data_cache -> $node_label -> error_msg;
                }else{
                    $resSet[$i] -> error_time = '';
                    $resSet[$i] -> error_value = '';
                    $resSet[$i] -> error_msg = '';
                }
            }else{
                $resSet[$i] -> F_ReadingDate = '-';
                $resSet[$i] -> F_DataValue = '-';
                $resSet[$i] -> F_ODataValue = '-';
                $resSet[$i] -> error_time = '';
                $resSet[$i] -> error_value = '';
                $resSet[$i] -> error_msg = '';
            }
            array_push($result -> data,$resSet[$i]);
        }
    }else{
        for($i = $page_num * ($page - 1);$i < count($resSet) && $i < $page_num * $page;$i++){
            $node_code = $resSet[$i] -> F_NodeCode;
            if($node_cache && property_exists($node_cache,$node_code)){
                $resSet[$i] -> OnlineFlag = $node_cache -> $node_code -> online;
            }else{
                $resSet[$i] -> OnlineFlag = null;
            }
            array_push($result -> data,$resSet[$i]);
        }
    }
	echo json_encode($result);
?>

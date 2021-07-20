<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
	if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$node = $_POST["node"];
	$label = $_POST["label"];
	$value = $_POST["value"];
	$user = $_POST["user"];
    
	$redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    $app_cache = json_decode($redis -> get("app"));
    $param_map_cache = json_decode($redis -> get("param_map"));
    $param_deviation_cache = json_decode($redis -> get("param_deviation"));
    $node_obj = $node_cache -> $node;
    $app_code = $node_obj -> app;
    $app_obj = $app_cache -> $app_code;
	$ip = $app_obj -> ip;
    $port = $app_obj -> port;
    $key = $app_obj -> skey;
    $id = $app_obj -> id;
    $no = $node_obj -> no;
    
    $utc = time();
    $temp = array((string)$utc, $key, $id);
    sort($temp);
    $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
    $url = $ip . ':' . $port . "/app/" . $id . "/api/protected/writeDeviceVariantData?";
    $params = array('deviceCode' => $no,'writeData' => array($label => $value));
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url . http_build_query($sign));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_POST,1 );
    curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    $result = curl_exec($curl);
    if($result === false){
        echo json_encode(array('status'=>false,'msg'=>curl_error($curl)));
    }else{
        $resJson = json_decode($result);
        if(is_null($resJson)){
            echo json_encode(array('status'=>false,'msg'=>$result));
        }else if($resJson -> errCode == 0){
            if($resJson -> writeResult -> $label == 0){
                if($redis -> get("node_param")){
                    $node_param_cache = json_decode($redis -> get("node_param"));
                }else{
                    $node_param_cache = new stdClass;
                }
                if (!property_exists($node_param_cache,$node)){
                    $node_param_cache -> $node_code = $node;
                    $redis -> set($node,json_encode(new stdClass));
                }
                $param_data_cache = json_decode($redis -> get($node));
                for($i = 0;$i < count($resJson -> variantDatas);$i++){
                    $node_code_label = $node . "-" . $resJson -> variantDatas[$i] -> code;
                    $temp_tpl_label = $node_cache -> $node -> tpl . "-" . $resJson -> variantDatas[$i] -> code;
                    $param_obj = $param_map_cache -> $temp_tpl_label;
                    
                    $ovalue = number_format(str_replace(',','',$resJson -> variantDatas[$i] -> value),$param_obj -> dp, ".", "");
                    $value = $ovalue * $param_obj -> pr + (property_exists($param_deviation_cache,$node_code_label) ? $param_deviation_cache -> $node_code_label -> dvalue : 0);
                                
                    if(property_exists($param_data_cache,$node_code_label)){
                        $param_data_cache -> $node_code_label -> commtime = date("Y-m-d H:i:s",$resJson -> variantDatas[$i] -> time);
                        $param_data_cache -> $node_code_label -> commtime_stamp = $resJson -> variantDatas[$i] -> time;
                        $param_data_cache -> $node_code_label -> updatetime = date("Y-m-d H:i:s",time());
                        $param_data_cache -> $node_code_label -> ovalue = $ovalue;
                        $param_data_cache -> $node_code_label -> value = $value;
                        $param_data_cache -> $node_code_label -> error_time = '';
                        $param_data_cache -> $node_code_label -> error_msg = '';
                        $param_data_cache -> $node_code_label -> error_value = '';
                    }else{
                        $param = new stdClass;
	                    $param -> code = $node;
	                    $param -> label = $resJson -> variantDatas[$i] -> code;
	                    $param -> commtime = date("Y-m-d H:i:s",$resJson -> variantDatas[$i] -> time);
                        $param -> commtime_stamp = $resJson -> variantDatas[$i] -> time;
                        $param -> updatetime = date("Y-m-d H:i:s",time());
                        $param -> ovalue = $ovalue;
                        $param -> value = $value;
                        $param -> value_kv = '';
                        $param -> error_time = '';
                        $param -> error_value = '';
                        $param -> error_msg = '';
                        $param_data_cache -> $node_code_label = $param;
                    }
                }
                $redis -> set($node,json_encode($param_data_cache));
                $redis -> set("node_param",json_encode($node_param_cache));
                $db -> execute("exec proc_A_WriteEventLog 4,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $user . "对设备（" . $node . "）的变量（" . $label . "）写入值（" . $value . "）。");
                echo json_encode(array('status'=>true,'msg'=>'写入变量成功!'));
            }else if($resJson -> writeResult -> $label < 0){
                echo json_encode(array('status'=>true,'msg'=>'写入变量失败!'));
            }else{
                echo json_encode(array('status'=>true,'msg'=>'写入变量异常!'));
            }
        }else{
            echo json_encode(array('status'=>false,'msg'=>$resJson -> errCode . ':' . $resJson -> errMsg));
        }
    }
    curl_close($curl);
?>

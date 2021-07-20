<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/comm.php");
	date_default_timezone_set("PRC");
	
	$node = $_POST["node"];
	$label = $_POST["label"];
	
	$redis = new Redis();
	$redis -> connect('127.0.0.1', 6379);
	$redis -> auth('P@ssw0rd');
	$node_cache = json_decode($redis -> get("node"));
	$app_cache = json_decode($redis -> get("app"));
	$param_map_cache = json_decode($redis -> get("param_map"));
	$param_deviation_cache = json_decode($redis -> get("param_deviation"));
	$node_obj = $node_cache -> $node;
	$node_app = $node_obj -> app;
	$app_obj = $app_cache -> $node_app;
	$ip = $app_obj -> ip;
	$port = $app_obj -> port;
	$key = $app_obj -> skey;
	$id = $app_obj -> id;
	$no = $node_obj -> no;
	
	$utc = time();
	$temp = array((string)$utc, $key, $id);
	sort($temp);
	$sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
	$url = $ip . ':' . $port . "/app/" . $id . "/api/protected/readDeviceVariantData?";
	$params = array('deviceCode' => $no,'variants' => $label);
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
				if(isset($param_obj -> dp) && isset($param_obj -> pr)){
					$ovalue = number_format(str_replace(',','',$resJson -> variantDatas[$i] -> value),$param_obj -> dp, ".", "");
					$value = $ovalue * $param_obj -> pr + ($param_deviation_cache && property_exists($param_deviation_cache,$node_code_label) ? $param_deviation_cache -> $node_code_label -> dvalue : 0);
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
						$param -> error_time = '';
						$param -> error_value = '';
						$param -> error_msg = '';
						$param_data_cache -> $node_code_label = $param;
					}
				}
			}
			$redis -> set($node,json_encode($param_data_cache));
			$redis -> set("node_param",json_encode($node_param_cache));
			echo json_encode(array('status'=>true,'msg'=>'success'));
		}else{
			echo json_encode(array('status'=>false,'msg'=>$resJson -> errCode . ':' . $resJson -> errMsg));
		}
	}
	curl_close($curl);
?>

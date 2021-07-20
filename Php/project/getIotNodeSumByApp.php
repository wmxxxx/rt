<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $app = $_POST["app"];
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    if(!$redis -> get("syn_node")){
        $syn_node = array();
	    $sql = "select a.F_RouterIP,a.F_RouterPort,b.F_AppID,b.F_SecretKey from dbo.tb_A_IoTRouter a,dbo.tb_A_IoTApp b where b.F_AppCode = '" . $app . "' and a.F_RouterCode = b.F_RouterCode";
	    $result = $db -> query($sql);
        $ip = $result[0] -> F_RouterIP;
        $port = $result[0] -> F_RouterPort;
        $key = $result[0] -> F_SecretKey;
        $id = $result[0] -> F_AppID;
    
        $utc = time();
        $temp = array((string)$utc, $key, $id);
        sort($temp);
        $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
        $url = $ip . ':' . $port . "/app/" . $id . "/api/protected/getDeviceList?";
        $params = array('start' => 0,'len' => 100,"order" =>"code","asc" =>"true");
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
                for($i = 0;$i < count($resJson -> data);$i++){
                    $syn_node[$resJson -> data[$i] -> code] = $resJson -> data[$i];
                }
                $totalCount = $resJson -> totalCount;
                $tempCount = count($resJson -> data);
                while($totalCount - $tempCount > 0){
                    $params['start'] = $tempCount;
                    curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
                    $result2 = curl_exec($curl);
                    $resJson2 = json_decode($result2);
                    for($i = 0;$i < count($resJson2 -> data);$i++){
                        $syn_node[$resJson2 -> data[$i] -> code] = $resJson2 -> data[$i];
                    }
                    $tempCount = $tempCount + count($resJson2 -> data);
                };
            }else{
                echo json_encode(array('status'=>false,'msg'=>$resJson -> errMsg));
            }
        }
        curl_close($curl);
        $redis -> set("syn_node",count($syn_node) > 0 ? json_encode($syn_node) : json_encode(new stdClass));
    }
    $resObj = array(
        "status" => true,
		"newadd" => 0,
		"expire" => 0,
		"changed" => 0,
		"unchanged" => 0
	);
    
	$syn_node_cache = json_decode($redis -> get("syn_node"));
    $sql = "select a.F_NodeCode,a.F_NodeNo,a.F_NodeName,b.F_TemplateID,a.F_Location,a.F_Remark from tb_A_IoTNode a left outer join tb_A_Template b on a.F_TemplateCode = b.F_TemplateCode where a.F_AppCode = " . $app;
	$resSet = $db -> query($sql);
    $resMap = array();
    for($i = 0;$i < count($resSet);$i++){
        $node_no = $resSet[$i] -> F_NodeNo;
        if(property_exists($syn_node_cache,$node_no)){
            $node = $syn_node_cache -> $node_no;
            if($resSet[$i] -> F_NodeNo == $node -> code
                && $resSet[$i] -> F_NodeName == $node -> name
                 && $resSet[$i] -> F_TemplateID == $node -> deviceTemplateId
                  && $resSet[$i] -> F_Location == $node -> installAddress
                   && $resSet[$i] -> F_Remark == $node -> remark){
                $resObj["unchanged"]++;
            }else{
                $resObj["changed"]++;
            }
        }else{
            $resObj["expire"]++;
        }
        $resMap[$resSet[$i] -> F_NodeNo] = $resSet[$i];
    }
    foreach($syn_node_cache as $node_code => $node){
        if(!array_key_exists($node_code,$resMap)){
            $resObj["newadd"]++;
        }
    }
    echo json_encode($resObj);    
?>

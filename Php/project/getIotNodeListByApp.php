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
    $tag = $_POST["tag"];
    $page = $_POST["page"];
    $page_num = $_POST["page_num"];
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
		"newadd" => array("total" => 0,"count" => 0,"data" => array()),
		"expire" => array("total" => 0,"count" => 0,"data" => array()),
		"changed" => array("total" => 0,"count" => 0,"data" => array()),
		"unchanged" => array("total" => 0,"count" => 0,"data" => array())
	);
    
	$syn_node_cache = json_decode($redis -> get("syn_node"));
	$tplSet = $db -> query("select F_TemplateID,F_TemplateName from tb_A_Template");
    $tplMap = array();
    for($i = 0;$i < count($tplSet);$i++){
        $tplMap[$tplSet[$i] -> F_TemplateID] = $tplSet[$i];
    }
    $sql = "select a.F_NodeNo as code,a.F_NodeName as name,b.F_TemplateID as deviceTemplateId,b.F_TemplateName as deviceTemplateName, a.F_Location as installAddress,a.F_Remark as remark from tb_A_IoTNode a left outer join tb_A_Template b on a.F_TemplateCode = b.F_TemplateCode where a.F_AppCode = " . $app;
	$resSet = $db -> query($sql);
    $resMap = array();
    for($i = 0;$i < count($resSet);$i++){
        $node_code = $resSet[$i] -> code;
        if(property_exists($syn_node_cache,$node_code)){
            $node = $syn_node_cache -> $node_code;
            if($resSet[$i] -> name == $node -> name
                 && $resSet[$i] -> deviceTemplateId == $node -> deviceTemplateId
                  && $resSet[$i] -> installAddress == $node -> installAddress
                   && $resSet[$i] -> remark == $node -> remark){
                array_push($resObj["unchanged"]["data"],$resSet[$i]);
                $resObj["unchanged"]["count"]++;
            }else{
                if($resSet[$i] -> name != $node -> name){
                    $resSet[$i] -> name = '<font color="red" title="变为：' . $node -> name . '">' . $resSet[$i] -> name . '</font>';
                }
                if($resSet[$i] -> deviceTemplateId != $node -> deviceTemplateId){
                    $resSet[$i] -> deviceTemplateName = '<font color="red" title="变为：' . $node -> deviceTemplateId . '">' . $resSet[$i] -> deviceTemplateName . '</font>';
                }
                if($resSet[$i] -> installAddress != $node -> installAddress){
                    $resSet[$i] -> installAddress = '<font color="red" title="变为：' . $node -> installAddress . '">' . $resSet[$i] -> installAddress . '</font>';
                }
                if($resSet[$i] -> remark != $node -> remark){
                    $resSet[$i] -> remark = '<font color="red" title="变为：' . $node -> remark . '">' . $resSet[$i] -> remark . '</font>';
                }
                array_push($resObj["changed"]["data"],$resSet[$i]);
                $resObj["changed"]["count"]++;
            }
        }else{
            array_push($resObj["expire"]["data"],$resSet[$i]);
            $resObj["expire"]["count"]++;
        }
        $resMap[$resSet[$i] -> code] = $resSet[$i];
    }
    foreach($syn_node_cache as $node_code => $node){
        if(!array_key_exists($node_code,$resMap)){
            $node -> deviceTemplateName = array_key_exists($node -> deviceTemplateId,$tplMap) ? $tplMap[$node -> deviceTemplateId] -> F_TemplateName : $node -> deviceTemplateId;
            array_push($resObj["newadd"]["data"],$node);
            $resObj["newadd"]["count"]++;
        }
    }
    $resObj["newadd"]["total"] = ceil($resObj["newadd"]["count"] / $page_num);
    $resObj["expire"]["total"] = ceil($resObj["expire"]["count"] / $page_num);
    $resObj["changed"]["total"] = ceil($resObj["changed"]["count"] / $page_num);
    $resObj["unchanged"]["total"] = ceil($resObj["unchanged"]["count"] / $page_num);
    
    $result = new stdClass;
    $result -> status = true;
    $result -> total = $resObj[$tag]["total"];
    $result -> count = $resObj[$tag]["count"];
    $result -> data = array();
    for($i = $page_num * ($page - 1);$i < $resObj[$tag]["count"] && $i < $page_num * $page;$i++){
        array_push($result -> data,$resObj[$tag]["data"][$i]);
    }
    echo json_encode($result);
?>

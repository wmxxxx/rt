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
	$tpl = $_POST["tpl"];
	$sql = "select a.F_RouterIP,a.F_RouterPort,b.F_AppID,b.F_SecretKey from dbo.tb_A_IoTRouter a,dbo.tb_A_IoTApp b where b.F_AppCode = '" . $app . "' and a.F_RouterCode = b.F_RouterCode";
	$result = $db -> query($sql);
    $ip = $result[0] -> F_RouterIP;
    $port = $result[0] -> F_RouterPort;
    $key = $result[0] -> F_SecretKey;
    $id = $result[0] -> F_AppID;
    
    $utc = time();
    $temp = array((string)$utc, $key, $id);
    sort($temp);
    $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]),'deviceTemplateId' => $tpl);
    $url = $ip . ':' . $port . "/app/" . $id . "/api/protected/getDeviceTemplateById?";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url . http_build_query($sign));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    $result = curl_exec($curl);
    if($result === false){
        echo json_encode(array('status'=>false,'msg'=>curl_error($curl)));
    }else{
        echo json_encode(array('status'=>true,'data'=>$result));
    }
    curl_close($curl);
?>

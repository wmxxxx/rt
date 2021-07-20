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
	
	$app = $_POST["app"];
	$etype = $_POST["etype"];
	$dtype = $_POST["dtype"];
	$status = $_POST["status"];
	$key = $_POST["key"];
    $page = $_POST["page"];
    $page_num = $_POST["page_num"];
	$sql = "exec proc_A_GetIotNodeList '" . $app . "','" . $etype . "','" . $dtype . "','" . $key . "'";
	$resSet = $db -> query($sql);
    
    $result = new stdClass;
    $result -> data = array();
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    if($status == ''){
        for($i = $page_num * ($page - 1);$i < count($resSet) && $i < $page_num * $page;$i++){
            $node_code = $resSet[$i] -> F_NodeCode;
            if($node_cache && property_exists($node_cache,$node_code)){
                $resSet[$i] -> F_OnlineFlag = $node_cache -> $node_code -> online;
            }else{
                $resSet[$i] -> F_OnlineFlag = null;
            }
            array_push($result -> data,$resSet[$i]);
        }
        $result -> total = ceil(count($resSet) / $page_num);
        $result -> count = count($resSet);
    }else{
        $data = array();
        for($i = 0;$i < count($resSet);$i++){
            $node_code = $resSet[$i] -> F_NodeCode;
            if($node_cache && ($status == $node_cache -> $node_code -> online)){
                $resSet[$i] -> F_OnlineFlag = $node_cache -> $node_code -> online;
                array_push($data,$resSet[$i]);
            }
        }
        $result -> data = array_slice($data,$page_num * ($page - 1),$page_num);
        $result -> total = ceil(count($data) / $page_num);
        $result -> count = count($data);
    }
	echo json_encode($result);
?>

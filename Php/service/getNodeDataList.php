<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    function sortNode($a, $b)
    {
        if(strcmp($a -> commtime, $b -> commtime) == 0){
            if(strcmp($a -> energy_type_name, $b -> energy_type_name) == 0){
                return strcmp($a -> device_type_name, $b -> device_type_name);
            }else{
                return strcmp($a -> energy_type_name, $b -> energy_type_name);
            }
        }else{
            return strcmp($b -> commtime,$a -> commtime);
        }
    }

	$etype = $_POST["etype"];
	$dtype = $_POST["dtype"];
	$keyword = $_POST["keyword"];
    
    $result = array();
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));

    foreach ($node_cache as $node){
        $match = true;
        if($etype != '' && $etype != $node -> energy_type_id){
            $match = false;
        }
        if($dtype != '' && $match && $dtype != $node -> device_type_id){
            $match = false;
        }
        if($keyword != '' && $match && strpos($node -> name,$keyword) == false){
            $match = false;
        }
        if($match){
            $data = new stdClass;
            $data -> code = $node -> code;
            $data -> name = $node -> name;
            $data -> device_type_name = $node -> device_type_name;
            $data -> energy_type_name = $node -> energy_type_name;
            $data -> online = $node -> online;
            $data -> commtime = $node -> commtime;
            array_push($result,$data);
        }
    }
    usort($result, "sortNode");
	echo json_encode($result);
?>

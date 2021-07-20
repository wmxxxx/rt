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
	
	$plugin = $_POST["plugin"];
	$fun = $_POST["fun"];
	$app = $_POST["app"];
	$energy_node = $_POST["energy_node"];
	$energy_id = $_POST["energy_id"];
	$device_node = $_POST["device_node"];
	$device_id = $_POST["device_id"];
	$tpl = $_POST["filter"];
	$online = $_POST["online"];
    $user = $_SESSION['user']['code'];
	$sql = "exec proc_B_GetEntityTreeData " . $plugin . "," . $fun . ",'" . $app . "'," . $user . "," . $energy_node . ",'" . $energy_id . "'," . $device_node . ",'" . $device_id . "','" . $tpl . "'";
    $result = $db -> query($sql);
    $resArray = array();
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    
    foreach ($result as $node){
        if($online == 'true'){
            if($node -> av){
                $node_id = $node -> id;
                if($node_cache && property_exists($node_cache,$node_id)){
                    $node -> online = $node_cache -> $node_id -> online;
                }else{
                    $node -> online = false;
                }
            }else{
                $node -> online = true;
            }
            array_push($resArray,$node);
        }else{
            $node -> online = true;
            array_push($resArray,$node);
        }
    }
	echo json_encode($resArray);
?>

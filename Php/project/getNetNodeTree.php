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
	
    $sql = "exec proc_A_GetNetNodeTree";
	$result = $db -> query($sql);

    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    foreach ($result as $node){
        if($node -> type == "node"){
            $node_id = $node -> id;
            if($node_cache && property_exists($node_cache,$node_id)){
                $node -> OnlineFlag = $node_cache -> $node_id -> online;
            }else{
                $node -> OnlineFlag = null;
            }
        }
    }
	echo json_encode($result);
?>

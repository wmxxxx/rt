<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    
    $result = new stdClass;
    $result -> total_node = 0;
    $result -> online_node = 0;
    $result -> total_param = 0;
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    $node_param_cache = json_decode($redis -> get("node_param"));

    if($node_cache){
        foreach ($node_cache as $node_code => $node){
            $result -> total_node++;
            if($node -> online == 1) $result -> online_node++;
        }
    }
    if($node_param_cache){
        foreach ($node_param_cache as $node_code => $node_param){
            $param_data_cache = json_decode($redis -> get($node_code));
            if($param_data_cache){
                foreach ($param_data_cache as $node_code_label => $param_data){
                    $result -> total_param++;
                }
            }
        }
    }
	echo json_encode($result);
?>

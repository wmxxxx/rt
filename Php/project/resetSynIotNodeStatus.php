<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
	if($redis -> delete("syn_node")){
        echo json_encode(true);
    }else{
        echo json_encode(false);
    } 
?>

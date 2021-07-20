<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    date_default_timezone_set("PRC");
    
    $result = new stdClass;
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $run_status = $redis -> get("run_status");
    $run_timestamp = $redis -> get("run_timestamp");
    if($run_status == 1 && time() - $run_timestamp < 3600){
        $result -> run_status = 1;
        $result -> time = $redis -> get("start_time");
        $result -> timestamp = time() - $redis -> get("start_timestamp");
    }else {
        $result -> run_status = 0;
        $result -> time = $redis -> get("stop_time");
        $result -> timestamp = time() - $redis -> get("stop_timestamp");
    }
	echo json_encode($result);
?>

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
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $redis -> set("run_cmd","start");
    $redis -> set("run_status",1);

    include_once '../task/task_CommEngine.php';
	//echo json_encode(array('status' => 1,'msg' => ''));
?>

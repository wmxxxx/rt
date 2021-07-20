<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    
	$task = $_POST["task"];
	$sys = $_POST["sys"];
	$fun = $_POST["fun"];
    try
    {
        $msg = '';
        if(file_exists("task/" . $task . ".php")){
            include_once("task/" . $task . ".php");
            eval("\$msg=" . $task . "('" . $sys . "','" . $fun . "');");
        }
        echo $msg;
    }
    catch(Exception $e)
    {
        echo '执行计划任务发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
?>
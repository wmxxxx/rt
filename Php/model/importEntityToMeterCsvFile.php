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
	ini_set('date.timezone','Asia/Shanghai');
	
    session_start();
    $resObj = array("status" => true);
    try{
        $sql = "exec proc_B_ImportEntityToMeterCsvFile '" . $_SESSION['user']['id'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        $db -> multi_query($sql);
        echo json_encode(array("status" => true));
    }catch (Exception $e) {
        echo json_encode(array("status" => false));
    }
?>

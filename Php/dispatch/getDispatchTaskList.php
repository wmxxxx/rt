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
	
	$type = $_POST["type"];
    $start = $_POST["start"] . ' 00:00:00';
    $end = $_POST["end"] . ' 23:59:59';
    $page = $_POST["page"];
    $page_num = $_POST["page_num"];
    
	$sql = "exec proc_D_GetDispatchTaskList '" . $type . "','" . $start . "','" . $end . "'";
	$resSet = $db -> query($sql);
    
    $result = new stdClass;
    $result -> count = count($resSet);
    $result -> total = ceil(count($resSet) / $page_num);
    $result -> data = array();
    for($i = $page_num * ($page - 1);$i < count($resSet) && $i < $page_num * $page;$i++){
        array_push($result -> data,$resSet[$i]);
    }
	echo json_encode($result);
?>

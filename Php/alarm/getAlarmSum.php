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
	
	$dtype = $_POST["dtype"];
    $start = $_POST["start"];
    $end = $_POST["end"];
    $rank = $_POST["rank"];
    
	$sql = "exec proc_A_GetEventSum '" . $dtype . "','" . $start . "','" . $end . "','" . $rank . "'," . $_SESSION['user']['code'];
	$result = $db -> query($sql);
	echo json_encode($result);
?>

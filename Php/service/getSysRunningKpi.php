<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/unit.php");
	
    $cpu = SystemRun::getCpuUsage();
    $memory = SystemRun::getMemoryUsage();
    $data = new stdClass;
    $data -> cpu = $cpu;
    $data -> memory = $memory['usage'];
	echo json_encode($data);
?>

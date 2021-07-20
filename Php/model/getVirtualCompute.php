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
    
	$ncode = $_POST["ncode"];
	$vlabel = $_POST["vlabel"];
	$sql = "select F_FromulaHtml from tb_B_VirtualCompute where F_VirtualNCode= " . $ncode . " and F_VirtualVLabel='" . $vlabel . "'";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

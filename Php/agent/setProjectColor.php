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
	
	$code = $_POST["code"];
	$color = $_POST["color"];
    $sql = "update tb_A_Agent set F_ProjectColor = '#" . $color . "' where F_AgentCode = " . $code;
    echo json_encode($db -> execute($sql));
?>

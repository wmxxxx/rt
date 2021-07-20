<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2017-07-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$no = $_POST["no"];
	$tree = $_POST["tree"];
	$obj = $_POST["obj"];
	$user = $_POST["user"];
    $sql = "exec proc_A_ProjectToTree '" . $no . "','" . $tree . "','" . $obj . "'";
    echo json_encode($db -> execute($sql));
?>

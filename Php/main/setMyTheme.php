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
    $name = $_POST["name"];
    
	$sql = "update tb_A_BookType set F_TypeName='" . $name . "' where F_TypeNo=" . $code;
    echo json_encode($db -> execute($sql));
?>

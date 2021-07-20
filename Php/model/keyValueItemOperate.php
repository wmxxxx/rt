<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$operFlg = $_POST["operFlg"];
	$code = $_POST["code"];
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $key = $_POST["txt_Key"];
	    $value = $_POST["txt_Value"];
	    $sql = "exec proc_B_KeyValueListOperate '" . $operFlg . "','" . $code . "','" . $key . "','" . $value . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $key = $_POST["txt_Key"];
	    $value = $_POST["txt_Value"];
	    $sql = "exec proc_B_KeyValueListOperate '" . $operFlg . "','" . $code . "','" . $key . "','" . $value . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
	    $key = $_POST["txt_Key"];
	    $sql = "exec proc_B_KeyValueListOperate '" . $operFlg . "','" . $code . "','" . $key . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

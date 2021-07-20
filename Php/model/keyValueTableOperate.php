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
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $name = $_POST["txt_DictionaryName"];
	    $sql = "exec proc_B_KeyValueTableOperate '" . $operFlg . "',null,'" . $name . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["txt_DictionaryName"];
	    $sql = "exec proc_B_KeyValueTableOperate '" . $operFlg . "','" . $code . "','" . $name . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
        $code = $_POST["code"];
	    $sql = "exec proc_B_KeyValueTableOperate '" . $operFlg . "','" . $code . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$type = $_POST["type"];
	$user = $_POST["user"];
    if($type == "1"){
	    $name = $_POST["text_DictTreeName"];
        $memo = $_POST["text_DictTreeMemo"];
	    $sql = "exec proc_B_DictTreeTypeOperate '" . $type . "',null,'" . $name . "','" . $memo . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($type == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["text_DictTreeName"];
        $memo = $_POST["text_DictTreeMemo"];
	    $sql = "exec proc_B_DictTreeTypeOperate '" . $type . "','" . $code . "','" . $name . "','" . $memo . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "3"){
        $code = $_POST["code"];
	    $sql = "exec proc_B_DictTreeTypeOperate '" . $type . "','" . $code . "',null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

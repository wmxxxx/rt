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
	$oper = $_POST["oper"];
	$prono = $_POST["prono"];
	$protag = $_POST["protag"];
    if($oper == "1"){
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $index = $_POST["index"];
        $parent = $_POST["parent"];
        $sql = "exec proc_A_HelpOperate '" . $type . "','" . $oper . "','" . $prono . "',null,'" . $tag . "','" . $name . "'," . $index . "," . $parent;
	}else if($oper == "2"){
	    $code = $_POST["code"];
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $index = $_POST["index"];
        $parent = $_POST["parent"];
        $sql = "exec proc_A_HelpOperate '" . $type . "','" . $oper . "','" . $prono . "'," . $code . ",'" . $tag . "','" . $name . "'," . $index . "," . $parent;
    }else if($oper == "3"){
	    $code = $_POST["code"];
        $tag = $_POST["tag"];
	    $sql = "exec proc_A_HelpOperate '" . $type . "','" . $oper . "','" . $prono . "'," . $code . ",null,null,null,null";
    }
    echo json_encode($db -> execute($sql));
?>

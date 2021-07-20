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
	include_once("../lib/file.php");

	$type = $_POST["type"];
	$oper = $_POST["oper"];
	$prono = $_POST["prono"];
	$protag = $_POST["protag"];
    if($oper == "1"){
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $abbr = $_POST["abbr"];
	    $index = $_POST["index"];
	    $position = $_POST["position"];
        $parent = $_POST["parent"];
        $fun = $_POST["fun"];
        if($fun == "" || $fun == null){
            $sql = "exec proc_A_MenuOperate '" . $type . "','" . $oper . "','" . $prono . "',null,'" . $tag . "','" . $name . "','" . $abbr . "'," . $index . ",'"  . $position . "'," . $parent . ",null";
        }else{
            $parray = explode("-",$fun);
            $sql = "exec proc_A_MenuOperate '" . $type . "','" . $oper . "','" . $prono . "',null,'" . $tag . "','" . $name . "','" . $abbr . "'," . $index . ",'"  . $position . "'," . $parent . "," . $parray[0];
        }
	}else if($oper == "2"){
	    $code = $_POST["code"];
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $abbr = $_POST["abbr"];
	    $index = $_POST["index"];
        $parent = $_POST["parent"];
        $fun = $_POST["fun"];
        if($fun == "" || $fun == null){
	        $sql = "exec proc_A_MenuOperate '" . $type . "','" . $oper . "','" . $prono . "'," . $code . ",'" . $tag . "','" . $name . "','" . $abbr . "'," . $index . ",''," . $parent . ",null";
        }else{
            $parray = explode("-",$fun);
            $sql = "exec proc_A_MenuOperate '" . $type . "','" . $oper . "','" . $prono . "'," . $code . ",'" . $tag . "','" . $name . "','" . $abbr . "'," . $index . ",''," . $parent . "," . $parray[0];
        }
    }else if($oper == "3"){
	    $code = $_POST["code"];
        $tag = $_POST["tag"];
        $fun = $_POST["fun"];
        $frame = $_POST["frame"];
        if($fun != "" && $fun != null){
            $parray = explode("-",$fun);
            File::delfile("../../Project/" . $protag . "/Resources/images/nav/" . $frame . "/" . $tag . ".png");
        }
	    $sql = "exec proc_A_MenuOperate '" . $type . "','" . $oper . "','" . $prono . "'," . $code . ",null,null,null,null,null,null,null";
    }
    echo json_encode($db -> execute($sql));
?>

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
	
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$oper = $_POST["oper"];
	$code = $_POST["code"];
	$user = $_POST["user"];
    if($oper == "1"){
	    $name = $_POST["name"];
        $tag = $_POST["tag"];
        $type = $_POST["type"];
        $plugin = $_POST["plugin"];
        $sql = "exec proc_A_FunctionOperate '" . $oper . "','" . $code . "','" . $name . "','" . $tag . "','"  . $type . "','" . $plugin . "','" . $user . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
	}else if($oper == "2"){
	    $name = $_POST["name"];
        $tag = $_POST["tag"];
        $type = $_POST["type"];
        $plugin = $_POST["plugin"];
        $sql = "exec proc_A_FunctionOperate '" . $oper . "','" . $code . "','" . $name . "','" . $tag . "','"  . $type . "','" . $plugin . "','" . $user . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($oper == "3"){
	    $sql = "exec proc_A_FunctionOperate '" . $oper . "','" . $code . "',null,null,null,null,null";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }
?>

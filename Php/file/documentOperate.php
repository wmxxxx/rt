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
	$user = $_POST["user"];
    
    if($oper == '1'){
        $name = $_POST["name"];
        $parent = $_POST["parent"];
        $sql = "exec proc_A_DocumentOperate '" . $oper . "',null,'" . $name . "','folder','" . $parent . "',null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($oper == '2'){
	    $code = $_POST["code"];
        $name = $_POST["name"];
        $type = $_POST["type"];
        if($type == 'folder'){
            $sql = "exec proc_A_DocumentOperate '" . $oper . "'," . $code . ",'" . $name . "','folder',null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }else{
            $sql = "exec proc_A_DocumentOperate '" . $oper . "'," . $code . ",null,'file',null,'" . $name . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }
    }else if($oper == '3'){
	    $code = $_POST["code"];
        $sql = "exec proc_A_DocumentOperate '" . $oper . "'," . $code . ",null,'folder',null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
	if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

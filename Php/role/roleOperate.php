<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$type = $_POST["type"];
	$user = $_POST["user"];
    if($type == "1"){
	    $name = $_POST["name"];
	    $group = $_POST["group"];
	    $kanban = $_POST["kanban"];
	    $sql = "exec proc_A_RoleOperate '" . $type . "',null,'" . $name . "','" . $group . "','" . $kanban . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($type == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["name"];
	    $group = $_POST["group"];
	    $kanban = $_POST["kanban"];
	    $sys = $_POST["sys"];
	    $sql = "exec proc_A_RoleOperate '" . $type . "','" . $code . "','" . $name . "','" . $group . "','" . $kanban . "','" . $sys . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "3"){
        $code = $_POST["code"];
	    $sql = "exec proc_A_RoleOperate '" . $type . "','" . $code . "',null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

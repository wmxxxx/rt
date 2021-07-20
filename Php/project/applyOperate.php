<?php
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
	$code = $_POST["code"];
    if($type == "1"){
	    $router = $_POST["router"];
	    $id = $_POST["id"];
	    $name = $_POST["name"];
	    $key = $_POST["key"];
	    $sql = "exec proc_A_ApplyOperate '" . $type . "'," . $router . ",null,'" . $id . "','" . $name . "','" . $key . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($type == "2"){
	    $router = $_POST["router"];
	    $id = $_POST["id"];
	    $name = $_POST["name"];
	    $key = $_POST["key"];$sql = "exec proc_A_ApplyOperate '" . $type . "'," . $router . ",'" . $code . "','" . $id . "','" . $name . "','" . $key . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "3"){
	    $sql = "exec proc_A_ApplyOperate '" . $type . "',null,'" . $code . "',null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

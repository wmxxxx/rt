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
	$node = $_POST["node"];
	$label = $_POST["label"];
	$value = $_POST["value"];
	$user = $_POST["user"];
    $sql = "exec proc_A_DeviationOperate " . $node . ",'" . $label . "'," . $value . ",'"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

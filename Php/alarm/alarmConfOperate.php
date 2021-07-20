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
	$type = $_POST["type"];
	$check = $_POST["check"];
    $offline_time = array_key_exists('offline_time',$_POST) ? $_POST["offline_time"] : '';
    $disk_space = array_key_exists('disk_space',$_POST) ? $_POST["disk_space"] : '';
    $to_user = array_key_exists('to_user',$_POST) ? $_POST["to_user"] : '';
    $is_wechat = array_key_exists('is_wechat',$_POST) ? $_POST["is_wechat"] : '';
    $is_email = array_key_exists('is_email',$_POST) ? $_POST["is_email"] : '';
	$user = $_POST["user"];
    
    $sql = "exec proc_D_AlarmConfOperate " . $type . "," . $check . ",'" . $offline_time . "','" . $disk_space . "','" . $to_user . "','" . $is_wechat . "','" . $is_email . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

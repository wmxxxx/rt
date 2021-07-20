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
        $type = $_POST["type"];
        $detail = $_POST["detail"];
        $etime = $_POST["etime"];
        $duser = $_POST["duser"];
        $sys = $_POST["sys"];
        $sql = "exec proc_D_DispatchTaskOperate '" . $oper . "',null,'" . $type . "','" . $detail . "','" . $etime . "','" . $duser . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys . "'";
    }else if($oper == '2'){
        $code = $_POST["code"];
        $type = $_POST["type"];
        $detail = $_POST["detail"];
        $etime = $_POST["etime"];
        $duser = $_POST["duser"];
        $sys = $_POST["sys"];
        $sql = "exec proc_D_DispatchTaskOperate '" . $oper . "'," . $code . ",'" . $type . "','" . $detail . "','" . $etime . "','" . $duser . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys . "'";
    }else if($oper == '3'){
	    $code = $_POST["code"];
        $sql = "exec proc_D_DispatchTaskOperate '" . $oper . "'," . $code . ",null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "',''";
    }else if($oper == '4'){
        $code = $_POST["code"];
        $type = $_POST["type"];
        $detail = $_POST["detail"];
        $etime = $_POST["etime"];
        $duser = $_POST["duser"];
        $sys = $_POST["sys"];
        $sql = "exec proc_D_DispatchTaskOperate '" . $oper . "'," . $code . ",'" . $type . "','" . $detail . "','" . $etime . "','" . $duser . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys . "'";
    }
	if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo json_encode(array('status' => 0,'msg' => '数据保存失败！'));
    }
?>

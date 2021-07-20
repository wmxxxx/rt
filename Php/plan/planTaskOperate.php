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
	if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$oper = $_POST["oper"];
	$code = $_POST["code"];
	$user = $_POST["user"];
    if($oper == "1"){
	    $name = $_POST["name"];
        $tag = $_POST["tag"];
	    $sys = $_POST["sys"];
	    $fun = $_POST["fun"];
        $type = $_POST["type"];
        $y_time = $_POST["y_time"];
        $m_time = $_POST["m_time"];
        $d_time = $_POST["d_time"];
        $w_day = $_POST["w_day"];
        $w_time = $_POST["w_time"];
        $c_time = $_POST["c_time"];
        $sql = "exec proc_A_PlanTaskOperate '" . $oper . "','" . $code . "','" . $name . "','" . $tag . "','"  . $sys . "','" . $fun . "','" . $type . "','" . $y_time . "','" . $m_time . "','" . $d_time . "','" . $w_day . "','" . $w_time . "','" . $c_time . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
	}else if($oper == "2"){
        $name = $_POST["name"];
        $tag = $_POST["tag"];
	    $sys = $_POST["sys"];
	    $fun = $_POST["fun"];
        $type = $_POST["type"];
        $y_time = $_POST["y_time"];
        $m_time = $_POST["m_time"];
        $d_time = $_POST["d_time"];
        $w_day = $_POST["w_day"];
        $w_time = $_POST["w_time"];
        $c_time = $_POST["c_time"];
        $sql = "exec proc_A_PlanTaskOperate '" . $oper . "','" . $code . "','" . $name . "','" . $tag . "','"  . $sys . "','" . $fun . "','" . $type . "','" . $y_time . "','" . $m_time . "','" . $d_time . "','" . $w_day . "','" . $w_time . "','" . $c_time . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($oper == "3"){
        $tag = $_POST["tag"];
        $sql = "exec proc_A_PlanTaskOperate '" . $oper . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }
?>

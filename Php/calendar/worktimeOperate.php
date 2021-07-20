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
	$date = $_POST["date"];
	$user = $_POST["user"];
    $wstart = $_POST["wstart"];
    $wend = $_POST["wend"];
    $tstart1 = $_POST["tstart1"];
    $tend1 = $_POST["tend1"];
    $tstart2 = $_POST["tstart2"];
    $tend2 = $_POST["tend2"];
    $year = $date == '' ? 9999 : substr($date,0,4);
    $month = $date == '' ? 99 : substr($date,5,2);
    $sql = "exec proc_D_SplitTimeOperate '" . $year . "','" . $month . "','" . $wstart . "','"  . $wend . "','" . $tstart1 . "','"  . $tend1 . "','" . $tstart2 . "','"  . $tend2 . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";

    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

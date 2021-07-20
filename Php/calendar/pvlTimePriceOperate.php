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
	$energy = $_POST["energy"];
    $sql = "";
    if($type == 'P'){
        $pstart1 = $_POST["pstart1"];
        $pend1 = $_POST["pend1"];
        $pstart2 = $_POST["pstart2"];
        $pend2 = $_POST["pend2"];
        $pprice = $_POST["pprice"];
        $sql = "exec proc_D_PvlTimePriceOperate '" . $type . "','" . $energy . "','" . $pstart1 . "','" . $pend1 . "','"  . $pstart2 . "','" . $pend2 . "','"  . $pprice . "',null,null,null,null,'" . $_SESSION['user']['id'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == 'V'){
        $vstart = $_POST["vstart"];
        $vend = $_POST["vend"];
        $vprice = $_POST["vprice"];
        $sql = "exec proc_D_PvlTimePriceOperate '" . $type . "','" . $energy . "',null,null,null,null,null,'" . $vstart . "','"  . $vend . "','"  . $vprice . "',null,'" . $_SESSION['user']['id'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == 'L'){
        $lprice = $_POST["lprice"];
        $sql = "exec proc_D_PvlTimePriceOperate '" . $type . "','" . $energy . "',null,null,null,null,null,null,null,null,'"  . $lprice . "','" . $_SESSION['user']['id'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

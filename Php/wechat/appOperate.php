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
	$user = $_POST["user"];
    if($oper == "1"){
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $type = $_POST["type"];
	    $fun = $_POST["fun"];
        $parray = explode("-",$fun);
        $sql = "exec proc_A_MobileAppOperate '" . $oper . "',null,'" . $name . "','" . $tag . "','" . $type . "'," . $parray[0] . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($oper == "2"){
	    $code = $_POST["code"];
	    $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $type = $_POST["type"];
	    $fun = $_POST["fun"];
        $parray = explode("-",$fun);
        $sql = "exec proc_A_MobileAppOperate '" . $oper . "'," . $code . ",'" . $name . "','" . $tag . "','" . $type . "'," . $parray[0] . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($oper == "3"){
	    $code = $_POST["code"];
        $tag = $_POST["tag"];
        File::delfile("../../Resources/images/wechat/" . $tag . ".png");
	    $sql = "exec proc_A_MobileAppOperate '" . $oper . "'," . $code . ",null,null,null,null,null,null";
    }
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

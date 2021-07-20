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
    $code = $_POST["code"];
    $user = $_POST["user"];
    $token = $_POST["token"];
    if($type == "1" || $type == "2"){
	    $utype = $_POST["utype"];
	    $id = $_POST["id"];
	    $name = $_POST["name"];
        $pass = $_POST["pass"];
        $email = $_POST["email"];
        $mobile = $_POST["mobile"];
        $ip = $_POST["ip"];
        $start = $_POST["start"];
        $end = $_POST["end"];
        if(sha1($id . '@reatgreen') == $token){
            $sql = "exec proc_A_UserOperate '" . $type . "'," . ($code == '' ? 'null' : $code) . ",'" . $id . "','" . $name . "','" . $pass . "','" . $utype . "','" . $email . "','"  . $mobile . "','" . $ip . "','" . $start . "','" . $end . "',null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }else{
            echo '{"status":0,"msg":"非法的操作方式！"}';
        }
	    
	}else if($type == "3"){
        if(sha1($code . '@reatgreen') == $token){
            $sql = "exec proc_A_UserOperate '" . $type . "'," . $code . ",null,null,null,null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }else{
            echo '{"status":0,"msg":"非法的操作方式！"}';
        }
    }else{
        echo '{"status":0,"msg":"无效的参数！"}';
    }
?>

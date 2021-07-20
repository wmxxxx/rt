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
    if($type == "4"){
	    $name = $_POST["name"];
	    $email = $_POST["email"];
        $mobile = $_POST["mobile"];
	    $sql = "exec proc_A_UserOperate '" . $type . "','" . $code . "',null,'" . $name . "',null,'','" . $email . "','"  . $mobile . "',null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
	}else if($type == "5"){
        $pass = $_POST["pass"];
        $sql = "exec proc_A_UserOperate '" . $type . "','" . $code . "',null,null,'" . $pass . "',null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($type == "6"){
        $kanban = $_POST["kanban"];
        $fun = explode('@',$kanban);
        $sql = "exec proc_A_UserOperate '" . $type . "','" . $code . "',null,null,null,null,null,null,null,null,null,'" . $fun[0] . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            $_SESSION['user']['kanban'] = $kanban;
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }
?>

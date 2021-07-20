<?php
	/*
	 * Created on 2015-12-20
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
        $type = $_POST["type"];
	    $name = $_POST["name"];
	    $abbr = $_POST["abbr"];
        $url = $_POST["url"];
        $key = $_POST["key"];
        $des = $_POST["des"];
        if(File::initdir($tag)){
	        $sql = "exec proc_A_AgentOperate '" . $oper . "',null,'" . $name . "','" . $abbr . "','"  . $tag . "','" . $type . "','" . $url . "','" . $key . "','" . $des . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	        if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
    }else if($oper == "2"){
        $tag = $_POST["tag"];
	    $code = $_POST["code"];
	    $name = $_POST["name"];
        $type = $_POST["type"];
	    $abbr = $_POST["abbr"];
        $url = $_POST["url"];
        $key = $_POST["key"];
        $des = $_POST["des"];
	    $sql = "exec proc_A_AgentOperate '" . $oper . "','" . $code . "','" . $name . "','" . $abbr . "','"  . $tag . "','" . $type . "','" . $url . "','" . $key . "','" . $des . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($oper == "3"){
        $code = $_POST["code"];
        $tag = $_POST["tag"];
        if(File::deldir("../../Project/" . $tag . "/")){
	        $sql = "exec proc_A_AgentOperate '" . $oper . "','" . $code . "',null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
    }
?>

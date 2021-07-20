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
	$no = $_POST["no"];
	$user = $_POST["user"];
    if($oper == "1"){
        $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $abbr = $_POST["abbr"];
        $type = $_POST["type"];
        $des = $_POST["des"];
        $sfun = $_POST["sfun"];
        $gfun = $_POST["gfun"];
        if(File::initdir($tag)){
	        $sql = "exec proc_A_ProjectOperate '" . $oper . "','" . $no . "','" . $name . "','" . $abbr . "','"  . $tag . "','" . $type . "','" . $des . "','" . $sfun . "','" . $gfun . "'";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
	}else if($oper == "2"){
        $tag = $_POST["tag"];
	    $name = $_POST["name"];
	    $abbr = $_POST["abbr"];
        $type = $_POST["type"];
        $des = $_POST["des"];
        $sfun = $_POST["sfun"];
        $gfun = $_POST["gfun"];
	    $sql = "exec proc_A_ProjectOperate '" . $oper . "','" . $no . "','" . $name . "','" . $abbr . "','"  . $tag . "','" . $type . "','" . $des . "','" . $sfun . "','" . $gfun . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($oper == "3"){
        $tag = $_POST["tag"];
        if(File::deldir("../../Project/" . $tag . "/")){
	        $sql = "exec proc_A_ProjectOperate '" . $oper . "','" . $no . "',null,null,null,null,null,null,null";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
    }
?>

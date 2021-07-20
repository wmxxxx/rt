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
        $type = $_POST["type"];
        $cate = $_POST["cate"];
        $e_conf = $_POST["e_conf"];
        $d_conf = $_POST["d_conf"];
        $t_conf = $_POST["t_conf"];
        $conf = $_POST["conf"];
        $conf_type = $_POST["conf_type"];
        $guide = $_POST["guide"];
        if(File::makedir("../../Plugins/" . $tag . "/")){
            if($guide == 'html'){
                copy("../../Lib/plugin/index.php", "../../Plugins/" . $tag . "/" . "index.php");
            }
	        $sql = "exec proc_A_PluginOperate '" . $oper . "','" . $code . "','" . $name . "','" . $tag . "','"  . $type . "','" . $cate . "','" . $conf . "','" . $conf_type . "','" . $e_conf . "','" . $t_conf . "','" . $d_conf . "','" . $guide . "','" . $user . "'";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
	}else if($oper == "2"){
	    $name = $_POST["name"];
        $type = $_POST["type"];
        $cate = $_POST["cate"];
        $e_conf = $_POST["e_conf"];
        $d_conf = $_POST["d_conf"];
        $t_conf = $_POST["t_conf"];
        $conf = $_POST["conf"];
        $conf_type = $_POST["conf_type"];
        $guide = $_POST["guide"];
	    $sql = "exec proc_A_PluginOperate '" . $oper . "','" . $code . "','" . $name . "',null,'"  . $type . "','" . $cate . "','" . $conf . "','" . $conf_type . "','" . $e_conf . "','" . $t_conf . "','" . $d_conf . "','" . $guide . "','" . $user . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else if($oper == "3"){
        $tag = $_POST["tag"];
        if(File::deldir("../../Plugins/" . $tag . "/")){
	        $sql = "exec proc_A_PluginOperate '" . $oper . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null";
            if($db -> execute($sql)){
                echo json_encode(array('status' => 1,'msg' => ''));
            }else{
                echo '{"status":0,"msg":"数据保存失败！"}';
            }
        }
    }
?>

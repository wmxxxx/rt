<?php
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
    $code = $_POST["code"];
	$name = $_POST["name"];
	$type = $_POST["type"];
	$user = $_POST["user"];
    
    $file_path = "../../Resources/file/";
    
    if(unlink($file_path . iconv("UTF-8", "GB2312", $name . '.' . $type)))  
    {   
        $sql = "exec proc_A_DocumentOperate '3','" . $code . "',null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo json_encode(array('status' => 0,'msg' => '数据保存失败！'));
        }
    }else{
        echo json_encode(array('status' => 0,'msg' => '文件删除失败！'));
    }
?>

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
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$book = $_POST["book"];
	$image = $_POST["image"];
	$user = $_POST["user"];
    
    $file_path = "../../Resources/images/book/";
    
    if(unlink($file_path . $image))  
    {   
        $sql = "exec proc_A_BookOperate '4','" . $book . "',null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else{
        echo '{"status":0,"msg":"封面删除失败！"}';
    }
?>

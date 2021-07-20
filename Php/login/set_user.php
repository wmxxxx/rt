<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    
    $id = $_POST["id"];
    $pwd = $_POST["pwd"];
    $sql = "select id from dbo.sysobjects where id = object_id('tb_A_LoginUser')";
	$result = $db -> query($sql);
    if(count($result) == 0){
        echo json_encode(array('status' => -1));
        exit;
    }
	$sql = "insert into tb_A_LoginUser (F_UserCode,F_UserID,F_UserName,F_UserPwd,F_UserType,F_StartDate) values (dbo.fun_MakeSerialNum(),'" . $id . "','超级管理员','" . $pwd . "','1',getdate())";
	if($db -> execute($sql)){
        echo json_encode(array('status' => 1));
    }else{
        echo json_encode(array('status' => 0));
    }
?>

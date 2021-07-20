<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2015-12-11
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    
    $user_status = 1;
    $sql = "select id from dbo.sysobjects where id = object_id('tb_A_LoginUser')";
	$result = $db -> query($sql);
    if(count($result) == 0){
        $user_status = -1;
    }
    $sql = "select F_UserCode from dbo.tb_A_LoginUser where F_UserType = '1'";
	$result = $db -> query($sql);
    if(count($result) == 0){
        $user_status = 0;
    }
    echo json_encode(array("user_status" => $user_status));
?>

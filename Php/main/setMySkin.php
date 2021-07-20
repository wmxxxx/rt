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
	
    $user = $_POST["user"];
    $skin = $_POST["skin"];
    
	$sql = "delete from tb_A_UserToUI where F_UserCode = " . $user . " insert into tb_A_UserToUI values (" . $user . ",'" . $skin . "')";
    if($db -> execute($sql)){
        $_SESSION['user']['ui'] = $skin;
        echo json_encode(true);
    }else{
        echo json_encode(false);
    }
?>

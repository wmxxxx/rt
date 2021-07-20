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
	
    $oper = $_POST["oper"];
    $user = $_POST["user"];
    $app = $_POST["app"];
    
	$sql = "";
    if($oper == "0"){
        $sql = "delete from tb_A_MyApp where F_UserCode=" . $user . " and F_AppCode=" . $app;
    }else if($oper == "1"){
        $type = $_POST["type"];
        $size = $_POST["size"];
        $sql = "insert into tb_A_MyApp values (" . $user . "," . $app . "," . $type . "," . $size . ",getdate())";
    }else if($oper == "2"){
        $size = $_POST["size"];
        $sql = "update tb_A_MyApp set F_AppSize= " . $size . " where F_UserCode = " . $user . " and F_AppCode = " . $app;
    }
    echo json_encode($db -> execute($sql));
?>

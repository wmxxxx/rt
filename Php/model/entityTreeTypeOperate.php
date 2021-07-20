<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$operFlg = $_POST["operFlg"];
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $name = $_POST["txt_EntityTreeName"];
        $type = $_POST["rdo_EntityTreeType"];
        $memo = $_POST["txt_EntityTreeMemo"];
	    $sql = "exec proc_B_EntityTreeTypeOperate '" . $operFlg . "',null,'" . $name . "','" . $type . "','" . $memo . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["txt_EntityTreeName"];
        $type = $_POST["rdo_EntityTreeType"];
        $memo = $_POST["txt_EntityTreeMemo"];
	    $sql = "exec proc_B_EntityTreeTypeOperate '" . $operFlg . "','" . $code . "','" . $name . "','" . $type . "','" . $memo . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
        $code = $_POST["code"];
	    $sql = "exec proc_B_EntityTreeTypeOperate '" . $operFlg . "','" . $code . "',null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

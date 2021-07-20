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
    $treeNo = $_POST["treeNo"];
	$group = $_POST["group"];
	$property = $_POST["property"];
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $OrderNum = $_POST["txt_OrderNum"];
	    $DecimalDigits = $_POST["txt_DecimalDigits"];
        $Examples = $_POST["txt_ExamplesDes"];
        $IsNull = $_POST["IsNull"] == "false" ? 0 : 1;
	    $sql = "exec proc_B_DictTreePropertyOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $property . "','" . $OrderNum . "','" . $DecimalDigits . "','" . $IsNull . "','"  . $Examples . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $OrderNum = $_POST["txt_OrderNum"];
	    $DecimalDigits = $_POST["txt_DecimalDigits"];
        $Examples = $_POST["txt_ExamplesDes"];
        $IsNull = $_POST["IsNull"] == "false" ? 0 : 1;
	    $sql = "exec proc_B_DictTreePropertyOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $property . "','" . $OrderNum . "','" . $DecimalDigits . "','" . $IsNull . "','"  . $Examples . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
	    $sql = "exec proc_B_DictTreePropertyOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $property . "',null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

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
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $name = $_POST["text_GroupName"];
	    $tag = $_POST["text_GroupTag"];
        $objectGroup = $_POST["rdo_ObjectGroup"];
        $objectType = $_POST["objectType"];
        if($objectType == ''){
            $sql = "exec proc_B_DictTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $name . "','" . $objectGroup . "',null,'" . $tag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }else{
            $sql = "exec proc_B_DictTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $name . "','" . $objectGroup . "',"  . $objectType . ",'" . $tag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }	    
	}else if($operFlg == "2"){
	    $name = $_POST["text_GroupName"];
	    $tag = $_POST["text_GroupTag"];
        $objectGroup = $_POST["rdo_ObjectGroup"];
        $objectType = $_POST["objectType"] == '' ? null : $_POST["objectType"];
	    if($objectType == ''){
            $sql = "exec proc_B_DictTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $name . "','" . $objectGroup . "',null,'" . $tag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }else{
            $sql = "exec proc_B_DictTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "','" . $name . "','" . $objectGroup . "',"  . $objectType . ",'" . $tag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }
    }else if($operFlg == "3"){
	    $sql = "exec proc_B_DictTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $group . "',null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

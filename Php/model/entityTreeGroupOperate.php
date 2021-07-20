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
	$entity = $_POST["entity"];
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $name = $_POST["txt_EntityName"];
	    $sname = $_POST["txt_EntitySName"];
	    $order = $_POST["txt_EntityOrderTag"];
	    $maptag = $_POST["txt_EntityMapTag"];
        $template = $_POST["template"];
        $nodetpl = $_POST["nodetpl"];
        $deviceType = $_POST["deviceType"];
        $energyType = $_POST["energyType"];
        $isDisplay = $_POST["rdo_EntityDisplay"];
	    $sql = "exec proc_B_EntityTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $entity . "','" . $name . "','" . $sname . "','" . $template . "','" . $nodetpl . "','" . $energyType . "','" . $deviceType . "'," . $isDisplay . ",'" . $order . "','" . $maptag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $name = $_POST["txt_EntityName"];
	    $sname = $_POST["txt_EntitySName"];
	    $order = $_POST["txt_EntityOrderTag"];
	    $maptag = $_POST["txt_EntityMapTag"];
        $template = $_POST["template"];
        $nodetpl = $_POST["nodetpl"];
        $deviceType = $_POST["deviceType"];
        $energyType = $_POST["energyType"];
        $isDisplay = $_POST["rdo_EntityDisplay"];
	    $sql = "exec proc_B_EntityTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $entity . "','" . $name . "','" . $sname . "','" . $template . "','" . $nodetpl . "','" . $energyType . "','" . $deviceType . "'," . $isDisplay . ",'" . $order . "','" . $maptag . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
	    $sql = "exec proc_B_EntityTreeGroupOperate '" . $operFlg . "','" . $treeNo . "','" . $entity . "',null,null,null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

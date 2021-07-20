<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$type = $_POST["type"];
	$tpl = $_POST["tpl"];
	$user = $_POST["user"];
    if($type == "1"){
	    $name = $_POST["name"];
	    $label = $_POST["label"];
        $ptype = $_POST["ptype"];
        $property = $_POST["property"];
        $dtype = $_POST["dtype"];
        $ratio = $_POST["ratio"];
        $point = $_POST["point"];
        $dvalue = $_POST["dvalue"];
        $unit = $_POST["unit"];
        $rw = $_POST["rw"];
        $ccycle = $_POST["ccycle"];
        $formula = $_POST["formula"];
        $kv = $_POST["kv"];
        $storage = $_POST["storage"];
        $scycle = $_POST["scycle"];
        $lrange = $_POST["lrange"];
        $urange = $_POST["urange"];
        $lslope = $_POST["lslope"];
        $uslope = $_POST["uslope"];
        $bvalue = $_POST["bvalue"];
        $fvalue = $_POST["fvalue"];
        $svalue = $_POST["svalue"];
        $evalue = $_POST["evalue"];
	    $sql = "exec proc_A_ValueOperate '" . $type . "','" . $tpl . "',null,'" . $name . "','" . $label . "','"  . $ptype . "','" . $property . "','" . $dtype . "'," . $ratio . "," . $point . ",'" . $dvalue . "',N'" . $unit . "','" . $rw . "','" . $ccycle . "','" . $formula . "','" . $kv . "','" . $storage . "','" . $scycle . "','" . $lrange . "','" . $urange . "','" . $lslope . "','" . $uslope . "','" . $bvalue . "','" . $fvalue . "','" . $svalue . "','" . $evalue . "',null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($type == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["name"];
	    $label = $_POST["label"];
        $ptype = $_POST["ptype"];
        $property = $_POST["property"];
        $dtype = $_POST["dtype"];
        $ratio = $_POST["ratio"];
        $point = $_POST["point"];
        $dvalue = $_POST["dvalue"];
        $unit = $_POST["unit"];
        $rw = $_POST["rw"];
        $ccycle = $_POST["ccycle"];
        $formula = $_POST["formula"];
        $kv = $_POST["kv"];
        $storage = $_POST["storage"];
        $scycle = $_POST["scycle"];
        $lrange = $_POST["lrange"];
        $urange = $_POST["urange"];
        $lslope = $_POST["lslope"];
        $uslope = $_POST["uslope"];
        $bvalue = $_POST["bvalue"];
        $fvalue = $_POST["fvalue"];
        $svalue = $_POST["svalue"];
        $evalue = $_POST["evalue"];
	    $sql = "exec proc_A_ValueOperate '" . $type . "','" . $tpl . "','" . $code . "','" . $name . "','" . $label . "','"  . $ptype . "','" . $property . "','" . $dtype . "'," . $ratio . "," . $point . ",'" . $dvalue . "',N'" . $unit . "','" . $rw . "','" . $ccycle . "','" . $formula . "','" . $kv . "','" . $storage . "','" . $scycle . "','" . $lrange . "','" . $urange . "','" . $lslope . "','" . $uslope . "','" . $bvalue . "','" . $fvalue . "','" . $svalue . "','" . $evalue . "',null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "3"){
        $code = $_POST["code"];
	    $sql = "exec proc_A_ValueOperate '" . $type . "','" . $tpl . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "4"){
        $code = $_POST["code"];
        $display = $_POST["display"];
	    $sql = "exec proc_A_ValueOperate '" . $type . "','" . $tpl . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null," . $display . ",null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($type == "5"){
        $code = $_POST["code"];
        $order = $_POST["order"];
	    $sql = "exec proc_A_ValueOperate '" . $type . "','" . $tpl . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null," . $order . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

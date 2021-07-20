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
	ini_set('date.timezone','Asia/Shanghai');
	
    $tree = $_POST["tree"];
    $user = $_POST["user"];
    try{
        $sql = "select F_EntityID from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $tree . " and F_ParentID = 0";
	    $result = $db -> query($sql);
        if(count($result) == 0){
            echo json_encode(array("status" => -1));exit;
        }
        $sql = "exec proc_A_ImportNodeCsvFile " . $tree . ",'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        $db -> multi_query($sql);
        echo json_encode(array("status" => 1));
    }catch (Exception $e) {
        echo json_encode(array("status" => 0));
    }
?>

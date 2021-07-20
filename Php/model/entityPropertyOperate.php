<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$tree = $_POST["tree"];
	$entity = $_POST["entity"];
	$date = $_POST["date"];
	$user = $_POST["user"];
    
    $resObj = new stdClass();
    $resObj -> success = true;
    $resObj -> msg = "";
    
    foreach($_POST as $key => $val) {
        if($key != "tree" && $key != "entity" &&  $key != "date" && $key != "user"){
            $sql = "exec proc_B_EntityPropertyOperate '" . $tree . "','" . $entity . "','" . $key . "','" . $val . "','" . $date . "','"  . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            try{
                $db -> execute($sql);
            }catch(Exception $e){
                $resObj -> msg = $e->getMessage();
                echo json_encode($resObj);
            }
        }
    }
    echo json_encode($resObj);
?>

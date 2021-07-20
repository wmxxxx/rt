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
	$data = json_decode($_POST["data"],true);
	$user = $_POST["user"];
    $sql = "exec proc_A_SynTemplateOperate '" . $data["app"] . "','" . $data["id"] . "','" . $data["name"] . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "';";
    for($i = 0;$i < count($data["params"]);$i++){
        $sql = $sql . "exec proc_A_SynValueOperate '" . $data["app"] . "','" . $data["id"] . "','" . $data["params"][$i]["name"] . "','" . $data["params"][$i]["code"] . "','" . $data["params"][$i]["dataType"] . "','" . $data["params"][$i]["precision"] . "','" . $data["params"][$i]["unit"] . "','" . $data["params"][$i]["rwFlag"] . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "';";
    }
    if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => '设备模板同步成功！'));
    }else{
        echo '{"status":0,"msg":"数据保存失败！"}';
    }
?>

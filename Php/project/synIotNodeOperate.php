<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $app = $_POST["app"];
    $tag = $_POST["tag"];
    $nodestr = $_POST["node"];
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    if($tag == 'expire'){
        $sql = "delete from tb_A_IoTNode where F_NodeNo in (select F_ObjectID from dbo.fun_SplitByComma('" . $nodestr . "')) delete from tb_B_EntityTreeModel where F_EntityID in (select F_ObjectID from dbo.fun_SplitByComma('" . $nodestr . "'))";
        if($db -> execute($sql)){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }else{
        $status = true;
        $syn_node_cache = json_decode($redis -> get("syn_node"));
        $nodes = explode(",",$nodestr);
        foreach ($nodes as $code){ 
            $node = $syn_node_cache -> $code;
            $sql = "exec proc_A_SynNodeOperate '" . $app . "','" . $node -> deviceTemplateId . "','" . $node -> code . "','" . $node -> id . "','" . $node -> name . "','" . $node -> installAddress . "','" . $node -> remark . "','" . $_SESSION['user']['id'] . "','" . $_SERVER["REMOTE_ADDR"] . "';";
            if(!$db -> execute($sql)){
                $status = false;
            }
        }
        if($status){
            echo json_encode(array('status' => 1,'msg' => ''));
        }else{
            echo '{"status":0,"msg":"数据保存失败！"}';
        }
    }	
?>

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
	
	$sql = "select a.F_AppCode,a.F_AppID,a.F_AppName,a.F_SecretKey,b.F_RouterCode,b.F_RouterName,COUNT(d.F_NodeCode) as F_NodeNum from dbo.tb_A_IoTRouter b,tb_A_IoTApp a left outer join dbo.tb_A_Template c on a.F_AppCode = c.F_AppCode left outer join dbo.tb_A_IoTNode d on c.F_TemplateCode = d.F_TemplateCode  where a.F_RouterCode = b.F_RouterCode group by a.F_AppCode,a.F_AppID,a.F_AppName,a.F_SecretKey,b.F_RouterCode,b.F_RouterName";
	$result = $db -> query($sql);
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    $app_node_cache = json_decode($redis -> get("app_node"));
    foreach ($result as $app){
        $total_node = 0;
        $online_node = 0;
        $app_code = $app -> F_AppCode;
        if($app_node_cache && property_exists($app_node_cache,$app_code)){
            $app_node_array = $app_node_cache -> $app_code;
            foreach ($app_node_array as $node){
                $node_code = $node -> code;
                if($node_cache -> $node_code -> online == true){
                    $online_node++;
                }
                $total_node++;
            }
        }
        $app -> F_TotalNode = $total_node;
        $app -> F_OnlineNode = $online_node;
    }
	echo json_encode($result);
?>

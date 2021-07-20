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
	
	$sql = "select a.F_RouterCode,a.F_RouterName,a.F_RouterIP,a.F_RouterPort,a.F_Interval,COUNT(DISTINCT b.F_AppCode) as F_AppNum,COUNT(d.F_NodeCode) as F_NodeNum from tb_A_IoTRouter a left outer join dbo.tb_A_IoTApp b on a.F_RouterCode = b.F_RouterCode left outer join dbo.tb_A_Template c on b.F_AppCode = c.F_AppCode left outer join dbo.tb_A_IoTNode d on c.F_TemplateCode = d.F_TemplateCode group by a.F_RouterCode,a.F_RouterName,a.F_RouterIP,a.F_RouterPort,a.F_Interval";
	$resRouter = $db -> query($sql);
    
	$redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $node_cache = json_decode($redis -> get("node"));
    foreach ($resRouter as $router){
        $total_node = 0;
        $online_node = 0;
        $sql = "select F_NodeCode from dbo.tb_A_IoTNode a,dbo.tb_A_IoTApp b,dbo.tb_A_IoTRouter c where c.F_RouterCode = " . $router -> F_RouterCode . " and c.F_RouterCode = b.F_RouterCode and b.F_AppCode = a.F_AppCode";
	    $resNode = $db -> query($sql);
        foreach ($resNode as $node){
            $node_code = $node -> F_NodeCode;
            if($node_cache && property_exists($node_cache,$node_code) && $node_cache -> $node_code -> online == true){
                $online_node++;
            }
            $total_node++;
        }
        $router -> F_TotalNode = $total_node;
        $router -> F_OnlineNode = $online_node;
    }
	echo json_encode($resRouter);
?>

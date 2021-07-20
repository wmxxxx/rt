<?php
	header("Content:text/html;charset=utf-8");
	include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/base.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/lib/x/x.php");

	date_default_timezone_set("PRC");
	$str = $db -> multi_query("select StrID,StrName,isForce from [Things+].[dbo].[tb_AC_Strategy] where StrType = 'all'");
	$result = array();
	foreach($str as $s){
		$now = date('Y-m-d H:i:s',time());
		$db -> execute("insert into [Things+].[dbo].[tb_AC_StrategyRecord] values(".$s -> StrID.",'".$s -> StrName."','".$now."','后台任务','0')");
		$nodes = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_StrategyToNode] where StrID =".$s -> StrID);
		$all = count($nodes);
		$offline = 0;$close = 0;$open = 0;$illegal = 0;$key = 0;
		foreach($nodes as $n){
			$node = checkNode($n -> NodeID);
			switch($node["state"]){
				case 1:
					$offline++;
					break;
				case 2:
					$close++;
					break;
				case 3:
					$open++;
					break;
			}
			if($node["flag"]){
				$illegal++;
				$db -> execute("insert into [Things+].[dbo].[tb_AC_AlarmEvent] values(".$node["id"].",'".$now."',".$node["key"].",'".$node["title"]."','".$node["msg"]."')");
				if($node["key"] >= 0){
					$key++;
					$db -> execute("insert into [Things+].[dbo].[tb_AC_StrategyDetail](StrID,NodeID,SendDate) values(".$n -> StrID.",".$node["id"].",'".$now."')");
					array_push($result,array(
						"id" => $node["id"],
						"data" => array(
							$node["key"] > 1 ? "temp" : "open" => $node["key"]
						)
					));
				}
			}
		}
		if(!$key){
			$db -> execute("delete [Things+].[dbo].[tb_AC_StrategyRecord] where StrID = ".$s -> StrID." and MakeDate = '".$now."'");
		}
		$info = ($s -> isForce ? "" : "非")."强控策略（".$s -> StrName."）运行检查完成。总计检查：".$all."台，离线：".$offline."台，关机：".$close."台，开机：".$open."台，违规：".$illegal."台。";
		$db -> execute("insert into [Things+].[dbo].[tb_AC_SystemLog] values('策略巡检','".$now."','".$info."')");
	}
	if(!count($result)){
		batchTask($result);
	}
?>
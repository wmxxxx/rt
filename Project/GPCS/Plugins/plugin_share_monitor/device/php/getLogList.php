<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	
	$id = $_POST["id"];
	$time = $_POST["time"];
	$app = $_POST["app"];
	$result = array();
	$log = $db -> query("select a.*,b.*,c.F_name from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid left join [Things+].[dbo].[TB_Share_Task] c on a.F_task_id = c.F_id where a.F_node_id = ".$id." and a.F_send like '%".$time."%' and a.F_app = ".$app);
	foreach($log as $l){
		if($l -> F_code != "100" && $l -> F_code != "103"){
			$color = "#FAAF3A";
			if(!in_array($l -> F_code,array("","101","102"))){
				$color = $l -> F_code == "0" ? '#159316' : '#FA5D3A';
			}
			$type = "手动控制";
			if($l -> F_type == 1){
				$type = '批量任务';
			}else if($l -> F_type == 2){
				$type = '策略任务('.$l -> F_name.')';
			}else if($l -> F_type == 3){
				$type = '重发任务';
			}
			array_push($result,array(
				"color" => $color,
				"title" => $type,
				"msg" => $l -> F_command_name,
				"time" => $l -> F_time ? $l -> F_time : $l -> F_send
			));
		}
	}
	$warning = $db -> query("select * from [Things+].[dbo].[TB_Share_Illegal] where F_node_id = ".$id." and F_time like '".$time."%'");
	foreach($warning as $w){
		$type = "开机时间违规";
		if($w -> F_type == 2){
			$type = '制冷下限违规';
		}else if($w -> F_type == 3){
			$type = '制热上限违规';
		}
		array_push($result,array(
			"color" => "#FAAF3A",
			"title" => $type,
			"msg" => $w -> F_msg,
			"time" => $w -> F_time
		));
	}
	usort($result,function($obj1,$obj2){
		return $obj1["time"] < $obj2["time"] ? 1 : -1;
	});
	ob_clean();
	echo json_encode($result);
?>

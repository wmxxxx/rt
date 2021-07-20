<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__)))."/lib/utils.php");
	include_once(dirname(dirname(dirname(__FILE__)))."/lib/plugin/share.php");
	date_default_timezone_set('PRC');
	// v2.3
	$de_log = new stdClass();
	$de_log -> start = date("Y-m-d H:i:s");
	$base = base();
	$today = date("Y-m-d");
	$now = date("H:i");
	$week = date("w");
	if($week == "0")$week = "7";
	// 数据准备
	$prep = prepare();
	// 判断策略执行的命令和设备
	$result = array();
	$strategy = $db -> query("select * from [Things+].[dbo].[TB_Share_Relation] where F_open = 1");
	foreach($strategy as $str){
		$str -> node = array();
		$str -> command = array();
		// 检查今天是否需要执行策略
		$isToday = 0;
		$cycle = $prep["model"][$str -> F_cycle_id];
		if($today >= $cycle -> F_start && $today <= $cycle -> F_end){
			if($cycle -> F_type_val == "0" || strstr($cycle -> F_type_val,$week))$isToday = 1;// 是否是今天
			// $prep["work"] 0:节假日;1:工作日
			// 工作日
			if($cycle -> F_day == 1){
				// 在节假日表中 不发
				if(isset($prep["work"][0]) && in_array($today,$prep["work"][0]))$isToday = 0;
				// 是周6,7 & 不在工作日表中 不发 
				if(strstr("6,7",$week) && isset($prep["work"][1]) && !in_array($today,$prep["work"][1]))$isToday = 0;
			}
			// 节假日
			if($cycle -> F_day == 2){
				// 在工作日表中 不发
				if(isset($prep["work"][1]) && in_array($today,$prep["work"][1]))$isToday = 0;
				// 是周1,2,3,4,5 & 不在节假日表中 不发
				if(strstr("1,2,3,4,5",$week) && isset($prep["work"][0]) && !in_array($today,$prep["work"][0]))$isToday = 0;
			}
		}
		// 检查现在是否有需要执行的命令
		foreach($prep["action"][$str -> F_action_id] as $a){
			if($a -> F_time == $now)$str -> command[$a -> F_command_id] = array();
		}
		if($isToday && count($str -> command)){
			// 获取需要控制的设备
			foreach(explode(",",$str -> F_group_id) as $g){
				foreach($prep["group"][$g] as $n){
					// 强制控制策略
					if(strstr($str -> F_id,'force'))array_push($str -> node,$n -> F_node_id);
					// 不是强制控制策略，强控分组没有该设备
					if(!strstr($str -> F_id,'force') && !in_array($n -> F_node_id,$prep["force"]))array_push($str -> node,$n -> F_node_id);
				}
			}
		}
		// 去除重复设备
		$str -> node = array_unique($str -> node);
		if(count($str -> node) && count($str -> command))array_push($result,$str);
		// 获取传入参数
		$action = $db -> query("select * from [Things+].[dbo].[TB_Share_Action] where F_value != '' and F_action_id = '".$str -> F_action_id."'");
		foreach($action as $a){
			foreach(json_decode($a -> F_value,true) as $a_key => $a_val){
				if(isset($str -> command[$a -> F_command_id]))$str -> command[$a -> F_command_id][$a_key] = $a_val;
			}
		}
	}
	$task = "";
	if(count($result))$task = sendCommand($result,2);
	$de_log -> end = date("Y-m-d H:i:s");
	$de_log -> task = $task;
	$db -> query("insert into [Things].[dbo].[tb_A_Log] (F_TypeNo,F_DateTime,F_LogDetail) values(13,'".date("Y-m-d H:i:s")."','通用控制任务:".json_encode($de_log)."')");
	echo json_encode($de_log);
?>
<?php
	header("Content:text/html;charset=utf-8");
	include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/base.php");

	date_default_timezone_set("PRC");
	function _curl($url,$type="get",$arr=""){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		// curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-type:application/json;","Accept:application/json"));
		curl_setopt($ch,CURLOPT_USERPWD,'admin:rt@rjyf');
		if($type == "post"){
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		//return $output;
		return json_decode(trim($output,chr(239).chr(187).chr(191)));
	}
	function checkType($id){
		$db = new DB();
		$tag = $db -> multi_query("select a.F_NodeTemplate,b.F_GroupTag from [Things].[dbo].[tb_B_EntityTreeModel] a,[Things].[dbo].[tb_B_DictTreeModel] b where a.F_EntityID = ".$id." and a.F_TemplateID = b.F_GroupID");
		$tag = $tag[0];
		if($tag -> F_GroupTag == "T2_AirConditioner" | $tag -> F_GroupTag == "T2_SAirCon"){
			$type = $db -> multi_query("select F_TemplateLabel from [Things].[dbo].[tb_A_Template] where F_TemplateCode =".$tag -> F_NodeTemplate);
			return $type[0] -> F_TemplateLabel;
		}else{
			return $tag -> F_GroupTag;
		}
	}
	function checkNode($id){
		$db = new DB();
		$result = array(
			"id" => $id,
			"state" => 1,//1离线,2关机,3开机
			"flag" => 0,//0未违规,1违规
			"key" => null,//0关机,1开机,大于1设定温度,-1不控制
			"title" => "",
			"msg" => ""
		);
		$node = _curl("http://".$_SERVER['HTTP_HOST']."/API/RData/GetDeviceVariantData/?device_code=".$id);
		if(count($node -> variantDatas)){
			foreach($node -> variantDatas as $key => $val){
				$node -> map[$val -> label] = $val -> value;
			}
		}else{
			return $result;
		}
		$node -> type = checkType($id);
		if($node -> type == "SAirCon"){
			$open = $node -> map["StartStopStatus"] == 1 ? 1 : 0;
			$mode = substr($node -> map["SettingMode"],0,-2) - 1;
			$temp = substr($node -> map["SettingMode"],1);
		}else{
			$open = $node -> map["StartStopStatus"];//0关1开
			$mode = $node -> map["ModeStatus"];//1制冷2制热
			$temp = $node -> map["TempSet"];
		}
		if($node -> online){//是否在线
			$result["state"] = $open ? 3 : 2;//开关机状态
			$str = $db -> multi_query("select b.* from [Things+].[dbo].[tb_AC_StrategyToNode] a,[Things+].[dbo].[tb_AC_Strategy] b where a.NodeID = ".$id." and a.StrID = b.StrID");
			$checkNode = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_BatchConDetail] a,[Things+].[dbo].[tb_AC_BatchConRecord] b where a.NodeID = ".$id." and a.BConID = b.BConID and b.ExpiryDate > '".date("Y-m-d H:i:s")."'");
			if(count($str) & !count($checkNode)){//是否关联策略 & 是否有主观控制任务
				$str = $str[0];
				$now = date("H",time())*60 + date("i",time());
				$month = date('m',time());
				if($str -> isTime){//启用时控
					$timeCon = $db -> multi_query("select top 1 * from [Things+].[dbo].[tb_AC_StrategyTimeConA] where StrID = ".$str -> StrID." and timeValue <= '".$now."' order by timeValue desc");
					if(count($timeCon)){
						$timeCon = $timeCon[0];
						$time = date("H",strtotime($timeCon -> timeValue))*60 + date("i",strtotime($timeCon -> timeValue));
						if($timeCon -> timeCmd == 0 & $result["state"] == 3){//时控为关机 & 状态为开机
							$result["flag"] = 1;
							$result["title"] = "开机时间违规";
							$result["msg"] = "当前时间为：".date("H:i",time()).",超过允许开机时间(".$timeCon -> timeValue.")";
							if($str -> isForce){
								if($timeCon -> isPoll){
									$result["key"] = ($now - $time) % $timeCon -> pollTime < 5 ? 0 : -1;
								}else{
									$result["key"] = $now - $time < 5 ? 0 : -1;
								}
							}else{
								$result["key"] = -1;
							}
						}
						if($timeCon -> timeCmd == 1 & $result["state"] == 2){//时控为开机 & 状态为关机
							if($str -> isForce){
								if($timeCon -> isPoll){
									if(($now - $time) % $timeCon -> pollTime < 5){
										$result["key"] = 1;
									}
								}else{
									if($now - $time < 5){
										$result["key"] = 1;
									}
								}
							}
						}
					}
				}
				if($str -> isTemp & $result["flag"] == 0 & $result["state"] == 3){//启用温控 & 时控未违规 & 状态为开机
					if($mode == 1){
						$mon = ($str -> coldStart < $str -> coldEnd ? ($month >= $str -> coldStart & $month <= $str -> coldEnd) : ($month >= $str -> coldStart | $month <= $str -> coldEnd));//当前月份是否在制冷其实月份内
						if($mon){
							if($str -> isCold & $temp < $str -> coldLower){
								$result["flag"] = 1;
								$result["title"] = "设定温度违规";
								$result["msg"] = "制冷温度下限为：".$str -> coldLower."℃,当前设定温度为：".$temp."℃。";
								if($str -> isForce){
									$result["key"] = $now % $str -> tempCycle < 5 ? $str -> coldLower : -99;
								}else{
									$result["key"] = -99;
								}
							}
						}else{
							$result["flag"] = 1;
							$result["title"] = "开机时间违规";
							$result["msg"] = "制冷起止月份".$str -> coldStart."~".$str -> coldEnd."月,当前时间为：".$month."月";
							if($str -> isForce){
								$result["key"] = $now % $str -> tempCycle < 5 ? 0 : -1;
							}else{
								$result["key"] = -1;
							}
						}
					}else if($mode == 2){
						$mon = ($str -> warmStart < $str -> warmEnd ? ($month >= $str -> warmStart & $month <= $str -> warmEnd) : ($month >= $str -> warmStart | $month <= $str -> warmEnd));//当前月份是否在制热其实月份内
						if($mon){
							if($str -> isWarm & $temp < $str -> warmUpper){
								$result["flag"] = 1;
								$result["title"] = "设定温度违规";
								$result["msg"] = "制热温度上限为：".$str -> warmUpper."℃,当前设定温度为：".$temp."℃。";
								if($str -> isForce){
									$result["key"] = $now % $str -> tempCycle < 5 ? $str -> warmUpper : -99;
								}else{
									$result["key"] = -99;
								}
							}
						}else{
							$result["flag"] = 1;
							$result["title"] = "开机时间违规";
							$result["msg"] = "制热起止月份".$str -> warmStart."~".$str -> warnEnd."月,当前时间为：".$month."月";
							if($str -> isForce){
								$result["key"] = $now % $str -> tempCycle < 5 ? 0 : -1;
							}else{
								$result["key"] = -1;
							}
						}
					}
				}
			}
		}
		return $result;
	}
	function batchTask($node,$token=""){
		/*	open:0关,1开 
			mode:1制冷,2制热  
			$node=[{
				id:id,
				data:{
					cmd:value
					...
				}
			}]					*/
		$db = new DB();
		$subTask = array();
		foreach($node as $n){
			$type = checkType($n["id"]);
			$n["type"] = $type[0] -> F_TemplateLabel;
			if($n["type"] == "SAirCon"){
				$res = array(
					"taskName" => $n["id"],
					"type" => "sendDeviceCustomCmd",
					"args" => array(
						"deviceCode" => $n["id"],
						"cmdName" => "SetCurCmd",
						"cmdData" => array(
							"ModeCmd" => 0,
							"StartStopCmd" => 4,
							"Temp" => 20,
							"CurCmdTime" => 255
						)
					)
				);
				if($n["data"]["open"]){
					$res["args"]["cmdData"]["StartStopCmd"] = ($n["data"]["open"] ? 3 : 1);
				}else{
					$res["args"]["cmdData"]["TempSet"] = $n["data"]["temp"];
					$res["args"]["cmdData"]["ModeCmd"] = ($n["data"]["mode"] == 1 ? 2 : 3);
				}
				array_push($subTask,$res);
			}else{
				$res = array(
					"taskName" => $n["id"],
					"type" => "writeDeviceVariantData",
					"args" => array(
						"deviceCode" => $n["id"],
						"writeData" => array()
					)
				);
				foreach($n["data"] as $key => $val){
					if($key == "open"){
						$res["args"]["writeData"]["StartStopCmd"] = $val;
					}
					if($key == "temp"){
						$res["args"]["writeData"]["TempSet"] = $val;
					}
					if($key == "mode"){
						$res["args"]["writeData"]["ModeCmd"] = $val;
					}
				}
				array_push($subTask,$res);
			}
		}
		if(count($subTask)){
			$param = array(
				"taskName" => 0,
				"callbackUrl" => $token,
				"subTasks" => $subTask
			);
			$result = _curl("http://".$_SERVER['HTTP_HOST']."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($param)));
			return $result;
		}
	}
	function sendSAirCon($flag,$strid,$nodes=null){
		$db = new DB();
		$str = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_Strategy] where StrID =".$strid);
		$str = $str[0];
		if($str -> StrType == "SAirCon"){
			if($nodes == null){
				$nodes = $db -> multi_query("select NodeID from [Things+].[dbo].[tb_AC_StrategyToNode] where StrID =".$strid);
			}
			$param = array(
				"taskName" => "SAirCon".$flag,
				"callbackUrl" => "",
				"subTasks" => array()
			);
			foreach($nodes as $n){
				if($str -> isTime){
					$SetTimeStrategy = array(
						"TimeNum" => 0,
						"TimePlan" => array(),
						"DisableTimeRTC" => "2017-01-01 01:01:01"
					);
					if($flag){
						$timeCon = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_StrategyTimeConA] where StrID =".$strid);
						$SetTimeStrategy["TimeNum"] = count($timeCon);
						$SetTimeStrategy["DisableTimeRTC"] = date("Y-m-d H:i:s",strtotime($str -> sDueTime));
						foreach($timeCon as $t){
							$times = date("H-i",strtotime($t -> timeValue))."-".($t -> isPoll ? $t -> pollTime : "00")."-0".$t -> timeCmd;
							array_push($SetTimeStrategy["TimePlan"],$times);
						}
					}
					array_push($param["subTasks"],array(
						"taskName" => $n,
						"type" => "sendDeviceCustomCmd",
						"args" => array(
							"deviceCode" => $n,
							"cmdName" => "SetTimeStrategy",
							"cmdData" => $SetTimeStrategy
						)
					));
				}
				if($str -> isTemp){
					array_push($param["subTasks"],array(
						"taskName" => $n,
						"type" => "sendDeviceCustomCmd",
						"args" => array(
							"deviceCode" => $n,
							"cmdName" => "SetTempPeriod",
							"cmdData" => array(
								"ColdMonthStart" => $flag ? $str -> coldStart : 7, 
								"ColdMonthEnd" => $flag ? $str -> coldEnd : 9,
								"WarmMonthStart" => $flag ? $str -> warmStart : 11,
								"WarmMonthEnd" => $flag ? $str -> warmEnd : 2
							)
						)
					));
					array_push($param["subTasks"],array(
						"taskName" => $n,
						"type" => "writeDeviceVariantData",
						"args" => array(
							"deviceCode" => $n,
							"writeData" => array(
								"PollTime" => $flag ? $str -> tempCycle : 30
							)
						)
					));
					if($str -> isCold | $str -> isWarm){
						array_push($param["subTasks"],array(
							"taskName" => $n,
							"type" => "sendDeviceCustomCmd",
							"args" => array(
								"deviceCode" => $n,
								"cmdName" => "SetTempStrategy",
								"cmdData" => array(
									"ColdLowerLimit" => $flag ? $str -> coldLower : 99,
									"ColdLimitValue" => $flag ? $str -> coldValue : 99,
									"WarmUpperLimit" => $flag ? $str -> warmUpper : 99,
									"WarmLimitValue" => $flag ? $str -> warmValue : 99
								)
							)
						));
					}
				}
			}
			if(!count($param["subTasks"])){
				$result = _curl("http://".$_SERVER['HTTP_HOST']."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($param)));
				return $result; 
			}
		}
	}
?>
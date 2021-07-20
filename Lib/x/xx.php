<?php
	header("Content:text/html;charset=utf-8");
	include_once($_SERVER['DOCUMENT_ROOT']."/Php/lib/base.php");

	date_default_timezone_set("PRC");
	function _curl($url,$type='get',$arr=''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-type:application/json;","Accept:application/json"));
		curl_setopt($ch, CURLOPT_USERPWD, 'zhanghui:zhanghui');
		if($type == 'post'){
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		if(preg_match('/^\xEF\xBB\xBF/',$output)){
			$output = substr($output,3);
		}
		return json_decode($output);
	}
	function checkType($id){
		$db = new DB();
		$tag = $db -> multi_query("select a.F_NodeTemplate,b.F_GroupTag from [Things].[dbo].[tb_B_EntityTreeModel] a,[Things].[dbo].[tb_B_DictTreeModel] b where a.F_EntityID = ".$id." and a.F_TemplateID = b.F_GroupID");
		$tag = $tag[0];
		if($tag -> F_GroupTag == "AirCon"){
			$type = $db -> multi_query("select F_TemplateLabel from [Things].[dbo].[tb_A_Template] where F_TemplateCode =".$tag -> F_NodeTemplate);
			return $type[0] -> F_TemplateLabel;
		}else{
			return $tag -> F_GroupTag;
		}
	}
	function checkNode($id){
		/*	state:1离线,2关机,3开机
			flag:0未违规,1违规->key
			key:0关机,1开机,大于1设定温度,-1不控制  */
		$db = new DB();
		$result = array(
			"id" => $id,
			"state" => 1,
			"flag" => 0,
			"key" => null,
			"title" => "",
			"msg" => ""
		);
		$node = _curl("http://".$_SERVER['HTTP_HOST']."/API/RData/GetDeviceVariantData/?device_code=".$id);
		$node -> type = checkType($id);
		foreach($node -> variantDatas as $key => $val){
			$node -> map[$val -> label] = $val -> value;
		}
		if($node -> type == "SAirCon"){
			$open = $node -> map["StartStopStatus"] == 1 ? 1 : 0;
			$mode = $node -> map["ModeStatus"];
			$temp = $node -> map["RoomTemp"];
		}else{
			$open = $node -> map["StartStopStatus"];
			$mode = $node -> map["ModeStatus"];
			$temp = $node -> map["RoomTemp"];
		}
		if(!$node -> online){
			$result["state"] = $open ? 3 : 2;
			$checkNode = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_BatchConDetail] a,[Things+].[dbo].[tb_AC_BatchConRecord] b where a.NodeID = ".$id." and a.BConID = b.BConID and b.ExpiryDate > '".date("Y-m-d H:i:s")."'");
			if(!count($checkNode)){
				$str = $db -> multi_query("select b.* from [Things+].[dbo].[tb_AC_StrategyToNode] a,[Things+].[dbo].[tb_AC_Strategy] b where a.NodeID = ".$id." and a.StrID = b.StrID");
				if(count($str)){
					$str = $str[0];
					$now = date("H",time())*60 + date("i",time());
					$month = date('m',time());
					if($str -> isTime){
						$time = $db -> multi_query("select * from [Things+].[dbo].[tb_AC_StrategyTimeConB] where StrID = ".$str -> StrID." and timeStart <= '".date("H:i",time())."' and timeEnd >= '".date("H:i",time())."'");
						if(!count($time)){
							$timeCon = $db -> multi_query("select top 1 * from [Things+].[dbo].[tb_AC_StrategyTimeConB] where StrID = ".$str -> StrID." and timeEnd < '".date("H:i",time())."' order by timeEnd desc");
							$result["flag"] = 1;
							$result["title"] = "开机时间违规";
							$result["msg"] = "当前时间为：".date("H:i",time()).",超过允许开机时间(".$timeCon[0] -> timeStart."~".$timeCon[0] -> timeEnd.")";
							$end = date("H",strtotime($timeCon[0] -> timeEnd))*60 + date("i",strtotime($timeCon[0] -> timeEnd));
							if($str -> isForce){
								$timeCon[0] -> isPoll ? $timeCon[0] -> pollTime : $timeCon[0] -> pollTime = 30;
								if(($now - $end) % $timeCon[0] -> pollTime < 5){
									$result["key"] = 0;
								}else{
									$result["key"] = -1;
								}
							}else{
								$result["key"] = -1;
							}
						}else{
							$start = date("H",strtotime($time[0] -> timeStart))*60 + date("i",strtotime($time[0] -> timeStart));
							if($now - $start < 5){
								$result["key"] = 1;
							}
						}
					}
					if($str -> isTemp & $result["flag"] == 0){
						if($mode == 1){
							$mon = ($str -> coldStart < $str -> coldEnd ? ($month >= $str -> coldStart & $month <= $str -> coldEnd) : ($month >= $str -> coldStart | $month <= $str -> coldEnd));
							if($mon){
								if($str -> isCold & $temp < $str -> coldLower){
									$result["flag"] = 1;
									$result["title"] = "设定温度违规";
									$result["msg"] = "制冷温度下限为：".$str -> coldLower."℃,当前温度为：".$temp."℃。";
									if($str -> isForce){
										if($now % $str -> tempCycle < 5){
											$result["key"] = $str -> coldLower;
										}else{
											$result["key"] = -99;
										}
									}else{
										$result["key"] = -99;
									}
								}
							}else{
								$result["flag"] = 1;
								$result["title"] = "开机时间违规";
								$result["msg"] = "制冷起止月份".$str -> coldStart."~".$str -> coldEnd."月,当前时间为：".$month."月";
								if($str -> isForce){
									if($now % $str -> tempCycle < 5){
										$result["key"] = 0;
									}else{
										$result["key"] = -1;
									}
								}else{
									$result["key"] = -1;
								}
							}
						}else if($mode == 2){
							$mon = ($str -> warmStart < $str -> warmEnd ? ($month >= $str -> warmStart & $month <= $str -> warmEnd) : ($month >= $str -> warmStart | $month <= $str -> warmEnd));
							if($mon){
								if($str -> isWarm & $temp < $str -> warmUpper){
									$result["flag"] = 1;
									$result["title"] = "设定温度违规";
									$result["msg"] = "制热温度上限为：".$str -> warmUpper."℃,当前温度为：".$temp."℃。";
									if($str -> isForce){
										if($now % $str -> tempCycle < 5){
											$result["key"] = $str -> warmUpper;
										}else{
											$result["key"] = -99;
										}
									}else{
										$result["key"] = -99;
									}
								}
							}else{
								$result["flag"] = 1;
								$result["title"] = "开机时间违规";
								$result["msg"] = "制热起止月份".$str -> warmStart."~".$str -> warnEnd."月,当前时间为：".$month."月";
								if($str -> isForce){
									if($now % $str -> tempCycle < 5){
										$result["key"] = 0;
									}else{
										$result["key"] = -1;
									}
								}else{
									$result["key"] = -1;
								}
							}
						}
					}
				}
			}
		}
		return json_decode(json_encode($result));
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
?>
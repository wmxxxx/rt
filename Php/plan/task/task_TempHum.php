<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__)))."/lib/utils.php");
	include_once(dirname(dirname(dirname(__FILE__)))."/lib/plugin/share.php");
	// v2.0
	function task_TempHum($app,$fun){
		$db = new DB();
		$base = base();
		session_start();
		$user = $_SESSION['user']["code"];
		// 获取功能关联设备字典
		$template = curl($base -> ip."/API/Context/FunToDevice/?fun_code=".$fun);
		$tpl = array();
		foreach($template as $t){
			array_push($tpl,$t -> F_DeviceTypeID);
		}
		// 获取树根节点
		$tree = curl($base -> ip."/API/Context/FunToTree/?fun_code=".$fun."&sys_code=".$app."&user_code=".$user);
		foreach($tree as $t){
			if($t -> F_ParentID == "0")$tree = $t -> F_EntityID;
		}
		// 当前城市天气
		$weather = curl($base -> ip."/API/Extended/GetCityCurrWeather/");
		$weather -> tpl_tag = "city";
		// 获取空调数据
		$data = curl($base -> ip."/API/RData/GetMultiDeviceVariantData/?type_id=".implode(",",$tpl)."&entity_str=".$tree."&haschild=1");
		$data = intactAttr($data,$app);
		$sql = "";
		foreach($data as $d){
			$pid = $db -> query("select F_EntityID from [Things].[dbo].[tb_B_EntityTreeToDevice] where F_DeviceID = ".$d -> code);
			$pid = $pid[0];
			$d -> access = checkAccess($app,$pid -> F_EntityID,1);
			if(!count($d -> access))$d -> access = array($weather);
			$outTemp=0;$outHum=0;$temp=0;$hum=0;$energy=0;$open=0;
			if($d -> access[0] -> tpl_tag == "city"){
				$outTemp = $d -> access[0] -> temperature_curr;
				$outHum = str_replace("%","",$d -> access[0] -> humidity);
			}else if($d -> access[0] -> tpl_tag == "humitemp"){
				if(isset($d -> access[0] -> variantDatas[0]))$outTemp = $d -> access[0] -> variantDatas[0] -> value;
				if(isset($d -> access[0] -> variantDatas[1]))$outHum = $d -> access[0] -> variantDatas[1] -> value;
			}
			if($d -> tpl_tag == "fjpg2"){
				if(isset($d -> data["RoomTemp"]))$temp = $d -> data["RoomTemp"] -> value;
				if(isset($d -> data["RoomHumi"]))$hum = $d -> data["RoomHumi"] -> value;
				if(isset($d -> data["RelayStatus"]))$open = $d -> data["RelayStatus"] -> value;
			}else if($d -> tpl_tag == "hvac2"){
				if(isset($d -> data["RoomTemp"]))$temp = $d -> data["RoomTemp"] -> value;
				if(isset($d -> data["StartStopStatus"]))$open = $d -> data["StartStopStatus"] -> value;
			}else if($d -> tpl_tag == "ftkt2"){
				if(isset($d -> data["RoomTemp"]))$temp = $d -> data["RoomTemp"] -> value;
				if(isset($d -> data["RoomHum"]))$hum = $d -> data["RoomHum"] -> value;
				if(isset($d -> data["StartStopStatus"]))$open = $d -> data["StartStopStatus"] -> value;
			}
			if(isset($d -> data["Ep"])){
				$energy = $d -> data["Ep"] -> value;
				$is = $db -> query("select top 1 * from [Things+].[dbo].[TB_share_FlexEnergy] where F_nodeid = '".$d -> code."' and F_tpl = '".$d -> tpl_tag."' order by F_date desc");
				if(count($is)){
					$is = $is[0];
					$stime = strtotime(date($is -> F_date));
					$sql .= "update [Things+].[dbo].[TB_share_FlexEnergy] set F_time = ".round((time() - $stime)/60).",F_diff = '".($energy - $is -> F_energy)."' where F_id = '".$is -> F_id."'";
					if($is -> F_outTemp != round($outTemp) || $is -> F_outHum != round($outHum) || $is -> F_temp != round($temp) || $is -> F_hum != round($hum) || $is -> F_open != $open){
						$sql .= "insert into [Things+].[dbo].[TB_share_FlexEnergy] values ('".uniqid("fx")."','".$d -> code."','".$d -> tpl_tag."',".round($outTemp).",".round($outHum).",".round($temp).",".round($hum).",0,".$energy.",0,".$open.",'".date("Y-m-d H:i:s")."');";
					}
				}else{
					$sql .= "insert into [Things+].[dbo].[TB_share_FlexEnergy] values ('".uniqid("fx")."','".$d -> code."','".$d -> tpl_tag."',".round($outTemp).",".round($outHum).",".round($temp).",".round($hum).",0,".$energy.",0,".$open.",'".date("Y-m-d H:i:s")."');";
				}
			}
			if($d -> tpl_tag == "ftkt2"){
				$out = ICHB($outTemp,$outHum);
				$room = ICHB($temp,$hum);
				if($out >= 55 && $out <= 70){
					// 关机
				}else{
					if($open == 1){
						// 计算适度区间温度
						$it = ICHBt($hum);
						$en = array();
						// 匹配每小时能耗
						foreach($it as $i){
							$e = $db -> query("select SUM(F_time) as t,SUM(F_diff) as e from [Things+].[dbo].[TB_share_FlexEnergy] where F_nodeid = ".$d -> code." and F_temp = ".$i." and F_hum = ".$hum." and F_open = 1");
							$e = $e[0];
							if($e -> t != null){
								$hour = $e -> t / 60;
								$energy = $e -> e / $hour;
								$en[$i] = $energy;
							}
						}
						// 有能耗选择能耗低的设定温度 没有选择舒适度为65的设定温度
						if(count($en)){
							usort($en,function($obj1,$obj2){
								return $obj1 < $obj2 ? -1 : 1;
							});
						}else{
							$tp = $it["65"];
						}
					}
				}
			}
		}
		$db -> multi_query($sql);
		return json_encode($data);
	}
	function ICHB($t,$hu,$v=1){
		return (1.8 * $t + 32) - 0.55 * (1 - $hu / 100) * (1.8 * $t - 26) - 3.2 * sqrt($v);
	}
	function ICHBt($hu,$v=1){
		$res = array();
		$hu = 1 - $hu / 100;
		for($i = 60;$i <= 70;$i++){
			$t = ($i - 32 - 14.3 * $hu + 3.2 * sqrt($v)) / (1.8 - 0.99 * $hu);
			$res[$i] = round($t);
		}
		return $res;
	}
?>
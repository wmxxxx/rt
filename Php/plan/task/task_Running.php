<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__)))."/lib/plugin/share.php");
	// v2.3
	function task_Running($app,$sys){
		$res = "任务开始".date("Y-m-d H:i:s")."<br>";
		$db = new DB();
		$base = base();
		$fun = $db -> query("select c.F_ProjectNo,b.F_FunctionCode from [Things].[dbo].[tb_A_Plugins] a,[Things].[dbo].[tb_A_Function] b,[Things].[dbo].[tb_A_ProjectToMenu] c where a.F_PluginTag = 'plugin_share_monitor' and b.F_PluginCode = a.F_PluginCode and c.F_FunctionCode = b.F_FunctionCode");
		foreach($fun as $f){
			$res .= mainTask($f -> F_ProjectNo,$f -> F_FunctionCode);
		}
		$res .= "任务结束".date("Y-m-d H:i:s");
		return $res;
	}
	function mainTask($app,$sys){
		date_default_timezone_set("PRC");
		// 获取状态配置
		$db = new DB();
		$base = base();
		$config = $db -> query("select * from [Things+].[dbo].[TB_Share_Config] where F_Type = 'monitor_".$base -> version."' and F_App =".$app);
		$config = json_decode($config[0] -> F_Config) -> node;
		// 获取设备类型
		$device = curl($base -> ip."/API/Context/FunToDevice/?fun_code=".$sys);
		$typeid = array();
		foreach($device as $d){
			array_push($typeid,$d -> F_DeviceTypeID);
		}
		// 获取设备并处理
		$result = curl($base -> ip."/API/RData/GetMultiDeviceVariantData/?type_id=".implode(",",$typeid));
		if($result){
			$result = intactAttr($result,$app);
			// 获取运行最新状态
			$today = date("Y-m-d");
			$time = strtotime(date('2011-11-11 H:i:s',time()));
			$node = $db -> query("select * from (select *,ROW_NUMBER() over(partition by F_NodeID,F_Tab order by F_endtime desc) rowid from [Things+].[dbo].[TB_Share_RunningState] where F_App = '".$app."'  and F_Date = '".$today."') t where rowid = 1");
			$nodes = array();
			foreach($node as $n){
				$nodes[$n -> F_NodeID.$n -> F_Tab] = $n;
			}
			// 获取异常状态
			$error = $db -> query("select * from [Things+].[dbo].[TB_Share_ErrorState]");
			$errors = array();
			foreach($error as $e){
				$errors[$e -> F_ID] = $e;
			}
			// 获取控制失败个数
			$fail = $db -> query("select a.F_node_id,count(a.F_node_id) as total,sum(case when b.F_code not in ('','0','100','101','102','103') then 1 else 0 end) as num from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid where a.F_send > '".date("Y-m-d", strtotime("-3 day"))."' and a.F_app = ".$app." group by a.F_node_id");
			$fails = array();
			foreach($fail as $f){
				$fails[$f -> F_node_id] = $f;
			}
			$fsql = "";
			foreach($result as $r){
				// 更新运行状态
				$state = -1;$info = "未通讯";$color = "#ddd";
				$tpl = $r -> tpl_tag; 
				foreach($config as $tab => $con){
					foreach($con -> tips as $c){
						if(isset($c -> tpl -> $tpl)){
							$temp = $c -> tpl -> $tpl;
							$str = '';
							foreach($temp as $t){
								if(isset($r -> data[$t -> attr])){
									$str .= $r -> data[$t -> attr] -> value;
									$str .= $t -> check;
									$str .= $t -> judge;
									if(isset($t -> pos))$str .= $t -> pos;
								}else{
									$str = '';
									break;
								}
							}
							if($str != '' && eval("return ".$str.";")){
								$state = $t -> attr.$t -> judge;
								$info = $c -> text;
								$color = $c -> color;
								break;
							}
						}
					}
					$tnode = $nodes[$r -> code.$tab];
					if($tnode){
						if($tnode -> F_State == $state){
							$fsql .= "delete [Things+].[dbo].[TB_Share_RunningState] where F_ID = '".$tnode -> F_ID."' and F_Tab = ".$tab.";insert into [Things+].[dbo].[TB_Share_RunningState] values('".$tnode -> F_ID."','".$tnode -> F_App."','".$tnode -> F_NodeID."','".$tnode -> F_Date."','".$tnode -> F_StartTime."','".$time."','".$tnode -> F_State."','".$tnode -> F_Info."','".$tnode -> F_Color."',".$tab.");";
						}else{
							$fsql .= "insert into [Things+].[dbo].[TB_Share_RunningState] values('".uniqid()."','".$app."','".$r -> code."','".$today."','".$tnode -> F_EndTime."','".$time."','".$state."','".$info."','".$color."',".$tab.");";
						}
					}else{
						$fsql .= "insert into [Things+].[dbo].[TB_Share_RunningState] values('".uniqid()."','".$app."','".$r -> code."','".$today."','".$time."','".$time."','".$state."','".$info."','".$color."',".$tab.");";
					}
				}
				// 更新异常状态
				if($r -> online){
					$fsql .= "delete [Things+].[dbo].[TB_Share_ErrorState] where F_ID = '".$r -> code."';";
					if(isset($fails[$r -> code]) ){
						$no = $fails[$r -> code];
						$per = round($no -> num / $no -> total,2);
						if($no -> num > 2 && $per > 0.5)$fsql .= "insert into [Things+].[dbo].[TB_Share_ErrorState] values('".$r -> code."',2,'控制失败".$no -> num."次，失败率".($per * 100)."%','','');";
					}
				}else{
					if(isset($errors[$r -> code])){
						$err = $errors[$r -> code];
						$start = $err -> F_StartDate == '' ? date("Y-m-d H:i:s") : $err -> F_StartDate;
						$differ = timeDiffer($start,date("Y-m-d H:i:s"));
						if($differ != "")$fsql .= "update [Things+].[dbo].[TB_Share_ErrorState] set F_Type = 1,F_Info = '离线".$differ."',F_StartDate = '".$start."',F_CurDate = '".date("Y-m-d H:i:s")."' where F_ID = '".$r -> code."';";
					}else{
						$fsql .= "insert into [Things+].[dbo].[TB_Share_ErrorState] values('".$r -> code."',99,'离线','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."');";
					}
				}
				// 添加违规状态
				if($r -> illegal["code"] != 0)$fsql .= "insert into [Things+].[dbo].[TB_Share_Illegal] values('".uniqid("i")."','".$r -> code."',".$r -> illegal["code"].",'".$r -> illegal["msg"]."','".date("Y-m-d H:i:s")."',".$app.");";
			}
			// 清除日志
			if($base -> clear != "0"){
				// 删除命令记录
				$fsql .= "delete [Things+].[dbo].[TB_Share_Log] where F_send < '".date("Y-m-d",strtotime("-".$base -> clear." month"))."'";
				$fsql .= "delete [Things+].[dbo].[TB_Share_Back] where F_time < '".date("Y-m-d",strtotime("-".$base -> clear." month"))."';";
				$fsql .= "delete [Things+].[dbo].[TB_Share_Task] where F_time < '".date("Y-m-d",strtotime("-".$base -> clear." month"))."';";// 删除任务记录
				$fsql .= "delete [Things+].[dbo].[TB_Share_Illegal] where F_time < '".date("Y-m-d",strtotime("-".$base -> clear." month"))."'";// 删除违规记录
				$fsql .= "delete [Things+].[dbo].[TB_Share_RunningState] where F_Date < '".date("Y-m-d",strtotime("-".$base -> clear." month"))."'";// 删除运行状态记录
			}
			$db -> multi_query($fsql);
			$res = $app."执行完成<br>";
		}else{
			$res = $app."获取设备失败<br>";
		}
		return $res;
	}
?>
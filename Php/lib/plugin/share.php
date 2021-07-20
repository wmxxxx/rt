<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(__FILE__))."/base.php");

	date_default_timezone_set("PRC");
	// 通用基础配置
	function base(){
		$db = new DB;
		$version = "v2";
		$base = $db -> query("select F_Config from [Things+].[dbo].[TB_Share_Config] where F_App = 0 and F_Type = 'base_".$version."'");
		$base = json_decode($base[0] -> F_Config);
		$base -> version = $version;
		return $base;
	}
	// 请求
	function curl($url,$type="get",$arr=""){
		$key = '2735ac7d08964fa00519c13bd3e23d2647c0c527';
		$sign = sha1(time().$key);
		$url .= strpos($url,'?') ? "&" : "?";
		$url .= "time=".time()."&sign=".$sign;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($ch,CURLOPT_USERPWD,'curl:jf3907');
		if($type == "post"){
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		$res = json_decode(trim($output,chr(239).chr(187).chr(191)));
		return $res === null ? $output : $res;
	}
	// 处理参数
	function intactAttr($nodes,$app=0){
		$db = new DB;
		// 准备数据
		$prep = array(
			"group" => array(),
			"str" => array(),
			"tpl" => array(),
			"user" => array(),
			"err" => array(),
			"illegal" => $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_app = ".$app." and F_name != '' and F_type = 'time' or F_type = 'temp'")
		);
		// 判断是否违规
		$ids = array();
		foreach($nodes as $n){
			array_push($ids,$n -> code);
		}
		//分组信息
		$group = $db -> query("select a.*,b.F_name,b.F_code from [Things+].[dbo].[TB_Share_GroupToNode] a,[Things+].[dbo].[TB_Share_Group] b where a.F_group_id = b.F_id and b.F_project_code = '".$app."' and a.F_node_id in(".implode(",",$ids).")");
		foreach($group as $g){
			if(!isset($prep["group"][$g -> F_node_id]))$prep["group"][$g -> F_node_id] = array();
			array_push($prep["group"][$g -> F_node_id],$g);
		}
		//关联策略
		$str = $db -> query("select distinct b.F_node_id from [Things+].[dbo].[TB_Share_Relation] a,[Things+].[dbo].[TB_Share_GroupToNode] b where a.F_app = ".$app." and a.F_group_id like '%'+b.F_group_id+'%'");
		foreach($str as $s){
			array_push($prep["str"],$s -> F_node_id);
		}
		// 设备模板信息
		$tpl = $db -> query("select a.F_EntityID,b.F_TemplateLabel,b.F_TemplateCode from [Things].[dbo].[tb_B_EntityTreeModel] a,[Things].[dbo].[tb_A_Template] b where a.F_NodeTemplate != '' and a.F_NodeTemplate = b.F_TemplateCode");
		foreach($tpl as $t){
			$prep["tpl"][$t -> F_EntityID] = $t;
		}
		// 移动端关联
		$user = $db -> query("select * from [Things+].[dbo].[TB_Share_UserToNode]");
		foreach($user as $u){
			$prep["user"][$u -> F_NodeID] = $u;
		}
		// 异常设备
		$err = $db -> query("select F_ID,F_Info from [Things+].[dbo].[TB_Share_ErrorState] where F_Type != 99");
		foreach($err as $e){
			$prep["err"][$e -> F_ID] = $e -> F_Info;
		}
		foreach($nodes as $node){
			//设备模板名称和ID
			$node -> tpl_tag = $prep["tpl"][$node -> code] -> F_TemplateLabel;
			$node -> tpl_id = $prep["tpl"][$node -> code] -> F_TemplateCode;
			$node -> group = isset($prep["group"][$node -> code]) ? $prep["group"][$node -> code] : array();// 关联分组信息
			$node -> err = isset($prep["err"][$node -> code]) ? $prep["err"][$node -> code] : "";// 异常信息
			$node -> str = in_array($node -> code,$prep["str"]) ? 1 : 0;// 是否关联策略
			$rel = isset($prep["user"][$node -> code]) ? $prep["user"][$node -> code] : array();// 移动端添加收藏
			$node -> rel = json_encode($rel);
			$vDatas = array();
			foreach($node -> variantDatas as $n){
				$n -> value = $n -> value * 1;
				if($n -> value_kv != "" & $n -> value_kv != null){
					$kv = array();
					foreach(explode("-",$n -> value_kv) as $v){
						$expv = explode(":",$v);
						$kv[$expv[0]] = $expv[1];
					}
					$n -> show = isset($kv[$n -> value]) ? $kv[$n -> value] : $n -> value;
				}else{
					$n -> show = $n -> value;
				}
				$vDatas[$n -> label] = $n;
			}
			$node -> data = $vDatas;
			// 判断违规
			$node -> illegal = array(
				"code" => 0,
				"msg" => ""
			);
			$open = "";$temp = 0;$mode = 0;
			foreach($node -> variantDatas as $n){
				if($node -> tpl_tag == "havc2"){
					if($n -> label == "StartStopStatus")$open = $n -> value;
					if($n -> label == "TempSet")$temp = $n -> value;
					if($n -> label == "ModeCmd"){
						if($n -> label == "1")$mode = 1;
						if($n -> label == "8")$mode = 2;
					}
				}
				if($node -> tpl_tag == "ftkt2"){
					if($n -> label == "StartStopStatus")$open = $n -> value;
					if($n -> label == "TempSet")$temp = $n -> value;
					if($n -> label == "ModeCmd")$mode = $n -> value - 1;
				}
				if($node -> tpl_tag == "fjpg2"){
					if($n -> label == "RelayStatus" || $n -> label == "StartStopStatus")$open = $n -> value;
					if($n -> label == "CurTempSet" || $n -> label == "TempSet")$temp = $n -> value;
					if($n -> label == "CurModeSet"){
						$mode = $n -> value * 1 + 1;
					}else if($n -> label == "ModeStatus"){
						$mode = $n -> value > 2 ? $n -> value - 2 : $n -> value;
					}
				}
				if($node -> tpl_tag == "VRV"){
					if($n -> label == "StartStopStatus")$open = $n -> value;
					if($n -> label == "TempAdjust")$temp = $n -> value;
					if($n -> label == "AirConModeStatus")$mode = $n -> value;
				}
			}
			if($node -> online && $open == "1"){
				$now = date("H:i:s");
				$week = date("w");
				if($week ==0)$week = 7;
				foreach($prep["illegal"] as $ill){
					$till = 1;
					foreach($node -> group as $g){
						if(strstr($ill -> F_name,$g -> F_group_id)){
							if($ill -> F_type == "time"){
								if(strstr($ill -> F_type_val,$week)){
									if($now < $ill -> F_start || $now > $ill -> F_end){
										$node -> illegal["code"] = 1;
										$node -> illegal["msg"] = "允许开机时间(".$ill -> F_start." ~ ".$ill -> F_end.")";
									}
								}else{
									$node -> illegal["code"] = 1;
									$node -> illegal["msg"] = "允许开机时间为每周".$ill -> F_type_val;
								}
								$till = 0;
							}else if($ill -> F_type == "temp" && $till){
								if($temp && $mode){
									if($mode == 1 && $temp < $ill -> F_start){
										$node -> illegal["code"] = 2;
										$node -> illegal["msg"] = "当前设定温度为".$temp."℃,制冷温度下限为".$ill -> F_start."℃";
									}else if($mode == 2 && $temp > $ill -> F_end){
										$node -> illegal["code"] = 3;
										$node -> illegal["msg"] = "当前设定温度为".$temp."℃,制热温度上限为".$ill -> F_end."℃";
									}
								}
							}
						}
					}
				}
			}
			$vDatas = array();
			//添加通讯状态
			$online = new stdClass();
			$online -> label = "online";
			$online -> name = "通讯状态";
			$online -> value = $node -> online ? 1 : 0;
			$online -> unit = "";
			$online -> value_kv = "0:离线-1:在线";
			array_push($node -> variantDatas,$online);
			// 添加违规状态
			$illegal = new stdClass();
			$illegal -> label = "illegal";
			$illegal -> name = "违规状态";
			$illegal -> value = $node -> illegal["code"] == 0 ? 0 : 1;
			$illegal -> unit = "";
			$illegal -> value_kv = "0:正常-1:违规";
			array_push($node -> variantDatas,$illegal);
			foreach($node -> variantDatas as $n){
				$n -> value = $n -> value * 1;
				if($n -> value_kv != "" & $n -> value_kv != null){
					$kv = array();
					foreach(explode("-",$n -> value_kv) as $v){
						$expv = explode(":",$v);
						$kv[$expv[0]] = $expv[1];
					}
					$n -> show = isset($kv[$n -> value]) ? $kv[$n -> value] : $n -> value;
				}else{
					$n -> show = $n -> value;
				}
				$vDatas[$n -> label] = $n;
				unset($n -> code,$n -> label,$n -> time,$n -> value_kv);
			}
			$node -> data = $vDatas;
			$node -> len = count($node -> variantDatas);
			unset($node -> variantDatas);
		}
		return $nodes;
	}
	// 检查外部设备
	function checkAccess($app,$id,$type){
		static $accessdata = array();
		$db = new DB;
		$base = base();
		$rel = array();
		$data = $db -> query("select F_DeviceID from [Things+].[dbo].[TB_Share_AccessRel] where F_App = '".$app."' and F_GroupID = '".$id."'");
		if($type && count($data) == 0){
			$pid = $db -> query("select F_ParentID from [Things].[dbo].[tb_B_EntityTreeModel] where F_EntityID = ".$id);
			$pid = $pid[0];
			if($pid -> F_ParentID != 0)checkAccess($app,$pid -> F_ParentID,$type);
		}else{
			foreach($data as $d){
				array_push($rel,$d -> F_DeviceID);
			}
			$accessdata = curl($base -> ip."/API/RData/GetDeviceVariantDataByID/?device_str=".implode(",",$rel));
			foreach($accessdata as $node){
				$tplt = $db -> query("select b.F_TemplateLabel from [Things].[dbo].[tb_B_EntityTreeModel] a,[Things].[dbo].[tb_A_Template] b where a.F_EntityID = ".$node -> code." and a.F_NodeTemplate = b.F_TemplateCode");
				$node -> tpl_tag = $tplt[0] -> F_TemplateLabel;
				foreach($node -> variantDatas as $n){
					$n -> value = $n -> value * 1;
					if($n -> value_kv != "" & $n -> value_kv != null){
						$kv = array();
						foreach(explode("-",$n -> value_kv) as $v){
							$expv = explode(":",$v);
							$kv[$expv[0]] = $expv[1];
						}
						$n -> show = array_key_exists($n -> value,$kv) ? $kv[$n -> value] : $n -> value;
					}else{
						$n -> show = $n -> value;
					}
				}
			}
		}
		return $accessdata;
	}
	// 检查通用配置
	function checkBase(){
		$db = new DB;
		$base = base();
		$result = $db -> query("select F_Config from [Things+].[dbo].[TB_Share_Config] where F_App = 0 and F_Type = 'base_".$base -> version."'");
		if(count($result)){
			$result = $result[0] -> F_Config;
		}else{
			$result = '{"ip":"http://localhost","num":15,"clear":"0"}';
			$db -> query("insert into [Things+].[dbo].[TB_Share_Config] values('base_".$base -> version."',0,'".$result."')");
		}
		$plan = $db -> query("select * from [Things].[dbo].[tb_A_PlanTask] where F_TaskTag = 'task_Running'");
		if(!count($plan))$db -> query("insert into [Things].[dbo].[tb_A_PlanTask] values(1555555555,'通用控制运行任务','task_Running',NULL, NULL,'cyc',NULL,NULL,NULL,NULL,NULL,900,NULL)");
		return $result;
	}
	// 检查配置
	function checkConfig($app){
		checkBase();
		$db = new DB;
		$base = base();
		$config = '{"group":[{"text":"开机","unit":"台","color":"#159316","tpl":{}},{"text":"违规","unit":"台","color":"#faaf3a","tpl":{}}],"node":[{"name":"空调","tips":[{"text":"离线","color":"#cccccc","tpl":{}},{"text":"关机","color":"#fa5d3a","tpl":{}},{"text":"违规","color":"#faaf3a","tpl":{}},{"text":"开机","color":"#159316","tpl":{}}],"tpl":{},"batch":["f8921e763085797fc63b0448b93ea10b","910f0b27e24a332bcad046c054b3b6dd","8f797441bf671ed4ea1aa4158dea908a","3fbc403827abe4f36defb0d1da815743","9ff8ec76a97d331152e5f5dc98d4d625","bb8a1308cfdd3220c2aaddaa76e86d7f","c6117a3cdaed6ccd45d6a81dd3e2e80c","971246f5706ef7f8cf06ee01074bce1d"],"screen":1,"control":1,"hide":0,"group":0}],"device":{}}';
		$config = json_decode($config);
		$def = array(
			"ftkt2" => array(
				"group1" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"StartStopStatus","check":"==","judge":"1"}]',
				"group2" => '[{"attr":"illegal","check":"==","judge":"1"}]',
				"node1" => '[{"attr":"online","check":"==","judge":"0"}]',
				"node2" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"StartStopStatus","check":"==","judge":"0"}]',
				"node3" => '{"attr1":"RoomTemp","attr2":"StartStopStatus","attr3":"RSSI","icon":"saircon.png"}',
				"device" => '{"show":1,"chart":1,"calendar":1,"strategy":1,"class":"p1","ext":"","attr1":"ModeCmd","attr2":"0","attr3":"Ep","attr4":"TempSet","attr5":"RoomTemp","btn":[{"icon":"temp.png","com":["f8921e763085797fc63b0448b93ea10b"]},{"icon":"switch.png","com":["3fbc403827abe4f36defb0d1da815743","9ff8ec76a97d331152e5f5dc98d4d625"]},{"icon":"mode.png","com":["910f0b27e24a332bcad046c054b3b6dd","8f797441bf671ed4ea1aa4158dea908a"]}]}'
			),
			"hvac2" => array(
				"group1" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"StartStopStatus","check":"==","judge":"1"}]',
				"group2" => '[{"attr":"illegal","check":"==","judge":"1"}]',
				"node1" => '[{"attr":"online","check":"==","judge":"0"}]',
				"node2" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"StartStopStatus","check":"==","judge":"0"}]',
				"node3" => '{"attr1":"RoomTemp","attr2":"FanSpeedSet","attr3":"ErrCode","icon":"air.png"}',
				"device" => '{"show":1,"chart":1,"calendar":0,"strategy":1,"class":"p1","ext":"FanSpeedSet&StartStopStatus&2&remove&zhuan;FanSpeedSet&StartStopStatus&1&add&zhuan","attr1":"ModeSet","attr2":"FanSpeedSet","attr3":"0","attr4":"TempSet","attr5":"RoomTemp","btn":[{"icon":"temp.png","com":["f8921e763085797fc63b0448b93ea10b"]},{"icon":"switch.png","com":["3fbc403827abe4f36defb0d1da815743","9ff8ec76a97d331152e5f5dc98d4d625"]},{"icon":"mode.png","com":["910f0b27e24a332bcad046c054b3b6dd","8f797441bf671ed4ea1aa4158dea908a"]}]}'
			),
			"fjpg2" => array(
				"group1" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"RelayStatus","check":"==","judge":"1"}]',
				"group2" => '[{"attr":"illegal","check":"==","judge":"1"}]',
				"node1" => '[{"attr":"online","check":"==","judge":"0"}]',
				"node2" => '[{"attr":"online","check":"==","judge":"1","pos":"&&"},{"attr":"RelayStatus","check":"==","judge":"0"}]',
				"node3" => '{"attr1":"CurTempSet","attr2":"LockStatus","attr3":"RSSI","icon":"fancoil.png"}',
				"device" => '{"show":1,"chart":1,"calendar":1,"strategy":1,"class":"p1","ext":"AirStatus&RelayStatus&0&remove&zhuan;AirStatus&RelayStatus&1&add&zhuan","attr1":"CurModeSet","attr2":"AirStatus","attr3":"TotalTime","attr4":"CurTempSet","attr5":"RoomTemp","btn":[{"icon":"temp.png","com":["f8921e763085797fc63b0448b93ea10b"]},{"icon":"lock.png","com":["971246f5706ef7f8cf06ee01074bce1d","c6117a3cdaed6ccd45d6a81dd3e2e80c"]},{"icon":"switch.png","com":["3fbc403827abe4f36defb0d1da815743","9ff8ec76a97d331152e5f5dc98d4d625"]},{"icon":"mode.png","com":["910f0b27e24a332bcad046c054b3b6dd","8f797441bf671ed4ea1aa4158dea908a"]}]}'
			)
		);
		$tpl = $db -> query("select * from [Things].[dbo].[tb_A_Template] where F_TemplateLabel in ('ftkt2','fjpg2','hvac2')");
		forEach($tpl as $t){
			$label = $t -> F_TemplateLabel;
			$group0 = $config -> group[0];
			$group0 -> tpl -> $label = json_decode($def[$label]["group1"]);
			$group1 = $config -> group[1];
			$group1 -> tpl -> $label = json_decode($def[$label]["group2"]);

			$node0 = $config -> node[0] -> tips[0];
			$node0 -> tpl -> $label = json_decode($def[$label]["node1"]);
			$node1 = $config -> node[0] -> tips[1];
			$node1 -> tpl -> $label = json_decode($def[$label]["node2"]);
			$node2 = $config -> node[0] -> tips[2];
			$node2 -> tpl -> $label = json_decode($def[$label]["group2"]);
			$node3 = $config -> node[0] -> tips[3];
			$node3 -> tpl -> $label = json_decode($def[$label]["group1"]);
			$node4 = $config -> node[0] -> tpl;
			$node4 -> $label = json_decode($def[$label]["node3"]);

			$config -> device -> $label = json_decode($def[$label]["device"]);
			$db -> query("update [Things+].[dbo].[TB_command_impl] set F_template_id = ".$t -> F_TemplateCode.",F_template_name = '".$t -> F_TemplateName."' where F_template_name = '".$label."'");
		}
		$result = $db -> query("delete [Things+].[dbo].[TB_Share_Config] where F_Type = 'monitor_".$base -> version."' and F_App = '".$app."';insert into [Things+].[dbo].[TB_Share_Config] values('monitor_".$base -> version."','".$app."','".json_encode($config)."');");
		return json_encode($config);
	}
	// 准备数据
	function prepare(){
		$db = new DB;
		$result = array(
			"model" => array(),
			"action" => array(),
			"group" => array(),
			"work" => array(),
			"interface" => array(),
			"command" => array(),
			"node" => array(),
			"force" => array()
		);
		// 获取所有模型
		$model = $db -> query("select * from [Things+].[dbo].[TB_Share_Model]");
		foreach($model as $m){
			$result["model"][$m -> F_id] = $m;
		}
		// 获取所有动作模型，将轮询时间和次数分解，按F_time排序
		$action = $db -> query("select * from [Things+].[dbo].[TB_Share_Action] order by F_time");
		foreach($action as $a){
			if(!isset($result["action"][$a -> F_action_id]))$result["action"][$a -> F_action_id] = array();
			if($a -> F_poll_time && $a -> F_poll_num){
				$time = date("Y-m-d ".$a -> F_time.":s");
				for($i = 0;$i <= $a -> F_poll_num;$i++){
					$add = new stdClass();
					$add -> F_action_id = $a -> F_action_id;
					$add -> F_time = date("H:i",strtotime("+".($a -> F_poll_time * $i)." minute",strtotime($time)));
					$add -> F_command_id = $a -> F_command_id;
					$add -> F_value = $a -> F_value;
					array_push($result["action"][$a -> F_action_id],$add);
				}
			}else{
				array_push($result["action"][$a -> F_action_id],$a);
			}
			usort($result["action"][$a -> F_action_id],function($obj1,$obj2){
				return $obj1 -> F_time < $obj2 -> F_time ? -1 : 1;
			});
		}
		// 获取所有分组设备
		$group = $db -> query("select * from [Things+].[dbo].[TB_Share_GroupToNode]");
		foreach($group as $g){
			if(!isset($result["group"][$g -> F_group_id]))$result["group"][$g -> F_group_id] = array();
			array_push($result["group"][$g -> F_group_id],$g);
		}
		// 获取系统工作日节假日
		$work = $db -> query("select * from [Things].[dbo].[tb_D_WorkCalendar]");
		foreach($work as $w){
			$date = json_decode(json_encode($w -> F_Date));
			if(!isset($result["work"][$w -> F_Status]))$result["work"][$w -> F_Status] = array();
			array_push($result["work"][$w -> F_Status],date("Y-m-d",strtotime($date -> date)));
		}
		// 获取命令列表
		$interface = $db -> query("select * from [Things+].[dbo].[TB_command_interface]");
		foreach($interface as $i){
			$result["interface"][$i -> F_id] = $i;
		}
		// 获取设备基本信息
		$node = $db -> query("select * from [Things].[dbo].[tb_B_EntityTreeModel] where F_NodeTemplate != ''");
		foreach($node as $n){
			$result["node"][$n -> F_EntityID] = $n;
		}
		// 强制控制设备
		$force = $db -> query("select b.F_node_id from [Things+].[dbo].[TB_Share_Relation] a,[Things+].[dbo].[TB_Share_GroupToNode] b where a.F_id like '%force%' and a.F_group_id = b.F_group_id");
		foreach($force as $f){
			array_push($result["force"],$f -> F_node_id);
		}
		// 取TB_command_impl_mx表，按F_info_id分组，F_info_id为TB_command_impl的F_id
		$impl_mx = $db -> query("select * from [Things+].[dbo].[TB_command_impl_mx]");
		$mx = array();
		foreach($impl_mx as $m){
			if(!isset($mx[$m -> F_info_id]))$mx[$m -> F_info_id] = array();
			array_push($mx[$m -> F_info_id],$m);
		}
		// 取TB_command_impl_config表，按F_mx_id分组，F_mx_id为TB_command_impl_mx的F_id
		$impl_con = $db -> query("select * from [Things+].[dbo].[TB_command_impl_config]");
		$con = array();
		foreach($impl_con as $c){
			if(!isset($con[$c -> F_mx_id]))$con[$c -> F_mx_id] = array();
			if($c -> F_command_code == "")$c -> F_command_code = "writeData";
			if(!isset($con[$c -> F_mx_id][$c -> F_command_code]))$con[$c -> F_mx_id][$c -> F_command_code] = array();
			array_push($con[$c -> F_mx_id][$c -> F_command_code],$c);
		}
		$impl_pre = $db -> query("select * from [Things+].[dbo].[TB_command_impl_contion]");
		$pre = array();
		foreach($impl_pre as $p){
			if(!isset($pre[$p -> F_mx_id]))$pre[$p -> F_mx_id] = array();
			$algo = str_replace("不等于","!=",$p -> F_con);
			$algo = str_replace("大于",">",$algo);
			$algo = str_replace("小于","<",$algo);
			$algo = str_replace("等于","==",$algo);
			$algo = "{".$p -> F_code."}".$algo.$p -> F_value;
			array_push($pre[$p -> F_mx_id],$algo);
		}
		$impl = $db -> query("select * from [Things+].[dbo].[TB_command_impl]");
		foreach($impl as $i){
			$result["command"][$i -> F_template_id] = array();
			if(isset($mx[$i -> F_id])){
				foreach($mx[$i -> F_id] as $m){
					$result["command"][$i -> F_template_id][$m -> F_face_id] = array(
						"cmd" => array(),
						"pre" => isset($pre[$m -> F_id]) ? $pre[$m -> F_id] : array()
					);
					if(isset($con[$m -> F_id])){
						foreach($con[$m -> F_id] as $k => $d){
							$cmdData = array();
							foreach($d as $c){
								if($c -> F_value_code != ""){
									if($c -> F_type == "no"){// 无操作
										if($c -> F_code != "")$c -> F_value = str_replace("-","#",$c -> F_code);
									}else if($c -> F_type == "exp"){// 表达式处理
										$c -> F_value = 'exp#'.$c -> F_typecontent;
									}else if($c -> F_type == "time"){// 系统时间
										$c -> F_value = 'time#1';
									}
									$cmdData[$c -> F_value_code] = $c -> F_value;
								}
							}
							if(count($cmdData)){
								if($k == "writeData"){
									$cmdData = array(
										"deviceCode" => "",
										"writeData" => $cmdData
									);
								}else{
									$cmdData = array(
										"deviceCode" => "",
										"cmdName" => $k,
										"cmdData" => $cmdData
									);
								}
								array_push($result["command"][$i -> F_template_id][$m -> F_face_id]["cmd"],$cmdData); 
							}
						}
					}
				}
			}
		}
		return $result;
	}
	// 下发命令 $type 0:即时命令,1:批量任务,2:策略任务,3:重发任务
	function sendCommand($param,$type){
		// 返回结果和说明
		$log = array(
			"code" => "",
			"msg" => ""
		);
		$db = new DB();
		$base = base();
		// 数据准备
		$prep = prepare();
		// 第一次循环判断设备是否要获取实时数据
		$toGet = array();
		foreach($param as $p){
			foreach($p -> node as $node){
				// 设备模板的命令列表
				$cmdList = $prep["command"][$prep["node"][$node] -> F_NodeTemplate];
				foreach($p -> command as $cmd_key => $cmd_val){
					if(isset($cmdList[$cmd_key])){
						$string = json_encode($cmdList[$cmd_key]["cmd"]);
						// 有前置条件 || 值为模板变量 || 值为传入参数 || 值需要运算
						if(count($cmdList[$cmd_key]["pre"]) || strstr($string,"mb") || strstr($string,"arg") || strstr($string,"exp"))array_push($toGet,$node);
					}
				}
			}
		}
		// 去除重复设备
		$toGet = array_unique($toGet);
		// 获取设备实时参数
		$data = array();
		if(count($toGet)){
			$chunk = array_chunk($toGet,500);
			foreach($chunk as $c){
				$res = curl($base -> ip."/API/RData/GetDeviceVariantDataByID/?device_str=".implode(",",$c));
				$data = array_merge($data,intactAttr($res));
			}
		}
		$device = array();
		foreach($data as $d){
			$device[$d -> code] = $d;
		}
		// 第二次循环组装命令
		$task = array();
		foreach($param as $p_key => $p_val){
			$task[$p_key] = array(
				"id" => $p_val -> F_id,
				"name" => $p_val -> F_name,
				"app" => $p_val -> F_app,
				"sub" => array()
			);
			foreach($p_val -> node as $node){
				$node_info = $prep["node"][$node];
				if(isset($prep["command"][$node_info -> F_NodeTemplate])){
					// 设备模板的命令列表
					// 自定义错误码 -> 103
					$cmdList = $prep["command"][$node_info -> F_NodeTemplate];
					foreach($p_val -> command as $cmd => $arg){
						if(isset($cmdList[$cmd])){
							$cond = $cmdList[$cmd];
							// 将命令前置条件内参数替换成对应的值
							foreach($cond["pre"] as $pre_key => $pre_val){
								$pos_start = strpos($pre_val,"{") + 1;
								$pos_end = strpos($pre_val,"}");
								$sub_val = substr($pre_val,$pos_start,$pos_end - $pos_start);
								$cond["pre"][$pre_key] = isset($device[$node] -> data[$sub_val] -> value) ? str_replace("{".$sub_val."}",$device[$node] -> data[$sub_val] -> value,$pre_val) : 0;
							}
							// 判断前置条件
							if(count($cond["pre"]) == 0 || eval("return (".implode("&&",$cond["pre"]).");")){
								foreach($cond["cmd"] as $cmd_info){
									$cmd_info["deviceCode"] = $node;
									$cmd_data = isset($cmd_info["cmdName"]) ? $cmd_info["cmdData"] : $cmd_info["writeData"];
									// 命令的条件替换为对应的值
									foreach($cmd_data as $cmd_key => $cmd_val){
										if(!is_numeric($cmd_val)){// 值不是数字，需要运算
											$exp = explode("#",$cmd_val);
											if($exp[0] == "mb"){// 模板参数
												if(isset($device[$node] -> data[$exp[1]])){
													$cmd_val = $device[$node] -> data[$exp[1]] -> value;
												}else{
													$cmd_val = "";
													array_push($task[$p_key]["sub"],array(
														"taskName" => $prep["interface"][$cmd] -> F_name,
														"type" => "error",
														"content" => array($exp[1]),
														"args" => array(
															"deviceCode" => $node,
															"code" => "101",
															"msg" => "模板参数未获取到"
														)
													));
												}
											}else if($exp[0] == "arg"){//传入参数
												if(isset($arg[$exp[1]])){
													$cmd_val = $arg[$exp[1]];
												}else{
													$cmd_val = "";
													array_push($task[$p_key]["sub"],array(
														"taskName" => $prep["interface"][$cmd] -> F_name,
														"type" => "error",
														"content" => array($exp[1]),
														"args" => array(
															"deviceCode" => $node,
															"code" => "102",
															"msg" => "传入参数未获取到"
														)
													));
												}
											}else if($exp[0] == "time"){// 系统时间
												$cmd_val = date("Y-m-d H:i:s");
											}else if($exp[0] == "exp"){// 表达式处理
												// 将命令条件内参数替换成对应的值并计算
												$pos_start = strpos($exp[1],"{") + 1;
												$pos_end = strpos($exp[1],"}");
												$sub_val = substr($exp[1],$pos_start,$pos_end - $pos_start);
												$cmd_val = eval("return ".str_replace("{".$sub_val."}",$device[$node] -> data[$sub_val] -> value,$exp[1]).";");
											}
										}
										$cmd_data[$cmd_key] = $cmd_val;
										// 为空则删除该条命令
										if($cmd_val === "")unset($cmd_data[$cmd_key]);
									}
									// 命令组不为空添加到任务数组中
									if(count($cmd_data)){
										isset($cmd_info["cmdName"]) ?  $cmd_info["cmdData"] = $cmd_data :  $cmd_info["writeData"] = $cmd_data;
										$cmd_info = array(
											"taskName" => $prep["interface"][$cmd] -> F_name,
											"type" => isset($cmd_info["cmdName"]) ? "SendDeviceCustomCmd" : "WriteDeviceVariantData",
											"args" => $cmd_info
										);
										array_push($task[$p_key]["sub"],$cmd_info);
									}
								}
							}else{
								array_push($task[$p_key]["sub"],array(
									"taskName" => $prep["interface"][$cmd] -> F_name,
									"type" => "error",
									"content" => $cmdList[$cmd]["pre"],
									"args" => array(
										"deviceCode" => $node,
										"code" => "100",
										"msg" => "前置条件未通过"
									)
								));
							}
						}else{
							array_push($task[$p_key]["sub"],array(
								"taskName" => $prep["interface"][$cmd] -> F_name,
								"type" => "error",
								"content" => "",
								"args" => array(
									"deviceCode" => $node,
									"code" => "103",
									"msg" => "命令未实现"
								)
							));
						}
					}
				}
			}
			if(!count($task[$p_key]["sub"]))unset($task[$p_key]);
		}
		// 下发命令
		$sql = "";
		$curl_info = "";
		if(count($task)){
			foreach($task as $t){
				$check_num = 0;
				$task_num = 0;
				$task_id = uniqid("t");
				$send_param = array(
					"taskName" => "",
					"callbackUrl" => $base -> ip."/Php/lib/plugin/share/strategyBack.php",
					"subTasks" => array()
				);
				// 不是即时命令插入一条任务记录
				if($type != 0)$sql .= "insert into [Things+].[dbo].[TB_Share_Task] values('".$task_id."','".$t["name"]."','".$t["id"]."',".$type.",'".date("Y-m-d H:i:s")."',".$t["app"].");";
				// 循环子任务
				foreach($t["sub"] as $sub_key => $sub_val){
					$sub_id = uniqid("s");
					$code = "";
					$back = "";
					$cmd_name = $sub_val["taskName"];
					$sub_val["taskName"] = $sub_id;
					if($sub_val["type"] == "error"){
						$sql .= "insert into [Things+].[dbo].[TB_Share_Log] values('".$sub_id."','".$task_id."','".$sub_val["args"]["deviceCode"]."','".$cmd_name."',".$type.",'".json_encode($sub_val["args"])."','".date("Y-m-d H:i:s")."',".$t["app"].");";
						$sql .= "insert into [Things+].[dbo].[TB_Share_Back] values('".$sub_id."','".json_encode($sub_val["content"])."','".date("Y-m-d H:i:s")."','".$sub_val["args"]["code"]."');";
						$log["code"] = $sub_val["args"]["code"];
						$log["msg"] = $sub_val["args"]["msg"];
					}else{
						// 是即时命令直接发送,不是添加到$send_param中
						if($type == 0){
							$curl_info = curl($base -> ip."/API/IoT/".$sub_val["type"]."/","post",array("param" => json_encode($sub_val["args"])));
							$back = json_decode($curl_info);
							$code = $back -> errCode;
							if(isset($back -> cmdReturnData -> Code))$code = $back -> cmdReturnData -> Code;
							if(isset($back -> writeResult)){
								foreach($back -> writeResult as $key => $write){
									if($write != 0 && $key != "StartStopCmdTemp")$code = $write;
								}
							}
							$sql .= "insert into [Things+].[dbo].[TB_Share_Back] values('".$sub_id."','".json_encode($back)."','".date("Y-m-d H:i:s")."','".$code."');";
							$log["code"] = $code;
						}else{
							array_push($send_param["subTasks"],$sub_val);
							$check_num++;
							// 当子任务个数等于批量任务个数时发送一次命令
							if($check_num == $base -> num){
								$task_num++;
								$send_param["taskName"] = $task_id."_".$task_num;
								$curl_info = curl($base -> ip."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($send_param)));
								$send_param["subTasks"] = array();
								$check_num = 0;
							}
						}
						// 插入子任务记录
						$sql .= "insert into [Things+].[dbo].[TB_Share_Log] values('".$sub_id."','".$task_id."','".$sub_val["args"]["deviceCode"]."','".$cmd_name."',".$type.",'".json_encode($sub_val["args"])."','".date("Y-m-d H:i:s")."',".$t["app"].");";
					}
				}
				// 发送批量任务
				if(count($send_param["subTasks"])){
					$task_num++;
					$send_param["taskName"] = $task_id."_".$task_num;
					$curl_info = curl($base -> ip."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($send_param)));
				}
			}
		}
		if($sql != "")$db -> multi_query($sql);
		return $log;
	}
	// 时间差
	function timeDiffer($start,$end){
		$result = "";
		$date = strtotime(date($end,time())) - strtotime(date($start,time()));
		$days = round($date/(24*3600));
		$leave1 = $date%(24*3600);
		if($days)$result .= $days."天";
		$hours = round($leave1/(3600));
		$leave2 = $leave1%(3600);
		$result .= $hours."小时";
		$minutes = round($leave2/(60));
		if($minutes)$result .= $minutes."分钟";
		return $hours > 2 ? $result : "";
	}
?>
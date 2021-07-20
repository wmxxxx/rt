<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	// 开关时时间点
	$time = array("07:45","09:40","09:45","12:00","13:55","15:50","15:55","18:00","18:25","20:20","20:15","22:00");
	// 开学第一周周一
	$opens = "2019-09-02";
	// 获取本学期所有课程并按教室合并
	$all = $db -> multi_query("select * from [ORCL]..[GDCJDX].[V_INTERFACE_JSKB] where XNXQ01ID = '2019-2020-1'");
	$classroom = array();
	foreach($all as $a){
		if(!array_key_exists($a -> JSID,$classroom)){
			$classroom[$a -> JSID] = array();
		}
		array_push($classroom[$a -> JSID],$a);
	}
	// 将教室课程分解到每一天
	$db -> multi_query("delete [Things+].[dbo].[TB_Common_TimeTable]");
	foreach($classroom as $room => $classes){
		$timeTable = array();
		foreach($classes as $cs){
			$week = explode(",",trim($cs -> KKZCMX,","));
			$class = explode(",",trim($cs -> KCSJMX,","));
			foreach($week as $w){
				foreach($class as $c){
					$day = substr($c,0,1) * 1;
					$kc = substr($c,1) * 1;
					$timeTable[$w][$day][$kc] = true;
				}
			}
		}
		$sql = "";
		foreach($timeTable as $week => $wSub){
			foreach($wSub as $day => $dSub){
				$value = "F_nodeid,F_date";
				$values = "'".$room."','".date("Y-m-d",strtotime($opens." +".($week*1-1)." week +".($day*1-1)." day"))."'";
				foreach($dSub as $class => $cSub){
					if($cSub == true & $class%2 != 0){
						$value .= ",F_class".$class.",F_class".($class*1+1);
						$values .= ",'".$time[($class*1-1)]."','".$time[$class*1]."'";
					}
				}
				if($value != "F_nodeid,F_date"){
					$sql .= "insert into [Things+].[dbo].[TB_Common_TimeTable](".$value.") values(".$values.");";
				}
			}
		}
		$db -> multi_query($sql);
	}
	// 整理数据插入策略表
	$db -> multi_query("delete [Things+].[dbo].[TB_Common_TimeTable_PlanInfo];delete [Things+].[dbo].[TB_Common_TimeTable_PlanMx];delete [Things+].[dbo].[TB_Common_TimeTable_Plan];");
	$handle = $db -> multi_query("select * ,(select distinct JIAOSMC from [ORCL]..[GDCJDX].[V_INTERFACE_JSKB] where JSID = F_nodeid) as F_nodeName from [Things+].[dbo].[TB_Common_TimeTable]");
	$open = "f1539bb62b20d2ab3ee857ba08ce91ea";
	$close = "42210ec98d3669d893074075ed92bdaa";
	$close1 = "70f8a0cf63504a298e9c1842c9feb9b0";
	foreach($handle as $han){
		$SID = md5(microtime().$han -> F_nodeid);
		$db -> multi_query("insert into [Things+].[dbo].[TB_Common_TimeTable_PlanInfo](F_planName,F_strategy_id,F_st_time,F_project_no,F_keyid,F_ed_time,F_ct_time,F_send_flag,F_type,F_model_flag,F_model_type) values('课表-".$han -> F_nodeName."','".$han -> F_nodeid."','".$han -> F_date."',2001,'".$SID."','".$han -> F_date."','".date("Y-m-d h:i:sa")."',1,8,0,1)");
		$cName = "insert into [Things+].[dbo].[TB_Common_TimeTable_PlanMx](F_strategy_id,F_keyid,F_plan_id,F_time,F_lx_time,F_sx_time) values";
		$cValue = "";
		if($han -> F_class1 != null){
			$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class1)."','".$SID."','".$han -> F_class1."','10','15');";
			$class = $han -> F_class3 == null ? $han -> F_class2 : $han -> F_class4;
			$cValue .= $cName."('".$close."','".md5(microtime().$class)."','".$SID."','".$class."','5','7');";
		}else{
			if($han -> F_class3 != null){
				$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class3)."','".$SID."','".$han -> F_class3."','10','15');";
				$cValue .= $cName."('".$close."','".md5(microtime().$han -> F_class4)."','".$SID."','".$han -> F_class4."','5','7');";
			}
		}
		if($han -> F_class5 != null){
			$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class5)."','".$SID."','".$han -> F_class5."','10','15');";
			$class = $han -> F_class7 == null ? $han -> F_class6 : $han -> F_class8;
			$cValue .= $cName."('".$close."','".md5(microtime().$class)."','".$SID."','".$class."','5','7');";
		}else{
			if($han -> F_class7 != null){
				$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class7)."','".$SID."','".$han -> F_class7."','10','15');";
				$cValue .= $cName."('".$close."','".md5(microtime().$han -> F_class8)."','".$SID."','".$han -> F_class8."','5','7');";
			}
		}
		if($han -> F_class9 != null){
			$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class9)."','".$SID."','".$han -> F_class9."','10','15');";
			if($han -> F_class11 == null){
				$ml = $close1;
				$class = $han -> F_class10;
			}else{
				$ml = $close;
				$class = $han -> F_class12;
			}
			$cValue .= $cName."('".$ml."','".md5(microtime().$class)."','".$SID."','".$class."','5','7');";
		}else{
			if($han -> F_class11 != null){
				$cValue .= $cName."('".$open."','".md5(microtime().$han -> F_class11)."','".$SID."','".$han -> F_class11."','10','15');";
				$cValue .= $cName."('".$close."','".md5(microtime().$han -> F_class12)."','".$SID."','".$han -> F_class12."','5','7');";
			}
		}
		$db -> multi_query($cValue);
		$toNode = $db -> multi_query("select * from [Things].[dbo].[tb_B_EntityTreeProperty] where F_PropertyValue = '".$han -> F_nodeid."' and F_PropertyID = 1553617527");
		foreach($toNode as $node){
			$db -> multi_query("insert into [Things+].[dbo].[TB_Common_TimeTable_Plan](F_node_id,F_strategy_id,F_st_time,F_project_no,F_keyid,F_ed_time,F_ct_time,F_send_flag,F_type,F_plan_id) values('".$node -> F_EntityID."',0,'".$han -> F_date."',2001,'".md5(microtime().$node -> F_EntityID)."','".$han -> F_date."','".date("Y-m-d h:i:sa")."',1,8,'".$SID."')");
		}
	}
	echo 1;
?>

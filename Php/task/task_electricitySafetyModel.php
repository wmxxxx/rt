<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/base.php");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/utils.php");
	date_default_timezone_set("PRC");
	function task_electricitySafetyModel($sys,$fun){
		$db = new DB();
    $sql = "select distinct a.* from [Things+].dbo.tb_EM_SafeModel a,[Things+].dbo.tb_EM_ElectricToModel b where a.F_ModelID=b.F_ModelID";
    $result = $db -> query($sql);
  
    $nowTime = date("Y-m-d H:i:s");
    $nowDate = date("Y-m-d");
    $preDate = date("Y-m-d",strtotime("-1 day"));
    $hour = date("H");
    echo json_encode("***hour*** = ".$hour);
    $devCount = 0;
    $unSafetyCount = 0;
    $recoverCount = 0;
    for($i=0;$i<count($result);$i++){
      // 该模型下有几个设备
      $sql1 = "select a.*,b.F_EntityName from [Things+].dbo.tb_EM_ElectricToModel a,Things.dbo.tb_B_EntityTreeModel b where a.F_EntityID=b.F_EntityID and a.F_ModelID=" . $result[$i] -> F_ModelID;
      $result1 = $db -> query($sql1);
      
      // 该模型关联哪些人员编号
      $sql11 = "with t as (select a.* from 
                [Things+].dbo.tb_EM_ModelToUser a
                where a.F_ModelID=".$result[$i] -> F_ModelID.") 
                SELECT F_ModelID,F_ModelName, 
                STUFF(
                     ( 
                      SELECT ','+ convert(varchar,F_UserID) FROM t a FOR XML PATH('')
                     ),1 ,1, '') Users 
                FROM t 
                GROUP BY F_ModelID,F_ModelName;";
      $result11 = $db -> query($sql11);
      
      for($j=0;$j<count($result1);$j++){
        $devCount++;
        //瞬时参数
        $node_code = $result1[$j]->F_EntityID;
        $filter_str = $result[$i]->F_MonitorParam_I;
        
        $memcache = memcache_connect("localhost",11211);
        $node_cache = $memcache -> get("node");
        $node_param_cache = $memcache -> get("node_param");
        $tpl_param_cache = $memcache -> get("tpl_param");
        $result2 = new stdClass;
        $result2 -> status = false;
        $result2 -> code = $node_cache[$node_code] -> code;
        $result2 -> name = $node_cache[$node_code] -> name;
        $result2 -> time = null;
        $result2 -> online = null;
        $result2 -> variantDatas = array();
        if (array_key_exists($node_code,$node_cache)){
            $result2 -> status = true;
            $result2 -> time = $node_cache[$node_code] -> commtime;
            $result2 -> online = $node_cache[$node_code] -> online;
            $params = $tpl_param_cache[$node_cache[$node_code] -> tpl]; 
            for($k = 0;$k < count($params);$k++){
                if (array_key_exists($node_code . '-' . $params[$k] -> label,$node_param_cache)){
                    if(strpos($filter_str,$params[$k] -> label) !== false){
                        $param = new stdClass;
                        $param -> code = $params[$k] -> code;
                        $param -> label = $params[$k] -> label;
                        $param -> name = $params[$k] -> name;
                        $param -> unit = $params[$k] -> unit;
                        $param -> time = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> commtime;
                        $param -> value = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> value;
                        $param -> value_kv = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> value_kv;
                        array_push($result2 -> variantDatas,$param);
                    }
                }
            }
        }
        
        
        //用量参数
        $sql02 = "exec proc_API_GetNodePointData ".$result1[$j]->F_EntityID.",'". $result[$i]->F_MonitorParam_U ."','". $preDate ."','". $nowDate ."','',null,null";
        $result02 = $db -> query($sql02);
        
        $flag = 0;//正常异常标志，0：正常，1：异常
        $flag1 = 0;
        $flag2 = 0;
        $errorStr = $result1[$j]->F_EntityName."电表";
        
        if($result2!=null){
          $value = $result2->variantDatas[0]->value;
          //echo json_encode("value = ".$value);
          if(floatval($value)>$result[$i]->F_UpperLimit_I){
            $flag1 = 1;
            $unSafetyCount++;
            $errorStr .= $result[$i]->F_ParamName_I ."的值为".$value."，超过上限值". $result[$i]->F_UpperLimit_I;
          }
        }
        
        if(count($result02)>0){
          $data = $result02;
          
          for($b=0;$b<count($data);$b++){
            
            
            if($hour==0){
            
              if($data[$b]->F_Day==$preDate && $data[$b]->F_Hour==23 && floatval($data[$b]->F_EnergyData)>$result[$i]->F_UpperLimit_U){
                $errorStr .= $preDate."日23时用电量为".$data[$b]->F_EnergyData."，超过上限值".$result[$i]->F_UpperLimit_U;
                $flag2 = 1;
              }
            }else{
              if($data[$b]->F_Day==$nowDate && $data[$b]->F_Hour==($hour-1) && (floatval($data[$b]->F_EnergyData)>$result[$i]->F_UpperLimit_U)){
                $errorStr .= $nowDate."日".($hour-1)."时用电量为".$data[$b]->F_EnergyData."，超过上限值".$result[$i]->F_UpperLimit_U;
                $flag2 = 1;
              }
            }
          }
        }
        
        if($flag1==1||$flag2==1){
          $unSafetyCount++;
          $flag = 1;
          //echo json_encode("flag = ".$flag);
        }
        $sql2 = "select top 1 * from [Things+].dbo.tb_EM_AlarmInfo where F_EntityID=".$result1[$j]->F_EntityID." and F_ModelID=". $result[$i] -> F_ModelID ." order by F_AlarmTime desc";
        $result3 = $db -> query($sql2);
        $nowState = 0;// 设备当前状态 0没有数据，1有报警没恢复，2有报警，已恢复
        if(count($result3)==1){
          if($result3[0]->F_RecoverTime!=null){
            $nowState = 2;
          }else{
            $nowState = 1;
          }
        }
        if($flag==1){
          //插入报警表
          $sql2 = "insert into [Things+].dbo.tb_EM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result[$i] -> F_ModelID .",'". $nowTime ."',null,'". $errorStr ."',0,null,null,null,2) ";
          $db -> query($sql2);
           // 同步
          T_Utils::writeExtendAlarmEvent(4,'',$result1[$j]->F_EntityID,'',$sys,$result[$i] -> F_ModelID,$errorContent,$result11[0].Users);
        }
        if($flag==0){//没有异常也插入
          if($nowState==1){
            $recoverCount++;
          }
          
          $sql3 = "insert into [Things+].dbo.tb_EM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."','". $nowTime ."',null,0,null,null,null,2) ";
          $db -> query($sql3);
        }
      }
    }
    return "共检测电表". $devCount . "个，其中".$unSafetyCount."个电表供电异常,".$recoverCount."个电表恢复报警，请在电系统中查看报警详情！";
	}
?>
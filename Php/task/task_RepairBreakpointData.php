<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
    
	$redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    try
    {
        if(!$redis -> get("node")){
            $node = array();
            $app_node = array();
            $tpl_node = array();
            $node_map = array();
            $sql = "select a.F_NodeCode as code,a.F_NodeNo as no ,a.F_NodeID as id,dbo.fun_GetEntityPathName(a.F_NodeCode) as name,a.F_IsStorage as storage,a.F_AppCode as app,a.F_TemplateCode as tpl, dbo.fun_GetNodeEnergyType(a.F_NodeCode) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_NodeCode) as device_type_id,c.F_GroupName AS device_type_name from dbo.tb_A_IoTNode a left outer join dbo.tb_B_EntityTreeModel b ON dbo.fun_GetNodeEnergyType(a.F_NodeCode) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c ON dbo.fun_GetNodeDeviceType(a.F_NodeCode) = c.F_GroupID where a.F_TemplateCode is not null and a.F_TemplateCode <> '' union select a.F_EntityID as code,null as no,null as id,a.F_EntityName as name,1 as storage,null as app,a.F_NodeTemplate as tpl,dbo.fun_GetNodeEnergyType(a.F_EntityID) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_EntityID) as device_type_id,c.F_GroupName as device_type_name from dbo.tb_B_EntityTreeModel a left outer join dbo.tb_B_EntityTreeModel b on dbo.fun_GetNodeEnergyType(a.F_EntityID) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c on dbo.fun_GetNodeDeviceType(a.F_EntityID) = c.F_GroupID where a.F_ObjectGroup in ('2','3') and dbo.fun_GetNodeAorVType(a.F_EntityID) = 0 and a.F_NodeTemplate is not null and a.F_NodeTemplate <> ''";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                if($result[$i] -> app != null){
                    if(array_key_exists($result[$i] -> app,$app_node)){
                        array_push($app_node[$result[$i] -> app],$result[$i]);
                    }else{
                        $app_node[$result[$i] -> app] = array($result[$i]);
                    }
                }
                if($result[$i] -> tpl != null){
                    if(array_key_exists($result[$i] -> tpl,$tpl_node)){
                        array_push($tpl_node[$result[$i] -> tpl],$result[$i]);
                    }else{
                        $tpl_node[$result[$i] -> tpl] = array($result[$i]);
                    }
                }
                $result[$i] -> updatetime = null;
                $result[$i] -> commtime = null;
                $result[$i] -> commtime_stamp = null;
                $result[$i] -> online = null;
                $node[$result[$i] -> code] = $result[$i];
                $node_map[$result[$i] -> app . '-' . $result[$i] -> no] = $result[$i] -> code;
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点“' . $result[$i] -> name . '”！<br>';
            };
		    $redis -> set("node",json_encode($node));
		    $redis -> set("app_node",json_encode($app_node));
		    $redis -> set("tpl_node",json_encode($tpl_node));
		    $redis -> set("node_map",json_encode($node_map));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点完成！共计' . count($result) . '个。<br>';
        }
        if(!$redis -> get("tpl_param")){
            $tpl_param = array();
            $param_map = array();
            $sql = "select a.F_TemplateCode as tpl,b.F_TemplateName as tpl_name,F_ValueCode as code,F_ValueName as name,F_ValueLabel as label,F_ValueType as vtype,F_DataType as dtype,F_PrecisionRatio as pr,F_DecimalPoint as dp,F_Unit as unit,F_CommCycle as ccycle,F_KV as kv,F_IsStorage as storage,F_StorageCycle as scycle,F_RangeLower as rl,F_RangeUpper as ru,F_SlopeLower as sl,F_SlopeUpper as su,F_Benchmark as bv,F_Fluctuated as fv,a.F_IsRefer as refer,a.F_ValueProperty as vp,a.F_IsDisplay as display,a.F_OrderNum as onum from dbo.tb_A_Value a,dbo.tb_A_Template b where a.F_TemplateCode = b.F_TemplateCode order by a.F_TemplateCode,a.F_OrderNum";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                if (array_key_exists($result[$i] -> tpl,$tpl_param)){
                    array_push($tpl_param[$result[$i] -> tpl],$result[$i]);
                }else{
                    $tpl_param[$result[$i] -> tpl] = array($result[$i]);
                }
                $param_map[$result[$i] -> tpl . '-' . $result[$i] -> label] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板“' . $result[$i] -> tpl_name . '”的通讯变量“' . $result[$i] -> name . '”！<br>';
            };
		    $redis -> set("param_map",json_encode($param_map));
		    $redis -> set("tpl_param",json_encode($tpl_param));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板变量完成！共计' . count($result) . '个。<br>';
        }
        if(!$redis -> get("formula_map")){
            $formula_map = array();
            $sql = "select a.F_NodeCode as ncode,a.F_NodeName as nname,a.F_ValueLabel as vlabel,a.F_ValueName as vname,a.F_PrecisionRatio as pr,a.F_DecimalPoint as dp,a.F_Formula as formula,a.F_KV as kv,a.F_IsStorage as storage,a.F_StorageCycle as scycle,a.F_NodeCode as  map_ncode,b.F_ValueLabel as map_vlabel,'tpl' as map_type from (select a.F_NodeCode,a.F_NodeName,b.F_ValueName,b.F_ValueLabel,b.F_PrecisionRatio,b.F_DecimalPoint,b.F_Formula,b.F_KV,b.F_IsStorage,b.F_StorageCycle from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where b.F_Formula <> '' and b.F_IsRefer = 0 and a.F_TemplateCode = b.F_TemplateCode) a,(select a.F_NodeCode,b.F_ValueLabel from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where a.F_TemplateCode = b.F_TemplateCode) b where a.F_NodeCode = b.F_NodeCode and b.F_ValueLabel = a.F_Formula union select a.F_VirtualNCode as ncode,b.F_EntityName as nname,a.F_VirtualVLabel as vlabel,c.F_ValueName as vname,c.F_PrecisionRatio as pr,c.F_DecimalPoint as dp,a.F_Formula as formula,c.F_KV as kv,c.F_IsStorage as storage,c.F_StorageCycle as scycle,d.F_EntityID as map_ncode,e.F_ValueLabel as map_vlabel,'node' as map_type from dbo.tb_B_VirtualCompute a,dbo.tb_B_EntityTreeModel b,dbo.tb_A_Value c,dbo.tb_B_EntityTreeModel d,dbo.tb_A_Value e where a.F_MType = '1' and a.F_VirtualNCode = b.F_EntityID and a.F_VirtualVLabel = c.F_ValueLabel and d.F_EntityID = substring(a.F_NAndV,1,CHARINDEX('.',a.F_NAndV) - 1) and d.F_NodeTemplate = e.F_TemplateCode and e.F_ValueLabel = substring(a.F_NAndV,CHARINDEX('.',a.F_NAndV) + 1,len(a.F_NAndV))";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                if (!array_key_exists($result[$i] -> map_ncode . '-' . $result[$i] -> map_vlabel,$formula_map)){
                    $formula_map[$result[$i] -> map_ncode . '-' . $result[$i] -> map_vlabel] = array();
                }
                array_push($formula_map[$result[$i] -> map_ncode . '-' . $result[$i] -> map_vlabel],$result[$i]);
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备“' . $result[$i] -> nname . '”的映射变量“' . $result[$i] -> vname . '”！<br>';
                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备“' . $result[$i] -> nname . '”的映射变量“' . $result[$i] -> vname . "”！\r\n");
            };
		    $redis -> set("formula_map",count($result) > 0 ? json_encode($formula_map) : new stdClass);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备的映射变量完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备的映射变量完成！共计' . count($result) . "个。\r\n");
        }
        if(!$redis -> get("param_deviation")){
            $param_deviation = array();
            $sql = "select F_NodeCode as code,F_ValueLabel as label,F_DeviationValue dvalue from dbo.tb_A_DeviationValue";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                $param_deviation[$result[$i] -> code . '-' . $result[$i] -> label] = $result[$i];
            };
		    $redis -> set("param_deviation",count($result) > 0 ? json_encode($param_deviation) : json_encode(new stdClass));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量修正偏差完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量修正偏差完成！共计' . count($result) . "个。\r\n");
        }
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
    
    try
    {
        $app_cache = json_decode($redis -> get("app"));
        $node_cache = json_decode($redis -> get("node"));
        $tpl_param_cache = json_decode($redis -> get("tpl_param"));
        $formula_map_cache = json_decode($redis -> get("formula_map"));
        $param_map_cache = json_decode($redis -> get("param_map"));
        $param_deviation_cache = json_decode($redis -> get("param_deviation"));
        
        $sql = "select top 10000 F_NodeCode as ncode,F_ValueLabel as vlabel,convert(varchar,F_StartTime,120) as startTime,convert(varchar,F_EndTime,120) as endTime from tb_C_RepairDataTotal where F_RepairStatus = 0 order by F_StartTime desc";
        $repair = $db -> query($sql);
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务读取最新待断点续传记录' . count($repair) . '条！<br>';
        for($i = 0;$i < count($repair);$i++){
            $status = false;
            $ncode = $repair[$i] -> ncode;
            $vlabel = $repair[$i] -> vlabel;
            $node_code_label = $ncode . "-" . $vlabel;
            $app_code = $node_cache -> $ncode -> app;
            $app_obj = $app_cache -> $app_code;
            $temp_tpl_label = $node_cache -> $ncode -> tpl . "-" . $vlabel;
            $param_obj = $param_map_cache -> $temp_tpl_label;
            $params = array('deviceCode' => $node_cache -> $ncode -> no,'variantCode' => $vlabel,'startTime'=> strtotime($repair[$i] -> startTime), 'endTime'=> strtotime($repair[$i] -> endTime), "start" => 0,"len" => 1000);
            
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . $repair[$i] -> startTime . ']至[' . $repair[$i] -> endTime . ']区间的数据！<br>';
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量历史数据接口，传递参数：' . json_encode($params) . '<br>';         
            $utc = time();
            $temp = array((string)$utc, $app_obj -> skey, $app_obj -> id);
            sort($temp);
            $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
            $url = $app_obj -> ip . ':' . $app_obj -> port . "/app/" . $app_obj -> id . "/api/protected/getDeviceHistData?";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url . http_build_query($sign));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl, CURLOPT_POST,1 );
            curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            $result = curl_exec($curl);
                        
            if($result === false){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量历史数据接口失败！错误信息：' . curl_error($curl) . '<br>';
                continue;
            }
            $resJson = json_decode($result);
            if(is_null($resJson)){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量历史数据接口解析失败！返回结果：' . $result . '<br>';
                continue;
            }
            if($resJson -> errCode != 0){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口错误！错误码：' . $resJson -> errCode . '，错误消息：' . $resJson -> errMsg . '。<br>';
                continue;
            }
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口成功！返回数据记录' . count($resJson -> data) . '条！<br>';
            
            $sql = '';
            $msg = '';
            $storagetime_stamp = 0;
            for($k = 0;$k < count($resJson -> data);$k++){
                $status = true;
                $time = $resJson -> data[$k] -> time;
                $value = number_format($param_obj -> pr * str_replace(',','',$resJson -> data[$k] -> value),$param_obj -> dp, ".", "");
                $end_value = $value + (property_exists($param_deviation_cache,$node_code_label) ? $param_deviation_cache -> $node_code_label -> dvalue : 0);
                
                $valid = true;
                $error_msg = '';
                if($param_obj -> rl != null && ($end_value < $param_obj -> rl || $end_value > $param_obj -> ru)){
                    $valid = false;
                    $error_msg = '数据不在量程范围！';
                }else if($param_obj -> bv != null && ($end_value < ($param_obj -> bv - $param_obj -> fv) || $end_value > ($param_obj -> bv + $param_obj -> fv))){
                    $valid = false;
                    $error_msg = '数据不在基准浮动范围！';
                }
                if($time - $storagetime_stamp > $param_obj -> scycle){
                    if($valid){
                        $sql = "insert into tb_C_DataBuffer values (" . $ncode . ",'" . $vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $end_value . ",1) insert into tb_C_RepairDataDetail values (" . $ncode . ",'" . $vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $end_value . ",getdate()) ";
                        if($db -> execute($sql)){
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库成功！<br>';
                        }else{
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库失败！错误消息：' .  print_r(sqlsrv_errors()) . '<br>';
                        }
                        if (property_exists($formula_map_cache,$node_code_label) && $formula_map_cache -> $node_code_label -> storage == 1){
                            $m_ncode = $formula_map_cache -> $node_code_label -> ncode;
                            $m_vlabel = $formula_map_cache -> $node_code_label -> vlabel;
                            if($formula_map_cache -> $node_code_label -> map_type == 'tpl'){
                                $m_value = number_format($formula_map_cache -> $node_code_label -> pr * str_replace($vlabel,$end_value,$formula_map_cache -> $node_code_label -> formula),$formula_map_cache -> $node_code_label -> dp, ".", "");
                            }else if($formula_map_cache -> $node_code_label -> map_type == 'node'){
                                $m_value = number_format($formula_map_cache -> $node_code_label -> pr * str_replace($formula_map_cache -> $node_code_label -> map_ncode . '.' . $vlabel,$end_value,$formula_map_cache -> $node_code_label -> formula),$formula_map_cache -> $node_code_label -> dp, ".", "");
                            }
                            $sql = "insert into tb_C_DataBuffer values (" . $m_ncode . ",'" . $m_vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $m_value . ",1) ";
                            if($db -> execute($sql)){
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务映射设备“' . $node_cache -> $m_ncode -> name . '”参数“' . $m_vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $m_value . '到数据库成功！<br>';
                            }else{
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务映射设备“' . $node_cache -> $m_ncode -> name . '”参数“' . $m_vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $m_value . '到数据库失败！错误消息：' .  print_r(sqlsrv_errors()) . '<br>';
                            }
                        }
                        $storagetime_stamp = $time;
                    }else{
                        echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库失败！错误消息：' .  $error_msg . '<br>';
                    }
                }
            }
            $totalCount = $resJson -> totalCount;
            $tempCount = count($resJson -> data);
            while($totalCount - $tempCount > 0){
                $params['start'] = $tempCount;
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量历史数据接口，传递参数：' . json_encode($params) . '<br>';
                curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
                $result2 = curl_exec($curl);
                $resJson2 = json_decode($result2);
                if($result2 === false){
                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量历史数据接口失败！错误信息：' . curl_error($curl) . '<br>';
                    continue;
                }
                if(is_null($resJson2)){
                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量历史数据接口解析失败！返回结果：' . $result2 . '<br>';
                    continue;
                }
                if($resJson2 -> errCode != 0){
                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口错误！错误码：' . $resJson2 -> errCode . '，错误消息：' . $resJson2 -> errMsg . '。<br>';
                    continue;
                }
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口成功！返回数据记录' . count($resJson2 -> data) . '条！<br>';
                            
                $sql = '';
                $msg = '';
                for($k = 0;$k < count($resJson2 -> data);$k++){
                    $time = $resJson2 -> data[$k] -> time;
                    $value = number_format($param_obj -> pr * str_replace(',','',$resJson2 -> data[$k] -> value),$param_obj -> dp, ".", "");
                    $end_value = $value + (property_exists($param_deviation_cache,$node_code_label) ? $param_deviation_cache -> $node_code_label -> dvalue : 0);
                    
                    $valid = true;
                    $error_msg = '';
                    if($param_obj -> rl != null && ($end_value < $param_obj -> rl || $end_value > $param_obj -> ru)){
                        $valid = false;
                        $error_msg = '数据不在量程范围！';
                    }else if($param_obj -> bv != null && ($end_value < ($param_obj -> bv - $param_obj -> fv) || $end_value > ($param_obj -> bv + $param_obj -> fv))){
                        $valid = false;
                        $error_msg = '数据不在基准浮动范围！';
                    }
                    if($time - $storagetime_stamp > $param_obj -> scycle){
                        if($valid){
                            $sql = "insert into tb_C_DataBuffer values (" . $ncode . ",'" . $vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $end_value . ",1) insert into tb_C_RepairDataDetail values (" . $ncode . ",'" . $vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $end_value . ",getdate()) ";
                            if($db -> execute($sql)){
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库成功！<br>';
                            }else{
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库失败！错误消息：' .  print_r(sqlsrv_errors()) . '<br>';
                            }
                            if (property_exists($formula_map_cache,$node_code_label) && $formula_map_cache -> $node_code_label -> storage == 1){
                                $m_ncode = $formula_map_cache -> $node_code_label -> ncode;
                                $m_vlabel = $formula_map_cache -> $node_code_label -> vlabel;
                                if($formula_map_cache -> $node_code_label -> map_type == 'tpl'){
                                    $m_value = number_format($formula_map_cache -> $node_code_label -> pr * str_replace($vlabel,$end_value,$formula_map_cache -> $node_code_label -> formula),$formula_map_cache -> $node_code_label -> dp, ".", "");
                                }else if($formula_map_cache -> $node_code_label -> map_type == 'node'){
                                    $m_value = number_format($formula_map_cache -> $node_code_label -> pr * str_replace($formula_map_cache -> $node_code_label -> map_ncode . '.' . $vlabel,$end_value,$formula_map_cache -> $node_code_label -> formula),$formula_map_cache -> $node_code_label -> dp, ".", "");
                                }
                                $sql = "insert into tb_C_DataBuffer values (" . $m_ncode . ",'" . $m_vlabel . "','" . date("Y-m-d H:i:s",$time) . "'," . $m_value . ",1) ";
                                if($db -> execute($sql)){
                                    echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务映射设备“' . $node_cache -> $m_ncode -> name . '”参数“' . $m_vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $m_value . '到数据库成功！<br>';
                                }else{
                                    echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务映射设备“' . $node_cache -> $m_ncode -> name . '”参数“' . $m_vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $m_value . '到数据库失败！错误消息：' .  print_r(sqlsrv_errors()) . '<br>';
                                }
                            }
                            $storagetime_stamp = $time;
                        }else{
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：' . '系统服务续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . date("Y-m-d H:i:s",$time) . ']点的数据值' . $end_value . '到数据库失败！错误消息：' .  $error_msg . '<br>';
                        }
                    }
                }
                $tempCount = $tempCount + count($resJson2 -> data);
            };
            if($status){
                $sql = "update tb_C_RepairDataTotal set F_RepairStatus = 1,F_RepairTime = getdate() where F_NodeCode = " . $ncode . " and F_ValueLabel = " . $vlabel . " and F_StartTime = '" . $repair[$i] -> startTime . "'";
                if($db -> execute($sql)){
                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务完成续传设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . $repair[$i] -> startTime . ']至[' . $repair[$i] -> endTime . ']区间的数据！<br>';
                }
            }else{
                $sql = "update tb_C_RepairDataTotal set F_RepairStatus = -1,F_RepairTime = getdate() where F_NodeCode = " . $ncode . " and F_ValueLabel = " . $vlabel . " and F_StartTime = '" . $repair[$i] -> startTime . "'";
                if($db -> execute($sql)){
                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务没有获取到数据，未完成设备“' . $node_cache -> $ncode -> name . '”参数“' . $vlabel . '”[' . $repair[$i] -> startTime . ']至[' . $repair[$i] -> endTime . ']区间的数据！<br>';
                }
            }
        };
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务获取通讯数据发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
?>
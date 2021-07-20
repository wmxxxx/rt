<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
    
    if(!array_key_exists('start',$_GET) || !array_key_exists('end',$_GET)){
        echo('传递参数不符合要求！');
        exit;
    }
    $start = strtotime($_GET["start"]);
    $end = strtotime($_GET["end"]);
    
	$redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    try
    {
        if(!$redis -> get("node")){
            $node = array();
            $app_node = array();
            $tpl_node = array();
            $node_map = array();
            $sql = "select a.F_NodeCode as code,a.F_NodeNo as no ,a.F_NodeID as id,dbo.fun_GetEntityPathName(a.F_NodeCode)as name,a.F_IsStorage as storage,a.F_AppCode as app,a.F_TemplateCode as tpl, dbo.fun_GetNodeEnergyType(a.F_NodeCode) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_NodeCode) as device_type_id,c.F_GroupName AS device_type_name from dbo.tb_A_IoTNode a left outer join dbo.tb_B_EntityTreeModel b ON dbo.fun_GetNodeEnergyType(a.F_NodeCode) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c ON dbo.fun_GetNodeDeviceType(a.F_NodeCode) = c.F_GroupID where a.F_TemplateCode is not null and a.F_TemplateCode <> '' union select a.F_EntityID as code,null as no,null as id,a.F_EntityName as name,1 as storage,null as app,a.F_NodeTemplate as tpl,dbo.fun_GetNodeEnergyType(a.F_EntityID) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_EntityID) as device_type_id,c.F_GroupName as device_type_name from dbo.tb_B_EntityTreeModel a left outer join dbo.tb_B_EntityTreeModel b on dbo.fun_GetNodeEnergyType(a.F_EntityID) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c on dbo.fun_GetNodeDeviceType(a.F_EntityID) = c.F_GroupID where a.F_ObjectGroup in ('2','3') and dbo.fun_GetNodeAorVType(a.F_EntityID) = 0 and a.F_NodeTemplate is not null and a.F_NodeTemplate <> ''";
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
            $formula_map = new stdClass;
            $sql = "select a.F_NodeCode as ncode,a.F_NodeName as nname,a.F_ValueLabel as vlabel,a.F_ValueName as vname,a.F_PrecisionRatio as pr,a.F_DecimalPoint as dp,a.F_Formula as formula,a.F_KV as kv,a.F_IsStorage as storage,a.F_StorageCycle as scycle,a.F_NodeCode as  map_ncode,b.F_ValueLabel as map_vlabel,'tpl' as map_type from (select a.F_NodeCode,a.F_NodeName,b.F_ValueName,b.F_ValueLabel,b.F_PrecisionRatio,b.F_DecimalPoint,b.F_Formula,b.F_KV,b.F_IsStorage,b.F_StorageCycle from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where b.F_Formula <> '' and b.F_IsRefer = 0 and a.F_TemplateCode = b.F_TemplateCode) a,(select a.F_NodeCode,b.F_ValueLabel from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where a.F_TemplateCode = b.F_TemplateCode) b where a.F_NodeCode = b.F_NodeCode and b.F_ValueLabel = a.F_Formula union select a.F_VirtualNCode as ncode,b.F_EntityName as nname,a.F_VirtualVLabel as vlabel,c.F_ValueName as vname,c.F_PrecisionRatio as pr,c.F_DecimalPoint as dp,a.F_Formula as formula,c.F_KV as kv,c.F_IsStorage as storage,c.F_StorageCycle as scycle,d.F_EntityID as map_ncode,e.F_ValueLabel as map_vlabel,'node' as map_type from dbo.tb_B_VirtualCompute a,dbo.tb_B_EntityTreeModel b,dbo.tb_A_Value c,dbo.tb_B_EntityTreeModel d,dbo.tb_A_Value e where a.F_MType = '1' and a.F_VirtualNCode = b.F_EntityID and a.F_VirtualVLabel = c.F_ValueLabel and d.F_EntityID = substring(a.F_NAndV,1,CHARINDEX('.',a.F_NAndV) - 1) and d.F_NodeTemplate = e.F_TemplateCode and e.F_ValueLabel = substring(a.F_NAndV,CHARINDEX('.',a.F_NAndV) + 1,len(a.F_NAndV))";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                $formula_map[$result[$i] -> map_ncode . '-' . $result[$i] -> map_vlabel] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备“' . $result[$i] -> nname . '”的映射变量“' . $result[$i] -> vname . '”！<br>';
            };
		    $redis -> set("formula_map",json_encode($formula_map));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备的映射变量完成！共计' . count($result) . '个。<br>';
        }
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
    
    try
    {
        $app_cache = json_decode($redis -> get("app"));
        $app_node_cache = json_decode($redis -> get("app_node"));
        $tpl_param_cache = json_decode($redis -> get("tpl_param"));
        $formula_map_cache = json_decode($redis -> get("formula_map"));

        foreach($app_cache as $app_code => $app_obj){
            $app_node_array = $app_node_cache -> $app_code;
            for($i = 0;$i < count($app_node_array);$i++){
                $app_node_tpl = $app_node_array[$i] -> tpl;
                $tpl_param_array = $tpl_param_cache -> $app_node_tpl;
                
                $node_code = $app_node_array[$i] -> code;
                $node_name = $app_node_array[$i] -> name;
                for($j = 0;$j < count($tpl_param_array);$j++){
                    $node_code_label = $node_code . "-" . $tpl_param_array[$j] -> label;
                    
                    if($tpl_param_array[$j] -> vtype == '1' && $tpl_param_array[$j] -> vp == '1' && $tpl_param_array[$j] -> storage == 1){
                        $params = array('deviceCode' => $app_node_array[$i] -> no,'variantCode' => $tpl_param_array[$j] -> label,'startTime'=> $start, 'endTime'=> $end, "start" => 0,"len" => 200);
                        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量统计数据接口，传递参数：' . json_encode($params) . '<br>';
                        
                        $utc = time();
                        $temp = array((string)$utc, $app_obj -> skey, $app_obj -> id);
                        sort($temp);
                        $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
                        $url = $app_obj -> ip . ':' . $app_obj -> port . "/app/" . $app_obj -> id . "/api/protected/getDeviceStaticData?";
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url . http_build_query($sign));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($curl, CURLOPT_POST,1 );
                        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
                        $result = curl_exec($curl);
                        
                        if($result === false){
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量统计数据接口失败！错误信息：' . curl_error($curl) . '<br>';
                            continue;
                        }
                        $resJson = json_decode($result);
                        if(is_null($resJson)){
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量统计数据接口解析失败！返回结果：' . $result . '<br>';
                            continue;
                        }
                        if($resJson -> errCode != 0){
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口错误！错误码：' . $resJson -> errCode . '，错误消息：' . $resJson -> errMsg . '。<br>';
                            continue;
                        }
                        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口成功！返回数据记录' . count($resJson -> data) . '条！<br>';
                        
                        $sql = '';
                        $msg = '';
                        for($k = 0;$k < count($resJson -> data);$k++){
                            $value_code = $tpl_param_array[$j] -> code;
                            $value_label = $tpl_param_array[$j] -> label;
                            $time = date("Y-m-d H:i:s",$resJson -> data[$k] -> time);
                            $value = str_replace(',','',$resJson -> data[$k] -> value);
                            $sql = $sql . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $node_code . " and F_ValueLabel = " . $value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $node_code . ",'" . $value_label . "','" . $time . "'," . $value . ",0,0)";
                            $msg = $msg . '系统服务更新设备“' . $node_name . '”参数“' . $value_label . '”的[' . $time . ']小时数据值' . $value . '到数据库成功！' . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $node_code . " and F_ValueLabel = " . $value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $node_code . ",'" . $value_label . "','" . $time . "'," . $value . ",0,0)<br>";
                            if (array_key_exists($node_code_label,$formula_map_cache) && $formula_map_cache[$node_code_label] -> storage == 1){
                                $m_node_code = $formula_map_cache[$node_code_label] -> ncode;
                                $m_value_label = $formula_map_cache[$node_code_label] -> vlabel;
                                if($formula_map_cache[$node_code_label] -> map_type == 'tpl'){
                                    $m_value = number_format($formula_map_cache[$node_code_label] -> pr * str_replace($tpl_param_array[$j] -> label,$value,$formula_map_cache[$node_code_label] -> formula),$formula_map_cache[$node_code_label] -> dp, ".", "");
                                }else if($formula_map_cache[$node_code_label] -> map_type == 'node'){
                                    $m_value = number_format($formula_map_cache[$node_code_label] -> pr * str_replace($formula_map_cache[$node_code_label] -> map_ncode . '.' . $tpl_param_array[$j] -> label,$value,$formula_map_cache[$node_code_label] -> formula),$formula_map_cache[$node_code_label] -> dp, ".", "");
                                }
                                $sql = $sql . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $m_node_code . " and F_ValueLabel = " . $m_value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $m_node_code . ",'" . $m_value_label . "','" . $time . "'," . $m_value . ",0,0)";
                                $msg = $msg . '系统服务更新设备“' . $formula_map_cache[$node_code_label] -> nname . '”参数“' . $m_value_label . '”的[' . $time . ']小时数据值' . $m_value . '到数据库成功！'  . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $m_node_code . " and F_ValueLabel = " . $m_value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $m_node_code . ",'" . $m_value_label . "','" . $time . "'," . $m_value . ',0,0)<br>';
                            }
                        }
                        if($sql != '' && $db -> execute($sql)){
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：<br>' . $msg;
                        }
                        $totalCount = $resJson -> totalCount;
                        $tempCount = count($resJson -> data);
                        while($totalCount - $tempCount > 0){
                            $params['start'] = $tempCount;
                            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量统计数据接口，传递参数：' . json_encode($params) . '<br>';
                            curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
                            $result2 = curl_exec($curl);
                            $resJson2 = json_decode($result2);
                            if($result2 === false){
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量统计数据接口失败！错误信息：' . curl_error($curl) . '<br>';
                                continue;
                            }
                            if(is_null($resJson2)){
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量统计数据接口解析失败！返回结果：' . $result2 . '<br>';
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
                                $value_code = $tpl_param_array[$j] -> code;
                                $value_label = $tpl_param_array[$j] -> label;
                                $time = date("Y-m-d H:i:s",$resJson2 -> data[$k] -> time);
                                $value = str_replace(',','',$resJson2 -> data[$k] -> value);
                                $sql = $sql . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $node_code . " and F_ValueLabel = " . $value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $node_code . ",'" . $value_label . "','" . $time . "'," . $value . ",0,0)";
                                $msg = $msg . '系统服务更新设备“' . $node_name . '”参数“' . $value_label . '”的[' . $time . ']小时数据值' . $value . '到数据库成功！'  . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $node_code . " and F_ValueLabel = " . $value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $node_code . "," . $value_code . ",'" . $value_label . "','" . $time . "'," . $value . ",0,0)<br>";
                                if (array_key_exists($node_code_label,$formula_map_cache) && $formula_map_cache[$node_code_label] -> storage == 1){
                                    $m_node_code = $formula_map_cache[$node_code_label] -> ncode;
                                    $m_value_label = $formula_map_cache[$node_code_label] -> vlabel;
                                    if($formula_map_cache[$node_code_label] -> map_type == 'tpl'){
                                        $m_value = number_format($formula_map_cache[$node_code_label] -> pr * str_replace($tpl_param_array[$j] -> label,$value,$formula_map_cache[$node_code_label] -> formula),$formula_map_cache[$node_code_label] -> dp, ".", "");
                                    }else if($formula_map_cache[$node_code_label] -> map_type == 'node'){
                                        $m_value = number_format($formula_map_cache[$node_code_label] -> pr * str_replace($formula_map_cache[$node_code_label] -> map_ncode . '.' . $tpl_param_array[$j] -> label,$value,$formula_map_cache[$node_code_label] -> formula),$formula_map_cache[$node_code_label] -> dp, ".", "");
                                    }
                                    $sql = $sql . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $m_node_code . " and F_ValueLabel = " . $m_value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $m_node_code . ",'" . $m_value_label . "','" . $time . "'," . $m_value . ",0,0)";
                                    $msg = $msg . '系统服务更新设备“' . $formula_map_cache[$node_code_label] -> nname . '”参数“' . $m_value_label . '”的[' . $time . ']小时数据值' . $m_value . '到数据库成功！'  . " if not exists(select F_ValueLabel from tb_C_NodeHourData where F_NodeCode = " . $m_node_code . " and F_ValueLabel = " . $m_value_label . " and F_ReadingDate = '" .  $time . "') insert into tb_C_NodeHourData (F_NodeCode,F_ValueLabel,F_ReadingDate,F_EnergyData,F_SynNdStatus,F_SynEhStatus) values (" . $m_node_code . ",'" . $m_value_label . "','" . $time . "'," . $m_value . ",0,0)<br>";
                                }
                            }
                            if($sql != '' && $db -> execute($sql)){
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：<br>' . $msg;
                            }
                            $tempCount = $tempCount + count($resJson2 -> data);
                        };
                    }
                }
            }
        }
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务获取通讯数据发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
?>
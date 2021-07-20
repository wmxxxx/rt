<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
    
	$memcache = memcache_connect("localhost",11211);
    $run_cmd = $memcache -> get("run_cmd");
    if($run_cmd && $run_cmd == "stop") {
        $memcache -> set("run_status",0,false,0);        
        exit();
    }
    if($run_cmd && $run_cmd == "start") {
        $memcache -> flush();
        $memcache -> set("run_cmd","",false,0);
    }
    if(!$memcache -> get("start_time")){
        $memcache -> set("start_time",date("Y-m-d H:i:s",time()),false,0);
        $memcache -> set("start_timestamp",time(),false,0);
    }
    
    $memcache -> set("run_time",date("Y-m-d H:i:s",time()),false,0);
    $memcache -> set("run_timestamp",time(),false,0);
    $memcache -> set("run_status",1,false,0);
    
    $old_log = glob("log/" . date('Y-m', strtotime('last month')) . "*.log"); foreach($old_log as $k=>$v){ unlink($v); }
    $log_file = fopen("log/" . date("Y-m-d",time()) . ".log", "a");
    try
    {
        if(!$memcache -> get("app")){
            $app = array();
            $sql = "select b.F_AppCode as code,b.F_AppID as id,b.F_AppName as name,b.F_SecretKey as skey,a.F_RouterIP as ip,a.F_RouterPort as port,a.F_Interval as interval from dbo.tb_A_IoTRouter a,dbo.tb_A_IoTApp b where a.F_RouterCode = b.F_RouterCode";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                $app[$result[$i] -> code] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联节点“' . $result[$i] -> name . '”！<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联节点“' . $result[$i] -> name . "”！\r\n");
            };
		    $memcache -> set("app",$app,false,0);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联节点完成！共计' . count($result) . '个。<br>';
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联节点完成！共计' . count($result) . "个。\r\n");
        }
        if(!$memcache -> get("node")){
            $node = array();
            $app_node = array();
            $tpl_node = array();
            $node_map = array();
            $sql = "select a.F_NodeCode as code,a.F_NodeNo as no ,a.F_NodeID as id,a.F_NodeName as name,a.F_IsStorage as storage,a.F_AppCode as app,a.F_TemplateCode as tpl, dbo.fun_GetNodeEnergyType(a.F_NodeCode) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_NodeCode) as device_type_id,c.F_GroupName AS device_type_name from dbo.tb_A_IoTNode a left outer join dbo.tb_B_EntityTreeModel b ON dbo.fun_GetNodeEnergyType(a.F_NodeCode) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c ON dbo.fun_GetNodeDeviceType(a.F_NodeCode) = c.F_GroupID";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                if(array_key_exists($result[$i] -> app,$app_node)){
                    array_push($app_node[$result[$i] -> app],$result[$i]);
                }else{
                    $app_node[$result[$i] -> app] = array($result[$i]);
                }
                if(array_key_exists($result[$i] -> tpl,$tpl_node)){
                    array_push($tpl_node[$result[$i] -> tpl],$result[$i]);
                }else{
                    $tpl_node[$result[$i] -> tpl] = array($result[$i]);
                }
                $result[$i] -> updatetime = null;
                $result[$i] -> commtime = null;
                $result[$i] -> commtime_stamp = null;
                $result[$i] -> online = null;
                $node[$result[$i] -> code] = $result[$i];
                $node_map[$result[$i] -> app . '-' . $result[$i] -> no] = $result[$i] -> code;
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点“' . $result[$i] -> name . '”！<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点“' . $result[$i] -> name . "”！\r\n");
            };
		    $memcache -> set("node",$node,false,0);
		    $memcache -> set("app_node",$app_node,false,0);
		    $memcache -> set("tpl_node",$tpl_node,false,0);
		    $memcache -> set("node_map",$node_map,false,0);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点完成！共计' . count($result) . '个。<br>';
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点完成！共计' . count($result) . "个。\r\n");
        }
        if(!$memcache -> get("tpl_param")){
            $tpl_param = array();
            $param_map = array();
            $sql = "select a.F_TemplateCode as tpl,b.F_TemplateName as tpl_name,F_ValueCode as code,F_ValueName as name,F_ValueLabel as label,F_ValueType as vtype,F_DataType as dtype,F_PrecisionRatio as pr,F_DecimalPoint as dp,F_Unit as unit,F_CommCycle as ccycle,F_KV as kv,F_IsStorage as storage,F_StorageCycle as scycle,F_RangeLower as rl,F_RangeUpper as ru,F_SlopeLower as sl,F_SlopeUpper as su,F_Benchmark as bv,F_Fluctuated as fv,a.F_IsRefer as refer,a.F_ValueProperty as vp,a.F_IsDisplay as display,a.F_OrderNum as order from dbo.tb_A_Value a,dbo.tb_A_Template b where a.F_TemplateCode = b.F_TemplateCode";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                if (array_key_exists($result[$i] -> tpl,$tpl_param)){
                    array_push($tpl_param[$result[$i] -> tpl],$result[$i]);
                }else{
                    $tpl_param[$result[$i] -> tpl] = array($result[$i]);
                }
                $param_map[$result[$i] -> tpl . '-' . $result[$i] -> label] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板“' . $result[$i] -> tpl_name . '”的通讯变量“' . $result[$i] -> name . '”！<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板“' . $result[$i] -> tpl_name . '”的通讯变量“' . $result[$i] -> name . "”！\r\n");
            };
		    $memcache -> set("param_map",$param_map,false,0);
		    $memcache -> set("tpl_param",$tpl_param,false,0);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板变量完成！共计' . count($result) . '个。<br>';
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板变量完成！共计' . count($result) . "个。\r\n");
        }
        if(!$memcache -> get("formula_map")){
            $formula_map = array();
            $sql = "select a.F_NodeCode as ncode,a.F_NodeName as nname,a.F_ValueCode as vcode,a.F_ValueLabel as vlabel,a.F_ValueName as vname,a.F_Formula as formula,a.F_KV as kv,a.F_IsStorage as storage,a.F_StorageCycle as scycle,a.F_NodeCode as  map_ncode,b.F_ValueCode as map_vcode,b.F_ValueLabel as map_vlabel,'tpl' as map_type from (select a.F_NodeCode,a.F_NodeName,b.F_ValueCode,b.F_ValueName,b.F_ValueLabel,b.F_Formula,b.F_KV,b.F_IsStorage,b.F_StorageCycle from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where b.F_Formula <> '' and b.F_IsRefer = 0 and a.F_TemplateCode = b.F_TemplateCode) a,(select a.F_NodeCode,b.F_ValueCode,b.F_ValueLabel from dbo.tb_A_IoTNode a,dbo.tb_A_Value b where a.F_TemplateCode = b.F_TemplateCode) b where a.F_NodeCode = b.F_NodeCode and b.F_ValueLabel like '%' + a.F_Formula + '%' union select a.F_VirtualNCode as ncode,b.F_EntityName as nname,a.F_VirtualVCode as vcode,a.F_VirtualVLabel as vlabel,c.F_ValueName as vname,a.F_Formula as formula,c.F_KV as kv,c.F_IsStorage as storage,c.F_StorageCycle as scycle,d.F_EntityID as map_ncode,e.F_ValueCode as map_vcode,e.F_ValueLabel as map_vlabel,'node' as map_type from dbo.tb_B_VirtualCompute a,dbo.tb_B_EntityTreeModel b,dbo.tb_A_Value c,dbo.tb_B_EntityTreeModel d,dbo.tb_A_Value e where a.F_MType = '1' and a.F_VirtualNCode = b.F_EntityID and a.F_VirtualVCode = c.F_ValueCode and d.F_EntityID = substring(a.F_NAndV,1,CHARINDEX('.',a.F_NAndV) - 1) and d.F_NodeTemplate = e.F_TemplateCode and e.F_ValueLabel = substring(a.F_NAndV,CHARINDEX('.',a.F_NAndV) + 1,len(a.F_NAndV))";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                $formula_map[$result[$i] -> ncode . '-' . $result[$i] -> map_vlabel] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备“' . $result[$i] -> nname . '”的映射变量“' . $result[$i] -> vname . '”！<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备“' . $result[$i] -> nname . '”的映射变量“' . $result[$i] -> vname . "”！\r\n");
            };
		    $memcache -> set("formula_map",$formula_map,false,0);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备的映射变量完成！共计' . count($result) . '个。<br>';
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备的映射变量完成！共计' . count($result) . "个。\r\n");
        }
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . '。<br>';
        fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . "。\r\n");
    }

    try
    {
        $app_cache = $memcache -> get("app");
        $app_node_cache = $memcache -> get("app_node");
        $tpl_param_cache = $memcache -> get("tpl_param");
        $node_cache = $memcache -> get("node");
        $node_map_cache = $memcache -> get("node_map");
        $formula_map_cache = $memcache -> get("formula_map");
        $param_map_cache = $memcache -> get("param_map");
        $node_param_cache = $memcache -> get("node_param");
        if(!$node_param_cache) $node_param_cache = array();

        foreach($app_cache as $app_code => $app_obj){
            $params = array();
            $app_node_array = $app_node_cache[$app_code];
            for($i = 0;$i < count($app_node_array);$i++){
                $tpl_param_array = $tpl_param_cache[$app_node_array[$i] -> tpl];
                $variants = array();
                for($j = 0;$j < count($tpl_param_array);$j++){
                    if($tpl_param_array[$j] -> refer == 1 && $tpl_param_array[$j] -> vp == "1"){
                        if (array_key_exists($app_node_array[$i] -> code . '-' . $tpl_param_array[$j] -> label,$node_param_cache)){
                            $node_param = $node_param_cache[$app_node_array[$i] -> code . '-' . $tpl_param_array[$j] -> label];
                            if($tpl_param_array[$j] -> ccycle != null && $tpl_param_array[$j] -> ccycle > 0){
                                if(time() - $node_param -> commtime_stamp > $tpl_param_array[$j] -> ccycle){
                                    array_push($variants,$tpl_param_array[$j] -> label);
                                }
                            }else{
                                if(time() - $node_param -> commtime_stamp > $app_obj -> interval){
                                    array_push($variants,$tpl_param_array[$j] -> label);
                                }
                            }
                        }else{
                            array_push($variants,$tpl_param_array[$j] -> label);
                        }
                    }
                }
                if(count($variants) > 0){
                    $data = new stdClass;
	                $data -> deviceCode = $app_node_array[$i] -> no;
	                $data -> variants = $variants;
                    array_push($params,$data);
                }
            }
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量接口，传递参数：' . json_encode($params) . '<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . '”设备变量接口，传递参数：' . json_encode($params) . "。\r\n");
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务开始调用应用“' . $app_obj -> name . "”设备变量接口。\r\n");
            
            $utc = time();
            $temp = array((string)$utc, $app_obj -> skey, $app_obj -> id);
            sort($temp);
            $sign = array('time' => $utc,'sign' => md5($temp[0] . $temp[1] . $temp[2]));
            $url = $app_obj -> ip . ':' . $app_obj -> port . "/app/" . $app_obj -> id . "/api/protected/getDeviceVariantData?";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url . http_build_query($sign));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl, CURLOPT_POST,1 );
            curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($params));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            $result = curl_exec($curl);
            if($result === false){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口失败！错误信息：' . curl_error($curl) . '<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口失败！错误信息：' . curl_error($curl) . "。\r\n");
                continue;
            }
            $resJson = json_decode($result);
            if(is_null($resJson)){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口解析失败！返回结果：' . $result . '<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口解析失败！返回结果：' . $result . "。\r\n");
                continue;
            }
            if($resJson -> errCode != 0){
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口错误！错误码：' . $resJson -> errCode . '，错误消息：' . $resJson -> errMsg . '。<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口错误！错误码：' . $resJson -> errCode . '，错误消息：' . $resJson -> errMsg . "。\r\n");
                continue;
            }
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口成功！返回结果：' . json_encode($resJson) . '<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . '”设备变量接口成功！返回结果：' . json_encode($resJson) . "。\r\n");
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务调用应用“' . $app_obj -> name . "”设备变量接口成功！\r\n");

            for($i = 0;$i < count($resJson -> data);$i++){
                if (array_key_exists($app_obj -> code . "-" . $resJson -> data[$i] -> deviceCode,$node_map_cache)){
                    $node_code = $node_map_cache[$app_obj -> code . "-" . $resJson -> data[$i] -> deviceCode];
                    $node_cache[$node_code] -> online = $resJson -> data[$i] -> online;
		                    
                    for($j = 0;$j < count($resJson -> data[$i] -> variantDatas);$j++){
                        $node_code_label = $node_code . "-" . $resJson -> data[$i] -> variantDatas[$j] -> code;
                        $param_obj = $param_map_cache[$node_cache[$node_code] -> tpl . "-" . $resJson -> data[$i] -> variantDatas[$j] -> code];
                                
                        if (array_key_exists($node_code_label,$node_param_cache)){
                            $valid = true;
                            $error_msg = '';
                            $value = number_format($param_obj -> pr * $resJson -> data[$i] -> variantDatas[$j] -> value,$param_obj -> dp);
                            if($param_obj -> vtype == '1' && abs($value) < abs($node_param_cache[$node_code_label] -> value)){
                                $valid = false;
                                $error_msg = '数据变小！';
                            }else if($param_obj -> rl != null && ($value < $param_obj -> rl || $value > $param_obj -> ru)){
                                $valid = false;
                                $error_msg = '数据不在量程范围！';
                            }else if($param_obj -> sl != null && $param_obj -> su != null
                                && $resJson -> data[$i] -> variantDatas[$j] -> time > $node_param_cache[$node_code_label] -> commtime_stamp){
                                $slope = ($value - $node_param_cache[$node_code_label] -> value) / ($resJson -> data[$i] -> variantDatas[$j] -> time - $node_param_cache[$node_code_label] -> commtime_stamp);
                                if($slope < $param_obj -> sl || $slope > $param_obj -> su){
                                    $valid = false;
                                    $error_msg = '数据变化超出变率范围！';
                                }
                            }else if($param_obj -> bv != null && ($value < ($param_obj -> bv - $param_obj -> fv) || $value > ($param_obj -> bv + $param_obj -> fv))){
                                $valid = false;
                                $error_msg = '数据不在基准浮动范围！';
                            }
                            if($valid){
                                $node_param_cache[$node_code_label] -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                $node_param_cache[$node_code_label] -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                $node_param_cache[$node_code_label] -> updatetime = date("Y-m-d H:i:s",time());
                                $node_param_cache[$node_code_label] -> value = $value;
                                $node_param_cache[$node_code_label] -> error_time = '';
                                $node_param_cache[$node_code_label] -> error_msg = '';
                                $node_param_cache[$node_code_label] -> error_value = '';
                                if($param_obj -> storage == 1){
                                    if(!property_exists($node_param_cache[$node_code_label],'storagetime_stamp')
                                        || $node_param_cache[$node_code_label] -> commtime_stamp - $node_param_cache[$node_code_label] -> storagetime_stamp > $param_obj -> scycle){
                                        $sql = "insert into tb_C_DataBuffer values (" . $node_code . "," . $param_obj -> code . ",'" . $param_obj -> label . "','" . $node_param_cache[$node_code_label] -> commtime . "'," . str_replace(',','',$value) . ")";
                                        if($db -> execute($sql)){
                                            $node_param_cache[$node_code_label] -> storagetime = $node_param_cache[$node_code_label] -> commtime;
                                            $node_param_cache[$node_code_label] -> storagetime_stamp = $node_param_cache[$node_code_label] -> commtime_stamp;
                                                
                                            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '到数据库成功！<br>';
                                            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "到数据库成功！\r\n");
                                        }
                                    }
                                }
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '到内存成功！<br>';
                                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "到内存成功！\r\n");
                                        
                                if (array_key_exists($node_code_label,$formula_map_cache)){
                                    $formula_key = $formula_map_cache[$node_code_label] -> ncode . '-' . $formula_map_cache[$node_code_label] -> vlabel;
                                    $node_param_cache[$formula_key] -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                    $node_param_cache[$formula_key] -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                    $node_param_cache[$formula_key] -> updatetime = date("Y-m-d H:i:s",time());
                                    $node_param_cache[$formula_key] -> value = str_replace($param_obj -> label,$value,$formula_map_cache[$node_code_label] -> formula);
                                    $node_param_cache[$formula_key] -> value_kv = $formula_map_cache[$node_code_label] -> kv;
                                    
                                    if($formula_map_cache[$node_code_label] -> storage == 1){
                                        if(!property_exists($node_param_cache[$formula_key],'storagetime_stamp')
                                            || $node_param_cache[$formula_key] -> commtime_stamp - $node_param_cache[$formula_key] -> storagetime_stamp > $formula_map_cache[$node_code_label] -> scycle){
                                            $sql = "insert into tb_C_DataBuffer values (" . $node_code . "," . $formula_map_cache[$node_code_label] -> vcode . ",'" . $formula_map_cache[$node_code_label] -> vlabel . "','" . $node_param_cache[$formula_key] -> commtime . "'," . str_replace(',','',$node_param_cache[$formula_key] -> value) . ")";
                                            if($db -> execute($sql)){
                                                $node_param_cache[$formula_key] -> storagetime = $node_param_cache[$formula_key] -> commtime;
                                                $node_param_cache[$formula_key] -> storagetime_stamp = $node_param_cache[$formula_key] -> commtime_stamp;
                                                
                                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . '到数据库成功！<br>';
                                                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . "到数据库成功！\r\n");
                                            }
                                        }
                                    }
                                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . '到内存成功！<br>';
                                    //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . "到内存成功！\r\n");
                                }
                                if(!property_exists($node_cache[$node_code],'commtime_stamp')
                                    || $node_cache[$node_code] -> commtime_stamp < $resJson -> data[$i] -> variantDatas[$j] -> time){
                                    $node_cache[$node_code] -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                    $node_cache[$node_code] -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                    $node_cache[$node_code] -> updatetime = date("Y-m-d H:i:s",time());
                                }
                            }else{
                                $node_param_cache[$node_code_label] -> error_time = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                $node_param_cache[$node_code_label] -> error_msg = $error_msg;
                                $node_param_cache[$node_code_label] -> error_value = $value;
                                echo '设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '验证非法，更新到内存失败！<br>';
                                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "验证非法，更新到内存失败！\r\n");
                            }
                        }else{
                            $valid = true;
                            $value = number_format($param_obj -> pr * $resJson -> data[$i] -> variantDatas[$j] -> value,$param_obj -> dp);
                            if($param_obj -> rl != null && ($value < $param_obj -> rl || $value > $param_obj -> ru)){
                                $valid = false;
                            }
                            if($param_obj -> bv != null && ($value < ($param_obj -> bv - $param_obj -> fv) || $value > ($param_obj -> bv + $param_obj -> fv))){
                                $valid = false;
                            }
                            if($valid){
                                $param = new stdClass;
	                            $param -> code = $node_code;
	                            $param -> label = $resJson -> data[$i] -> variantDatas[$j] -> code;
	                            $param -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                $param -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                $param -> updatetime = date("Y-m-d H:i:s",time());
                                $param -> value = $value;
                                $param -> value_kv = $param_obj -> kv;
                                $param -> error_time = '';
                                $param -> error_value = '';
                                $param -> error_msg = '';
                                $node_param_cache[$node_code_label] = $param;
                                        
                                if($param_obj -> storage == 1){
                                    $sql = "insert into tb_C_DataBuffer values (" . $node_code . "," . $param_obj -> code . ",'" . $param_obj -> label . "','" . $node_param_cache[$node_code_label] -> commtime . "'," . str_replace(',','',$value) . ")";
                                    if($db -> execute($sql)){
                                        $node_param_cache[$node_code_label] -> storagetime = $node_param_cache[$node_code_label] -> commtime;
                                        $node_param_cache[$node_code_label] -> storagetime_stamp = $node_param_cache[$node_code_label] -> commtime_stamp;
                                                
                                        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '到数据库成功！<br>';
                                        //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "到数据库成功！\r\n");
                                    }
                                }                                        
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '到内存成功！<br>';
                                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "到内存成功！\r\n");
                                    
                                if (array_key_exists($node_code_label,$formula_map_cache)){
                                    $formula_key = $formula_map_cache[$node_code_label] -> ncode . '-' . $formula_map_cache[$node_code_label] -> vlabel;
                                    $node_param_cache[$formula_key] -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                    $node_param_cache[$formula_key] -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                    $node_param_cache[$formula_key] -> updatetime = date("Y-m-d H:i:s",time());
                                    $node_param_cache[$formula_key] -> value = str_replace($param_obj -> label,$value,$formula_map_cache[$node_code_label] -> formula);
                                    $node_param_cache[$formula_key] -> value_kv = $formula_map_cache[$node_code_label] -> kv;
                                    
                                    if($formula_map_cache[$node_code_label] -> storage == 1){
                                        if(!property_exists($node_param_cache[$formula_key],'storagetime_stamp')
                                            || $node_param_cache[$formula_key] -> commtime_stamp - $node_param_cache[$formula_key] -> storagetime_stamp > $formula_map_cache[$node_code_label] -> scycle){
                                            $sql = "insert into tb_C_DataBuffer values (" . $node_code . "," . $formula_map_cache[$node_code_label] -> vcode . ",'" . $formula_map_cache[$node_code_label] -> vlabel . "','" . $node_param_cache[$formula_key] -> commtime . "'," . str_replace(',','',$node_param_cache[$formula_key] -> value) . ")";
                                            if($db -> execute($sql)){
                                                $node_param_cache[$formula_key] -> storagetime = $node_param_cache[$formula_key] -> commtime;
                                                $node_param_cache[$formula_key] -> storagetime_stamp = $node_param_cache[$formula_key] -> commtime_stamp;
                                                
                                                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . '到数据库成功！<br>';
                                                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . "到数据库成功！\r\n");
                                            }
                                        }
                                    }
                                    echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . '到内存成功！<br>';
                                    //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的参数“' . $formula_map_cache[$node_code_label] -> vlabel . '”值' . $node_param_cache[$formula_key] -> value . "到内存成功！\r\n");
                                }
                                if(!property_exists($node_cache[$node_code],'commtime_stamp')
                                    || $node_cache[$node_code] -> commtime_stamp < $resJson -> data[$i] -> variantDatas[$j] -> time){
                                    $node_cache[$node_code] -> commtime = date("Y-m-d H:i:s",$resJson -> data[$i] -> variantDatas[$j] -> time);
                                    $node_cache[$node_code] -> commtime_stamp = $resJson -> data[$i] -> variantDatas[$j] -> time;
                                    $node_cache[$node_code] -> updatetime = date("Y-m-d H:i:s",time());
                                }
                            }else{
                                echo '[' . date("Y-m-d H:i:s",time()) . ']：设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . '验证非法，更新到内存失败！<br>';
                                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：设备“' . $node_cache[$node_code] -> name . '”的参数“' . $param_obj -> label . '”值' . $value . "验证非法，更新到内存失败！\r\n");
                            }
                        }
                    }
                    $sql = "update tb_A_IoTNode set F_CommTime = '" . $node_cache[$node_code] -> commtime . "',F_CommStatus = " . ($node_cache[$node_code] -> online ? 1 : 0) . " where F_NodeCode = " . $node_code;
                    if($db -> execute($sql)){
                        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . '”的通讯状态到数据库成功！<br>';
                        fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务更新设备“' . $node_cache[$node_code] -> name . "”的通讯状态到数据库成功！\r\n");
                    }
                }
            }
        }
        $memcache -> set("node",$node_cache,false,0);
        $memcache -> set("node_param",$node_param_cache,false,0);
        $sql = "exec proc_A_WriteEventLog 1,null,null,'数据引擎任务后台执行完成！详情查看日志文件[" . date("Y-m-d",time()) . ".log]'";
        if($db -> execute($sql)){
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务写入本次执行日志到数据库成功！<br>';
            fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . "]：系统服务写入本次执行日志到数据库成功！\r\n");
        }
        fclose($log_file);
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务获取通讯数据发生异常！异常消息：' . $e -> getMessage() . '。<br>';
        fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务获取通讯数据发生异常！异常消息：' . $e -> getMessage() . "。\r\n");
    }
?>
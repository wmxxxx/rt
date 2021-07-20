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
    $run_cmd = $redis -> get("run_cmd");
    if($run_cmd && $run_cmd == "stop") {
        $redis -> set("run_status",0);        
        exit();
    }
    if($run_cmd && $run_cmd == "start") {
        $redis -> flush();
        $redis -> set("run_cmd","");
    }
    if(!$redis -> get("start_time")){
        $redis -> set("start_time",date("Y-m-d H:i:s",time()));
        $redis -> set("start_timestamp",time());
    }
    
    $redis -> set("run_time",date("Y-m-d H:i:s",time()));
    $redis -> set("run_timestamp",time());
    $redis -> set("run_status",1);
    
    $old_log = glob("log/" . date('Y-m', strtotime('last month')) . "*.log"); foreach($old_log as $k=>$v){ unlink($v); }
    $log_file = fopen("log/" . date("Y-m-d",time()) . ".log", "a");
    try
    {
        if(!$redis -> get("app")){
            $app = array();
            $sql = "select b.F_AppCode as code,b.F_AppID as id,b.F_AppName as name,b.F_SecretKey as skey,a.F_RouterIP as ip,a.F_RouterPort as port,a.F_Interval as interval from dbo.tb_A_IoTRouter a,dbo.tb_A_IoTApp b where a.F_RouterCode = b.F_RouterCode";
            $result = $db -> query($sql);
            for($i = 0;$i < count($result);$i++){
                $app[$result[$i] -> code] = $result[$i];
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联应用“' . $result[$i] -> name . '”！<br>';
                fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联应用“' . $result[$i] -> name . "”！\r\n");
            };
		    $redis -> set("app",json_encode($app));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联节点完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载物联应用完成！共计' . count($result) . "个。\r\n");
        }
        if(!$redis -> get("node")){
            $node = array();
            $app_node = array();
            $tpl_node = array();
            $node_map = array();
            $sql = "select a.F_EntityID as code,a.F_EntityID as no ,a.F_EntityID as id,dbo.fun_GetEntityPathName(a.F_EntityID) as name,1 as storage,0 as app,a.F_NodeTemplate as tpl, dbo.fun_GetNodeEnergyType(a.F_EntityID) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_EntityID) as device_type_id,c.F_GroupName AS device_type_name from dbo.tb_B_EntityTreeModel a left outer join dbo.tb_B_EntityTreeModel b ON dbo.fun_GetNodeEnergyType(a.F_EntityID) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c ON dbo.fun_GetNodeDeviceType(a.F_EntityID) = c.F_GroupID where a.F_ObjectGroup in ('2','3')";
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
                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点“' . $result[$i] -> name . "”！\r\n");
            };
		    $redis -> set("node",json_encode($node));
		    $redis -> set("app_node",json_encode($app_node));
		    $redis -> set("tpl_node",json_encode($tpl_node));
		    $redis -> set("node_map",json_encode($node_map));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备节点完成！共计' . count($result) . "个。\r\n");
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
                //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板“' . $result[$i] -> tpl_name . '”的通讯变量“' . $result[$i] -> name . "”！\r\n");
            };
		    $redis -> set("param_map",json_encode($param_map));
		    $redis -> set("tpl_param",json_encode($tpl_param));
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板变量完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备模板变量完成！共计' . count($result) . "个。\r\n");
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
		    $redis -> set("param_deviation",count($result) > 0 ? json_encode($param_deviation) : new stdClass);
            echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量修正偏差完成！共计' . count($result) . '个。<br>';
            //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量修正偏差完成！共计' . count($result) . "个。\r\n");
        }
        $node_cache = json_decode($redis -> get("node"));
        if($redis -> get("node_param")){
            $node_param_cache = json_decode($redis -> get("node_param"));
        }else{
            $node_param_cache = new stdClass;
        }

        $sql = "select a.F_NodeCode as code,a.F_ValueLabel as label,convert(varchar,F_ReadingDate,120) as commtime,b.F_DataType,case b.F_DataType when 'int' then cast(cast(F_DataValue as int) as varchar) when 'double' then cast(cast(F_DataValue as numeric(18,2)) as varchar) when 'bool' then cast(cast(F_DataValue as bit) as varchar) end as value,b.F_KV as value_kv from dbo.tb_C_LatelyData a,dbo.tb_A_Value b,dbo.tb_B_EntityTreeModel c where a.F_NodeCode = c.F_EntityID and c.F_NodeTemplate = b.F_TemplateCode and a.F_ValueLabel = b.F_ValueLabel";
        $result = $db -> query($sql);
        for($i = 0;$i < count($result);$i++){
            $node_code = $result[$i] -> code;
            if (!property_exists($node_param_cache,$node_code)){
                $node_param_cache -> $node_code = $node_code;
                $redis -> set($node_code,json_encode(new stdClass));
            }
            $param_data_cache = json_decode($redis -> get($node_code));
                        
            if (property_exists($node_cache,$node_code)){
                $node_label = $node_code . '-' . $result[$i] -> label;

                $node_cache -> $node_code -> online = true;
                $node_cache -> $node_code -> commtime = $result[$i] -> commtime;
                $node_cache -> $node_code -> updatetime = $result[$i] -> commtime;
                
                $param_data_cache -> $node_label -> commtime = $result[$i] -> commtime;
                $param_data_cache -> $node_label -> commtime_stamp = 0;
                $param_data_cache -> $node_label -> updatetime = $result[$i] -> commtime;
                $param_data_cache -> $node_label -> ovalue = $result[$i] -> value;
                $param_data_cache -> $node_label -> value = $result[$i] -> value;
                $param_data_cache -> $node_label -> value_kv = $result[$i] -> value_kv;
                $param_data_cache -> $node_label -> error_time = '';
                $param_data_cache -> $node_label -> error_msg = '';
                $param_data_cache -> $node_label -> error_value = '';
                $redis -> set($node_code,json_encode($param_data_cache));
            }
        };
        $redis -> set("node",json_encode($node_cache));
		$redis -> set("node_param",json_encode($node_param_cache));
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量最近数据完成！共计' . count($result) . '个。<br>';
        //fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载设备变量最近数据完成！共计' . count($result) . "个。\r\n");
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . '。<br>';
        fwrite($log_file, "[" . date("Y-m-d H:i:s",time()) . ']：系统服务加载基础信息发生异常！异常消息：' . $e -> getMessage() . "。\r\n");
    }
?>
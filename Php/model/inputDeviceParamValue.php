<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
	
	$node = $_POST["node"];
	$code = $_POST["code"];
	$label = $_POST["label"];
	$time = $_POST["time"];
	$value = $_POST["value"];
	$user = $_POST["user"];
    
    try{
	    $redis = new Redis();
        $redis -> connect('127.0.0.1', 6379);
        
        if(!$redis -> get("node")){
            $node = array();
            $app_node = array();
            $tpl_node = array();
            $node_map = array();
            $sql = "select a.F_NodeCode as code,a.F_NodeNo as no ,a.F_NodeID as id,dbo.fun_GetEntityPathName(a.F_NodeID) as name,a.F_IsStorage as storage,a.F_AppCode as app,a.F_TemplateCode as tpl, dbo.fun_GetNodeEnergyType(a.F_NodeCode) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_NodeCode) as device_type_id,c.F_GroupName AS device_type_name from dbo.tb_A_IoTNode a left outer join dbo.tb_B_EntityTreeModel b ON dbo.fun_GetNodeEnergyType(a.F_NodeCode) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c ON dbo.fun_GetNodeDeviceType(a.F_NodeCode) = c.F_GroupID where a.F_TemplateCode is not null and a.F_TemplateCode <> '' union select a.F_EntityID as code,null as no,null as id,a.F_EntityName as name,1 as storage,null as app,a.F_NodeTemplate as tpl,dbo.fun_GetNodeEnergyType(a.F_EntityID) as energy_type_id,b.F_EntityName as energy_type_name,dbo.fun_GetNodeDeviceType(a.F_EntityID) as device_type_id,c.F_GroupName as device_type_name from dbo.tb_B_EntityTreeModel a left outer join dbo.tb_B_EntityTreeModel b on dbo.fun_GetNodeEnergyType(a.F_EntityID) = b.F_EntityID left outer join dbo.tb_B_DictTreeModel c on dbo.fun_GetNodeDeviceType(a.F_EntityID) = c.F_GroupID where a.F_ObjectGroup in ('2','3') and dbo.fun_GetNodeAorVType(a.F_EntityID) = 0 and a.F_NodeTemplate is not null and a.F_NodeTemplate <> ''";
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
            };
		    $redis -> set("node",json_encode($node));
		    $redis -> set("app_node",json_encode($app_node));
		    $redis -> set("tpl_node",json_encode($tpl_node));
		    $redis -> set("node_map",json_encode($node_map));
        }
        
        if($redis -> get("node_param")){
            $node_param_cache = json_decode($redis -> get("node_param"));
        }else{
            $node_param_cache = new stdClass;
        }
        
        $node_code_label = $node . "-" . $label;
    
        if (!property_exists($node_param_cache,$node)){
            $node_param_cache -> $node = $node;
            $redis -> set($node,json_encode(new stdClass));
        }
        $param_data_cache = json_decode($redis -> get($node));
        if(property_exists($param_data_cache,$node_code_label)){
            $param_data_cache -> $node_code_label -> commtime = $time;
            $param_data_cache -> $node_code_label -> commtime_stamp = strtotime($time);
            $param_data_cache -> $node_code_label -> updatetime = date("Y-m-d H:i:s",time());
            $param_data_cache -> $node_code_label -> ovalue = $value;
            $param_data_cache -> $node_code_label -> value = $value;
            $param_data_cache -> $node_code_label -> error_time = '';
            $param_data_cache -> $node_code_label -> error_msg = '';
            $param_data_cache -> $node_code_label -> error_value = '';
        }else{
            $param = new stdClass;
	        $param -> code = $node;
	        $param -> label = $label;
	        $param -> commtime = $time;
            $param -> commtime_stamp = strtotime($time);
            $param -> updatetime = date("Y-m-d H:i:s",time());
            $param -> ovalue = $value;
            $param -> value = $value;
            $param -> error_time = '';
            $param -> error_value = '';
            $param -> error_msg = '';
            $param_data_cache -> $node_code_label = $param;
        }
        $redis -> set($node,json_encode($param_data_cache));
        $sql = "insert into tb_C_DataBuffer values (" . $node . ",'" . $label . "','" . $time . "'," . $value . ") exec proc_A_WriteEventLog 6,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $user . "对设备（" . $node . "）的变量（" . $label . "）录入值（" . $value . "）。'";
        $db -> execute($sql);
    
        $sql = "select a.F_VirtualNCode as ncode,b.F_EntityName as nname,a.F_VirtualVLabel as vlabel,c.F_ValueName as vname,a.F_Formula as formula,c.F_KV as kv,c.F_IsStorage as storage from dbo.tb_B_VirtualCompute a,dbo.tb_B_EntityTreeModel b,dbo.tb_A_Value c where a.F_MType = '1' and substring(a.F_NAndV,1,CHARINDEX('.',a.F_NAndV) - 1) = '" . $node . "' and substring(a.F_NAndV,CHARINDEX('.',a.F_NAndV) + 1,len(a.F_NAndV)) = '" . $label . "' and a.F_VirtualNCode = b.F_EntityID and b.F_NodeTemplate = c.F_TemplateCode and a.F_VirtualVLabel = c.F_ValueLabel";
	    $result = $db -> query($sql);
        foreach ($result as $vnode){
            $vnode_code_label = $vnode -> ncode . "-" . $vnode -> vlabel;
            $vnode_value = eval('return ' . str_replace($node . '.' . $label, $value, $vnode -> formula) . ';');
            if (!property_exists($node_param_cache,$vnode -> ncode)){
                $node_param_cache -> $vnode -> ncode = $vnode -> ncode;
                $redis -> set($vnode -> ncode,json_encode(new stdClass));
            }
            $param_data_cache = json_decode($redis -> get($vnode -> ncode));
            if(property_exists($param_data_cache,$vnode_code_label)){
                $param_data_cache -> $vnode_code_label -> commtime = $time;
                $param_data_cache -> $vnode_code_label -> commtime_stamp = strtotime($time);
                $param_data_cache -> $vnode_code_label -> updatetime = date("Y-m-d H:i:s",time());
                $param_data_cache -> $vnode_code_label -> ovalue = $vnode_value;
                $param_data_cache -> $vnode_code_label -> value = $vnode_value;
                $param_data_cache -> $vnode_code_label -> error_time = '';
                $param_data_cache -> $vnode_code_label -> error_msg = '';
                $param_data_cache -> $vnode_code_label -> error_value = '';
            }else{
                $param = new stdClass;
	            $param -> code = $vnode -> ncode;
	            $param -> label = $vnode -> vlabel;
	            $param -> commtime = $time;
                $param -> commtime_stamp = strtotime($time);
                $param -> updatetime = date("Y-m-d H:i:s",time());
                $param -> ovalue = $vnode_value;
                $param -> value = $vnode_value;
                $param -> value_kv = $vnode -> kv;
                $param -> error_time = '';
                $param -> error_value = '';
                $param -> error_msg = '';
                $param_data_cache -> $vnode_code_label = $param;
            }
            $redis -> set($vnode -> ncode,json_encode($param_data_cache));
            if($vnode -> storage == 1){
                $sql = "insert into tb_C_DataBuffer values (" . $vnode -> ncode . ",'" . $vnode -> vlabel . "','" . $time . "'," . $vnode_value . ")";
                $db -> execute($sql);
            }
        }
        $redis -> set("node_param",json_encode($node_param_cache));
        echo json_encode(array('status'=>true,'msg'=>'变量录入成功！'));
    }
    catch(Exception $e)
    {
        echo json_encode(array('status'=>false,'msg'=>$e -> getMessage()));
    }
?>

<?php
    header("Content:text/html;charset=utf-8");
    include_once(dirname(dirname(dirname(__FILE__)))."/lib/utils.php");
    include_once(dirname(dirname(dirname(dirname(__FILE__))))."/lib/plugin/share.php");
    // v1

    $psql = "SELECT distinct [F_ProjectNo] as pcode FROM [Things+].[dbo].[tb_Alarm_ProjectAlarmConf]";
    $psqlresult = $db -> query($psql);
    foreach($psqlresult as $pone){
        $pcode = $pone -> pcode;
        task_Alarm($pcode,'');
    }

    function task_Alarm($pCode,$fCode){

        $ippath =_IP();
        $db = new DB();
        $obj = new StdClass;
        $obj->rt = true;
        //微信消息回调地址
        $wxback = $ippath."/Plugins/plugin_wechat_msgBlock/index1.php?bookID=3";
        //log文本
        $txt = "";

        date_default_timezone_set('PRC');
        $start = new DateTime;
        $dt20 = date_format($start,"Y-m-d H:i:s");
        $dt10 = date_format($start,"Y-m-d");
        $dth8 = date_format($start,"H:i:s");

        //所有配置字典
        $configs = array();
        //所有发送用户字典
        $sendusers_wx = array();
        //所有发送用户字典
        $sendusers_email = array();
        //所有用户字典
        $users = array();
        $url = $ippath."/API/RData/GetDeviceVariantDataByTpl/";

        //上层巡检--模版
        $scxj1 = "select * from [Things+].dbo.tb_Alarm_ProjectAlarmConf where F_ProjectNo='".$pCode."' and F_ReportType='1' and F_ObjectType='1'";
        $scxjresult1 = $db -> query($scxj1);
        $obj->countXjModel = count($scxjresult1);
        //上层巡检--设备
        $scxj2 = "select * from [Things+].dbo.tb_Alarm_ProjectAlarmConf where F_ProjectNo='".$pCode."' and F_ReportType='1' and F_ObjectType='2'";
        $scxjresult2 = $db -> query($scxj2);
        $obj->countXjNode = count($scxjresult2);
        //主动上报--模版
        $zdsb1 = "select * from [Things+].dbo.tb_Alarm_ProjectAlarmConf where F_ProjectNo='".$pCode."' and F_ReportType='2' and F_ObjectType='1'";
        $zdsbresult1 = $db -> query($zdsb1);
        $obj->countSbModel = count($zdsbresult1);
        //主动上报--设备
        $zdsb2 = "select * from [Things+].dbo.tb_Alarm_ProjectAlarmConf where F_ProjectNo='".$pCode."' and F_ReportType='2' and F_ObjectType='2'";
        $zdsbresult2 = $db -> query($zdsb2);
        $obj->countSbNode = count($zdsbresult2);

        //需要监测的所有设备模版
        $tmps = "select distinct F_TypeCode from [Things+].dbo.tb_Alarm_ProjectAlarmConf where F_ProjectNo='".$pCode."'";
        $tmpsresult = $db -> query($tmps);
        $obj->countDeviceTemplate = count($tmpsresult);
        //系统用户集合F_OpenID
        $loginuser = "select * from [Things].dbo.tb_A_LoginUser";
        $loginuserresult = $db -> query($loginuser);
        $obj->countUser = count($loginuserresult);
        foreach($loginuserresult as $tmp){
            $key = $tmp -> F_UserCode;
            if (array_key_exists($key,$users)){
            }else{
                $users[$key] = $tmp;
            }
        }
        //频道订阅字典
        $channels = array();
        $channel = "select * from [Things+].dbo.tb_Alarm_ChannelBook where F_ChannelGroup like '%3%'";
        $channelresult = $db -> query($channel);
        foreach($channelresult as $tmp){
            $key = $tmp -> F_UserID;
            if (array_key_exists($key,$channels)){
            }else{
                $channels[$key] = $tmp;
            }
        }
        //已发记录字典
        $records = array();
        $oldrecord = "select e.F_ObjectCode,e.F_ValueLabel from [Things].[dbo].[tb_A_Event] e,[Things].[dbo].[tb_A_EventToUser] u where e.F_EventCode = u.F_EventCode and u.F_ACKStatus = 0 and e.F_ProjectNo = '".$pCode."' and e.F_TypeNo = 3";
        $oldrecordresult = $db -> query($oldrecord);
        foreach($oldrecordresult as $tmp){
            $o1 = $tmp -> F_ObjectCode;
            $v1 = $tmp -> F_ValueLabel;
            if (array_key_exists($o1."-".$v1,$records)){
            }else{
                $records[$o1."-".$v1] = $tmp;
            }
        }
        //设备模版字典
        $templates = array();
        $templatesql = "select * from [Things].[dbo].[tb_A_Template]";
        $templateresult = $db -> query($templatesql);
        foreach($templateresult as $tmp){
            $key = $tmp -> F_TemplateCode;
            $keyValue = $tmp -> F_DeviceTypeID;
            if (array_key_exists($key,$templates)){
            }else{
                if($keyValue) $templates[$key] = $keyValue;
            }
        }

        //配置整理
        foreach($scxjresult1 as $tmp){
            $vl = $tmp -> F_ValueLabel;
            $key = "xj-model".$tmp -> F_TypeCode.$vl;
            if (array_key_exists($key,$configs)){
                $tmparr = $configs[$key];
                array_push($tmparr,$tmp);
            }else{
                $arr = array();
                array_push($arr,$tmp);
                $configs[$key] = $arr;
            }
        }
        foreach($scxjresult2 as $tmp){
            $vl = $tmp -> F_ValueLabel;
            $vls = explode('+',$vl);
            $key = "xj-node".$tmp -> F_ObjectCode.$vls[0];
            if (array_key_exists($key,$configs)){
                $tmparr = $configs[$key];
                array_push($tmparr,$tmp);
            }else{
                $arr = array();
                array_push($arr,$tmp);
                $configs[$key] = $arr;
            }
        }
        foreach($zdsbresult1 as $tmp){
            $vl = $tmp -> F_ValueLabel;
            $key = "sb-model".$tmp -> F_TypeCode.$vl;
            if (array_key_exists($key,$configs)){
                $tmparr = $configs[$key];
                array_push($tmparr,$tmp);
            }else{
                $arr = array();
                array_push($arr,$tmp);
                $configs[$key] = $arr;
            }
        }
        foreach($zdsbresult2 as $tmp){
            $vl = $tmp -> F_ValueLabel;
            $vls = explode('+',$vl);
            $key = "sb-node".$tmp -> F_ObjectCode.$vls[0];
            if (array_key_exists($key,$configs)){
                $tmparr = $configs[$key];
                array_push($tmparr,$tmp);
            }else{
                $arr = array();
                array_push($arr,$tmp);
                $configs[$key] = $arr;
            }
        }

        //循环所有设备模版取数据
        foreach($tmpsresult as $tmp){
            $key = $tmp -> F_TypeCode;
            $newurl = $url."?tpl_code=".$key;
            $result_data = curl($newurl,"get","");
            foreach($result_data as $json){
                $Nname = $json -> name;
                $Ncode = $json -> code;
                $Nstatus = $json -> status;
                $Nonline = $json -> online;
                $Nvs = $json -> variantDatas;
                foreach($Nvs as $vone){
                    $Nvalue = $vone -> value;
                    $Nlabel = $vone -> label;
                    $F_key1 = "xj-node".$Ncode.$Nlabel;
                    $F_key2 = "xj-model".$key.$Nlabel;
                    if (array_key_exists($F_key1,$configs)){//存在设备级配置
                        $setarr = $configs[$F_key1];
                        foreach($setarr as $cof){
                            $rt = condtion_js($cof,$Nname,$Nvalue);
                            $rtstatus = $rt -> status;
                            $rtmsg = $rt -> msg;
                            if($rtstatus){
                                if (array_key_exists($Ncode."-".$Nlabel,$records)){//存在已发未读记录-不发
                                    continue;
                                }
                                $F_level = $cof -> F_AlarmLevel;
                                $F_type = $cof -> F_AlarmType;
                                $F_deviceType = "";
                                if(array_key_exists($key,$templates)) $F_deviceType = $templates[$key];
                                $event = T_Utils::writeAlarmEvent('3',$F_type,$F_level,$F_deviceType,$Ncode,$Nlabel,$pCode,$rtmsg);
                                $F_IsWechat = $cof -> F_IsWechat;
                                $F_IsEmail = $cof -> F_IsEmail;
                                $F_ToUser = $cof -> F_ToUser;
                                $us = explode(',',$F_ToUser);
                                if($F_IsWechat == 1 && $F_IsEmail == 1){
                                    foreach($us as $u){
                                        if($u) {
                                            T_Utils::writeAlarmToUser($event,$u,11);
                                        }
                                    }
                                }else if($F_IsWechat == 1){
                                    foreach($us as $u){
                                        if($u) {
                                            T_Utils::writeAlarmToUser($event,$u,10);
                                        }
                                    }
                                }
                                if($F_IsWechat == 1)$sendusers_wx = msg_send($cof,$sendusers_wx);
                                if($F_IsEmail == 1)$sendusers_email = msg_send($cof,$sendusers_email);
                            }
                        }
                    }else if(array_key_exists($F_key2,$configs)){//存在模版级配置
                        $setarr = $configs[$F_key2];
                        foreach($setarr as $cof){
                            $rt = condtion_js($cof,$Nname,$Nvalue);
                            $rtstatus = $rt -> status;
                            $rtmsg = $rt -> msg;
                            if($rtstatus){
                                if (array_key_exists($Ncode."-".$Nlabel,$records)){//存在已发未读记录-不发
                                    continue;
                                }
                                $F_level = $cof -> F_AlarmLevel;
                                $F_type = $cof -> F_AlarmType;
                                $F_deviceType = "";
                                if(array_key_exists($key,$templates)) $F_deviceType = $templates[$key];
                                $event = T_Utils::writeAlarmEvent('3',$F_type,$F_level,$F_deviceType,$Ncode,$Nlabel,$pCode,$rtmsg);
                                $F_IsWechat = $cof -> F_IsWechat;
                                $F_IsEmail = $cof -> F_IsEmail;
                                $F_ToUser = $cof -> F_ToUser;
                                $us = explode(',',$F_ToUser);
                                if($F_IsWechat == 1 && $F_IsEmail == 1){
                                    foreach($us as $u){
                                        if($u) T_Utils::writeAlarmToUser($event,$u,'11');
                                    }
                                }else if($F_IsWechat == 1){
                                    foreach($us as $u){
                                        if($u) T_Utils::writeAlarmToUser($event,$u,'10');
                                    }
                                }
                                if($F_IsWechat == 1)$sendusers_wx = msg_send($cof,$sendusers_wx);
                                if($F_IsEmail == 1)$sendusers_email = msg_send($cof,$sendusers_email);
                            }
                        }
                    }
                }
            }
        }

        //微信消息提醒
        $wxusers_real = "";
        foreach($sendusers_wx as $uid){
            if(array_key_exists($uid,$users)){//判定用户存在
                $myuser = $users[$uid];
                $openid = $myuser -> F_OpenID;
                if($openid && array_key_exists($uid,$channels)){//判定用户订阅
                    $wxusers_real = $wxusers_real.$uid;
                    $back = T_Utils::sendTplMessage($uid,'上层巡检-阈值报警','阈值异常','自动巡检发生设备阈值异常',$dt20,'',$wxback."&user=".$uid."&openid=".$openid);
                    $obj->back = $back;
                }
            }
        }
        $obj->wxusers_real = $wxusers_real;
        //邮件消息提醒
        foreach($sendusers_email as $uid){
            if(array_key_exists($uid,$users))
            T_Utils::sendEmail($uid,'','上层巡检-阈值报警','设备发生阈值报警，请尽快查看！');
        }

        $obj->txt = $txt;
        echo json_encode($obj);
    }

    //表达式计算
    function condtion_js($cof,$Nname,$Nvalue){
        $rt = new StdClass;
        $F_Warning = false;
        $F_WaningInfo = "设备[".$Nname."]发生阈值报警[上层巡检]!";
        $F_WaningInfoSet = $cof -> F_MsgFormat;
        $F_MatchType = $cof -> F_MatchType;
        $F_EqualLimit = $cof -> F_EqualLimit;
        $F_LowLimit = $cof -> F_LowerLimit;
        $F_UpperLimit = $cof -> F_UpperLimit;
        //1:大于 2:小于 3:等于 4:不等于 5:在区间 6:不在区间
        if($F_MatchType == "1"){
            if(intval($Nvalue) > intval($F_EqualLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]大于检测值[".$F_EqualLimit."]!";
            }
        }else if($F_MatchType == "2"){
            if(intval($Nvalue) < intval($F_EqualLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]小于检测值[".$F_EqualLimit."]!";
            }
        }else if($F_MatchType == "3"){
            if(intval($Nvalue) == intval($F_EqualLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]等于检测值[".$F_EqualLimit."]!";
            }
        }else if($F_MatchType == "4"){
            if(intval($Nvalue) != intval($F_EqualLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]不等于检测值[".$F_EqualLimit."]!";
            }
        }else if($F_MatchType == "5"){
            if(intval($Nvalue) >= intval($F_LowLimit) && intval($Nvalue) <= intval($F_UpperLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]在区间检测值[".$F_LowLimit."-".$F_UpperLimit."]!";
            }
        }else if($F_MatchType == "6"){
            if(intval($Nvalue) >= intval($F_UpperLimit) && intval($Nvalue) <= intval($F_LowLimit)) {
                $F_Warning = true;
                $F_WaningInfo = $F_WaningInfo."实际值[".$Nvalue."]不在区间检测值[".$F_LowLimit."-".$F_UpperLimit."]!";
            }
        }
        if($F_WaningInfoSet){
            $F_WaningInfo = str_replace("{deviceName}",$Nname,$F_WaningInfoSet);
        }
        $rt->msg = $F_WaningInfo;
        $rt->status = $F_Warning;
        //返回监测结果
        return $rt;
    }

    //消息推送用户处理
    function msg_send($cof,$sendusers){
        $F_ToUser = $cof -> F_ToUser;
        $us = explode(',',$F_ToUser);
        foreach($us as $u){
            if (array_key_exists($u,$sendusers)){//存在发送用户
            }else{
                if($u)
                $sendusers[$u] = $u;
            }
        }
        return $sendusers;
    }
?>
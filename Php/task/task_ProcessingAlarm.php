<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
	include_once("../lib/utils.php");
    date_default_timezone_set("PRC");
    try
    {
        $res_gconf = $db -> query("select F_TypeNo,F_OfflineTime,F_DiskSpace,F_ToUser,F_IsWechat,F_IsEmail from tb_D_GlobalAlarmConf");
        for($i = 0;$i < count($res_gconf);$i++){
            if($res_gconf[$i] -> F_TypeNo == 1 && $res_gconf[$i] -> F_OfflineTime >= 0){
                $total = 0;$wechat = 0;$email = 0;
                $res_offline = $db -> query("select F_NodeCode,F_NodeNo,F_NodeName,convert(varchar,F_CommTime,120) as F_CommTime from tb_A_IoTNode where F_CommStatus = 0 and datediff(mi,F_CommTime,getdate()) > " . $res_gconf[$i] -> F_OfflineTime);
                if($res_gconf[$i] -> F_ToUser != null && $res_gconf[$i] -> F_ToUser != ''){
                    $users = explode(",",$res_gconf[$i] -> F_ToUser);
                    foreach($users as $key => $user){
                        $user_code = explode("&",$user);
                        for($j = 0;$j < count($res_offline);$j++){
                            $msg = "设备“" . $res_offline[$j] -> F_NodeName . "”（通讯编号：" . $res_offline[$j] -> F_NodeNo . "）离线时间超过" . $res_gconf[$i] -> F_OfflineTime . "分钟。最近通讯时间：" . $res_offline[$j] -> F_CommTime;
                            if($db -> execute("exec proc_A_WriteEventAlarm 1,null," . $res_offline[$j] -> F_NodeCode . ",null,null,null,'" . $msg . "','" . $user_code[0] . "'")){
                                $total++;
                            }
                        }
                    }
                }else{
                    for($j = 0;$j < count($res_offline);$j++){
                        $msg = "设备“" . $res_offline[$j] -> F_NodeName . "”（通讯编号：" . $res_offline[$j] -> F_NodeNo . "）离线时间超过" . $res_gconf[$i] -> F_OfflineTime . "分钟。最近通讯时间：" . $res_offline[$j] -> F_CommTime;
                        if($db -> execute("exec proc_A_WriteEventAlarm 1,null," . $res_offline[$j] -> F_NodeCode . ",null,null,null,'" . $msg . "',null")){
                            $total++;
                        }
                    }
                }
                echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务处理离线报警，生成报警记录' . $total . '条！<br>';
            }
        };
		
    }
    catch(Exception $e)
    {
        echo '[' . date("Y-m-d H:i:s",time()) . ']：系统服务处理报警发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
?>
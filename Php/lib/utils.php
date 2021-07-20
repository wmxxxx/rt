<?php
    /*
     * Created on 2018-11-30
     *
     * To change the template for this generated file go to
     * Window - Preferences - PHPeclipse - PHP - Code Templates
     */
 
	include_once("base.php");

    class T_Utils {
        public static function getWechatAccessToken()
        {
            $redis = new Redis();
            $redis -> connect('127.0.0.1', 6379);
            if(!$redis -> get("access_token")){
                $config_str = file_get_contents(dirname(dirname(dirname(__FILE__))) . '\data.json');;
                if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	                $config_str = substr($config_str,3);
                }
                $config = json_decode($config_str, true);
                $app_id = $config['wechat']['appID'];
                $app_secret = $config['wechat']['appSecret'];
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $app_id . "&secret=" . $app_secret;
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $url); 
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type:application/json;","Accept:application/json"));
		        $result = curl_exec($ch);
		        curl_close($ch);
                $redis -> set("access_token",$result);
            }
            $access_token = json_decode($redis -> get("access_token"));
            return $access_token;
        }
        public static function getWechatApiToken()
        {
            $redis = new Redis();
            $redis -> connect('127.0.0.1', 6379);
            if(!$redis -> get("access_token")){
                $config_str = file_get_contents(dirname(dirname(dirname(__FILE__))) . '\data.json');;
                if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	                $config_str = substr($config_str,3);
                }
                $config = json_decode($config_str, true);
                $app_id = $config['wechat']['appID'];
                $app_secret = $config['wechat']['appSecret'];
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $app_id . "&secret=" . $app_secret;
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $url); 
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type:application/json;","Accept:application/json"));
		        $result = curl_exec($ch);
		        curl_close($ch);
                $redis -> set("access_token",$result);
            }
            $access_token = json_decode($redis -> get("access_token"));
            if(!$redis -> get("api_ticket")){
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token -> access_token . "&type=jsapi";
                $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $url); 
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type:application/json;","Accept:application/json"));
		        $result = curl_exec($ch);
		        curl_close($ch);
                $redis -> set("api_ticket",$result);
            };
            $api_ticket = json_decode($redis -> get("api_ticket"));
            return $api_ticket;
        }
        
        /*
         * 参数
         * $user：用户编号，$first：消息头，$keyword1：事件类型，$keyword2：事件内容，$keyword3：事件时间，$remark：备注信息，$url：详细链接
         * 
         * 返回 -1：没有绑定微信，0：发送成功，>0：发送失败
         */
        public static function sendTplMessage($user,$first,$keyword1,$keyword2,$keyword3,$remark,$url)
        {
            $db = new DB();
            $sql = "select isnull(F_OpenID,'') as F_OpenID from tb_A_LoginUser where F_UserCode = " . $user;
	        $result = $db -> query($sql);
            $openid = $result[0] -> F_OpenID;
	        if($openid == ''){
                return -1;
            }
            $wx_token = json_decode(T_Utils::getWechatApiToken(), true);
            $param = new stdClass;
	        $param -> touser = $openid;
	        $param -> template_id = 'C89cy6wgmAzRFpDC3pZIegywIkPurenJMCIX1MvRggw';
	        $param -> url = $url;
	        $param -> data = new stdClass;
	        $param -> data -> first = new stdClass;
	        $param -> data -> first -> value = $first;
	        $param -> data -> first -> color = "#173177";
	        $param -> data -> keyword1 = new stdClass;
	        $param -> data -> keyword1 -> value = $keyword1;
	        $param -> data -> keyword1 -> color = "#173177";
	        $param -> data -> keyword2 = new stdClass;
	        $param -> data -> keyword2 -> value = $keyword2;
	        $param -> data -> keyword2 -> color = "#173177";
	        $param -> data -> keyword3 = new stdClass;
	        $param -> data -> keyword3 -> value = $keyword3;
	        $param -> data -> keyword3 -> color = "#173177";
	        $param -> data -> remark = new stdClass;
	        $param -> data -> remark -> value = $remark;
	        $param -> data -> remark -> color = "#173177";
                    
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $wx_token -> access_token;
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);             
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POST,1 );
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($param));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		    $result = json_decode(curl_exec($ch), true);
		    curl_close($ch);
            
            return $result["errcode"];
        }
        /*
         * 参数
         * $recipient：收件人（多个分号隔开），$copier：抄送人，$theme：主题，$content：内容
         * 
         * 返回 1：加入发送队列成功，0：加入发送队列失败
         */
        public static function sendEmail($recipient,$copier,$theme,$content)
        {
            $db = new DB();
            $sql = "exec proc_A_EmailOperate '1',null,'" . $recipient . "','" . $copier . "','" . $theme . "','" . $content . "','','','后台任务','" . $_SERVER["REMOTE_ADDR"] . "'";
	        if($db -> execute($sql)){
                return 1;
            }else{
                return 0;
            }
        }
        /*
         * 更新报警消息确认状态
         * 
         * 参数 $alarmcode：报警编号
         * 返回 1：成功，0：失败
         */
        public static function setAlarmAckStatus($alarmcode,$status)
        {
            $db = new DB();
            $sql = "update tb_A_Event set F_ACKStatus=" . $status . " where F_EventCode=" . $alarmcode;
	        if($db -> execute($sql)){
                return 1;
            }else{
                return 0;
            }
        }
        /*
         * 写入报警事件记录
         * 
         * 参数 $type：分类编号,$item：分项编号,$rank：报警等级,$o_type：对象类型,$object：对象编号,$label：变量标签,$sys：系统编号,$msg：报警内容
         * 返回 >0：事件编号，0：失败
         */
        public static function writeAlarmEvent($type,$item,$rank,$o_type,$object,$label,$sys,$msg)
        {
            $db = new DB();
            $sql = "exec proc_A_WriteEventAlarm " . $type . ",'" . $item . "'," . $rank . "," . $o_type . ",'" . $object . "','" . $label . "','" . $sys . "','" . $msg . "'";
	        try{
                $result = $db -> multi_query($sql);
                return current($result) -> F_EventCode;
            }
            catch(Exception $e)
            {
                return 0;
            }            
        }
        /*
         * 写入报警推送记录
         * 
         * 参数 $code：事件编号,$user：用户编号,$way：推送方式(10-微信,11-微信+邮件)
         * 返回 1：成功，0：失败
         */
        public static function writeAlarmToUser($code,$user,$way)
        {
            $db = new DB();
            $sql = "exec proc_A_WriteEventToUser " . $code . "," . $user . ",'" . $way . "'";
	        
            if($db -> execute($sql)){
                return 1;
            }else{
                return 0;
            }
        }
        /*
         * 推送消息到已登录账户浏览器端
         * 
         * 参数 $to_uid：指明给谁推送，为空表示向所有在线用户推送
         * 参数 $content：推送内容
         * 返回 ok：成功
         */
        public static function pushMsgToClient($to_uid,$content)
        {
            $push_api_url = "http://127.0.0.1:2121/";
            $post_data = array(
               "type" => "publish",
               "content" => $content,
               "to" => $to_uid, 
            );
            $ch = curl_init ();
            curl_setopt($ch, CURLOPT_URL, $push_api_url );
            curl_setopt($ch, CURLOPT_POST, 1 );
            curl_setopt($ch, CURLOPT_HEADER, 0 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
            $result = curl_exec($ch);
            curl_close($ch );
            return $result;
        }
    }
?>

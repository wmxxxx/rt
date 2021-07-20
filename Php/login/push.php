<?php
	include_once("../lib/comm.php");
	include_once("../lib/utils.php");
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $s_keys = $redis -> keys('PHPREDIS_SESSION:' . "*");
    for($i = 0;$i < count($s_keys);$i++){
        $session_id = explode(":",$s_keys[$i]);
        $user_obj = $redis -> get($s_keys[$i]);
        if($user_obj && $user_obj != ''){
            $user_codes = explode(";",$user_obj);
            $user_code= explode(":",$user_codes[3]);
            if(session_id() != $session_id[1] && json_decode($user_code[2]) == $_SESSION['user']['code']){
                $redis -> delete($s_keys[$i]);
            }
        }
    }
    T_Utils::pushMsgToClient($_POST["code"],-1);
?>

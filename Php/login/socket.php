<?php
	include_once("../lib/comm.php");
	include_once("../lib/utils.php");

    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    $s_keys = $redis -> keys('PHPREDIS_SESSION:' . "*");var_dump($s_keys);
    for($i = 0;$i < count($s_keys);$i++){
        $user_obj = $redis -> get($s_keys[$i]);
        if($user_obj && $user_obj != ''){
            $user_codes = explode(";",$u_obj); 
            $user_code= explode(":",$u_codes[3]); 
            var_dump(json_decode($user_code[2]));
        }
    }
?>

<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2015-12-11
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	session_start();
    
    if(isset($_SESSION['user'])){
        if($_SESSION['user']['type'] != '4'){
            $redis = new Redis();
            $redis -> connect('127.0.0.1', 6379);
            $s_keys = $redis -> keys('PHPREDIS_SESSION:' . "*");
            for($i = 0;$i < count($s_keys);$i++){
                $session = explode(":",$s_keys[$i]); 
                if(session_id() == $session[1] && array_key_exists("logged",$_SESSION['user']) && $_SESSION['user']["logged"] == true){
                    $_SESSION['user']["logged"] = false;
                }
            }
            echo json_encode($_SESSION['user']);
        }else{
            echo json_encode(array("status" => 0));
        }
    }else{
        $db_status = false;
        $db_json = '';
        $user_status = false;
        $custom_status = false;
        $custom_json = '';
        $config_str = file_get_contents('../../data.json');
        if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	        $config_str = substr($config_str,3);
        }
        $config = json_decode($config_str,false);
        if($config -> db -> dbType == '' 
            || $config -> db -> dbHost == '' 
            || $config -> db -> dbName == '' 
            || $config -> db -> dbUser == ''
            || $config -> db -> dbPwd == ''){
            $db_status = true;
            $db_json = $config -> db;
        }
        if($config -> custom -> p_cname == '' 
            || $config -> custom -> p_ename == ''
            || $config -> custom -> c_cname == ''
            || $config -> custom -> c_ename == ''){
            $custom_status = true;
            $custom_json = array("p_cname" => $config -> custom -> p_cname,"p_ename" => $config -> custom -> p_ename,"c_cname" => $config -> custom -> c_cname, "c_ename" => $config -> custom -> c_ename);
        }
        echo json_encode(array("status" => -1,"db_status" => $db_status,"db_json" => $db_json, "custom_status" => $custom_status, "custom_json" => $custom_json));
    }
?>

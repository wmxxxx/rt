<?php
	/*
	 * Created on 2015-12-11
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
  
	$userID = $_POST["userID"];
	$passHash = $_POST["passHash"];
	$openID = array_key_exists('openid',$_POST) ? $_POST["openid"] : '';

	$resObj = array(
		"status" => 0,
		"code" => "",
		"id" => $userID,
		"name" => "",
		"type" => "",
		"type_name" => "",
		"email" => "",
		"mobile" => "",
		"kanban" => "",
		"project" => "",
        "ui" => "",
        "logged" => false
	);
	$sql = "exec proc_A_LoginUserAuth '" . $userID . "','" . $passHash . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $openID . "'";
	$result = $db -> query($sql);
	if(count($result) > 0){
        session_start();
		$obj = reset($result);
		$resObj["status"] = 1;
		$resObj["code"] = $obj -> F_UserCode;
		$resObj["id"] = $obj -> F_UserID;
		$resObj["name"] = urlencode($obj -> F_UserName);
		$resObj["type"] = $obj -> F_UserType;
		$resObj["type_name"] = $obj -> F_TypeName;
		$resObj["email"] = base64_encode($obj -> F_Email);
		$resObj["mobile"] = base64_encode($obj -> F_Mobile);
		$resObj["kanban"] = $obj -> F_MyKanban;
		$resObj["project"] = $obj -> F_MyProject;
		$resObj["isonly"] = $obj -> F_IsOnlyTag;
		$resObj["ui"] = $obj -> F_UI != null ? $obj -> F_UI : 'd';
        
        $redis = new Redis();
        $redis -> connect('127.0.0.1', 6379);
        $s_keys = $redis -> keys('PHPREDIS_SESSION:' . "*");
        for($i = 0;$i < count($s_keys);$i++){
            $user_obj = $redis -> get($s_keys[$i]);
            $session = explode(":",$s_keys[$i]); 
            if($user_obj && $user_obj != ''){
                $user_codes = explode(";",$user_obj); 
                $user_code= explode(":",$user_codes[3]); 
                if(json_decode($user_code[2]) == $obj -> F_UserCode && session_id() != $session[1]){
                    $resObj["logged"] = true;
                }
            }
        }
        $_SESSION['user'] = $resObj;
	}
	echo json_encode($resObj);
?>

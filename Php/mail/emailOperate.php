<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
    if(array_key_exists('mail_attachment', $_FILES)){
        $file_path = "../../Resources/attachment/";
        $file_name = $_FILES['mail_attachment']['name'];
        $temp_name = $_FILES["mail_attachment"]["tmp_name"];
	    $file_ext = array_pop(explode(".", $file_name));
	    $file_ext = trim($file_ext);
	    $file_ext = strtolower($file_ext);
        $file_name_new = date('YmdHis',time()) . "." . $file_ext;
        if(move_uploaded_file ($temp_name, $file_path . iconv("UTF-8", "GB2312", $file_name_new))) 
        {
            $recipient = $_GET["recipient"];
            $copier = $_GET["copier"];
            $theme = $_GET["theme"];
            $content = $_GET["content"];
	        $user = $_GET["user"];
            $sql = "exec proc_A_EmailOperate '1',null,'" . $recipient . "','" . $copier . "','" . $theme . "','" . $content . "','" . $file_name . "','" . $file_name_new . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }
    }else{
	    $oper = $_POST["oper"];
	    $user = $_POST["user"];
        if($oper == '1'){
            $recipient = $_POST["recipient"];
            $copier = $_POST["copier"];
            $theme = $_POST["theme"];
            $content = $_POST["content"];
            $sql = "exec proc_A_EmailOperate '" . $oper . "',null,'" . $recipient . "','" . $copier . "','" . $theme . "','" . $content . "','','','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }else if($oper == '3'){
	        $code = $_POST["code"];
            $sql = "exec proc_A_EmailOperate '" . $oper . "'," . $code . ",null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
        }
    }
	if($db -> execute($sql)){
        echo json_encode(array('status' => 1,'msg' => ''));
    }else{
        echo json_encode(array('status' => 0,'msg' => '数据保存失败！'));
    }
?>

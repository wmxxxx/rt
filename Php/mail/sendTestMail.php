<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    include_once("../lib/mailer/class.phpmailer.php");
    include_once("../lib/mailer/class.smtp.php");
    if(!array_key_exists('X-Requested-With',getallheaders())){
        echo '{"status":0,"msg":"非法的操作方式！"}';exit;
    }
	$server = $_POST["server"];
	$email = $_POST["email"];
	$name = $_POST["name"];
	$id = $_POST["id"];
	$pwd = $_POST["pwd"];
    
    $mail = new PHPMailer();
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = $server;
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;//25
    $mail->CharSet = 'UTF-8';
    $mail->FromName = $name;
    $mail->Username = $id;
    $mail->Password = $pwd;
    $mail->From = $email;
    $mail->isHTML(true);
    $mail->addAddress($email);
    $mail->Subject = '测试邮件';
    $mail->Body = '这是在测试你的帐户设置时自动发送的电子邮件。';
    if($mail->send()){
        echo json_encode(array('status' => 1,'msg' => '配置测试成功！'));
    }else{
        echo json_encode(array('status' => 0,'msg' => '配置测试失败！\\n' . $mail->ErrorInfo));
    }
?>

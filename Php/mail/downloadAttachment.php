<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    include_once("../lib/base.php");
    
    $code = $_GET["code"];
    $sql = "select F_Attachment,F_AttachCode from dbo.tb_A_EmailInfo where F_EmailCode = '" . $code . "'";
	$result = $db -> query($sql);
    
    $filename = dirname(dirname(dirname(__FILE__))) . '/Resources/attachment/' . $result[0] -> F_AttachCode;
    $out_filename = $result[0] -> F_Attachment;
    if(!file_exists($filename)){
        echo 'Not Found' . $filename;
    }else{
        header('Accept-Ranges: bytes');
        header('Accept-Length: ' . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $out_filename);
        header('Content-Type: application/octet-stream; name=' . $out_filename);
        if(is_file($filename) && is_readable($filename)){
            $file = fopen($filename, "r");
            echo fread($file, filesize($filename));
            fclose($file);
        }
    }
?>

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
	ini_set('date.timezone','Asia/Shanghai');
    
	$dtree = $_GET["dtree"];
	$gtree = $_GET["gtree"];
    $zipname = $_SERVER['DOCUMENT_ROOT'] . '/Things_model_sql[' . date('YmdHis') . '].rar';
 
    $zip = new ZipArchive();
    if($zip -> open($zipname, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
        $sql = "exec proc_A_GetModelSqlScript " . $dtree . ",'" . $gtree . "'";
        $result = $db -> query($sql);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data.sql','USE [Things]' . "\r\n",FILE_APPEND);
        foreach ($result as $obj){
            if($obj -> F_Values != null && $obj -> F_Values != ''){
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data.sql',($obj -> F_Header != "" ? $obj -> F_Header . "\r\n" : "") . "INSERT " . $obj -> F_Table . " (" . $obj -> F_Fields . ") VALUES (" . $obj -> F_Values . ")\r\n" . ($obj -> F_Footer != "" ? $obj -> F_Footer . "\r\n" : "") ,FILE_APPEND);
            }
        }
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/data.sql','data.sql');
    }
   
    $zip -> close();
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/zip;");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length: " . filesize($zipname));
    header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");
    ob_clean();
    flush();
    readfile($zipname);
    unlink($zipname);
    unlink($_SERVER['DOCUMENT_ROOT'] . '/data.sql');
    exit;
    
    function createZip($openFile,$zipObj,$sourceAbso,$newRelat = '')
    {
        while(($file = readdir($openFile)) != false)
        {
            if($file == "." || $file == "..") continue;
            $sourceTemp = $sourceAbso . '/' . $file;
            $newTemp = $newRelat == '' ? $file : $newRelat . '/' . $file;
            
            if(is_dir($sourceTemp)){
                $zipObj -> addEmptyDir($newTemp);
                createZip(opendir($sourceTemp),$zipObj,$sourceTemp,$newTemp);
            }
            if(is_file($sourceTemp)){
                $zipObj -> addFile($sourceTemp,$newTemp);
            }
        }
    }
?>

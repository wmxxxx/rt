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
    
	$sys = $_GET["sys"];
	$menu = $_GET["menu"];
	$app = $_GET["app"];

    $zipname = $_SERVER['DOCUMENT_ROOT'] . '/Things_plugins_setup[' . date('YmdHis') . '].zip';
 
    $zip = new ZipArchive();
    if($zip -> open($zipname, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
        $zip -> addEmptyDir('Plugins');
    }
    
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql')) unlink($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql');
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/config.sql')) unlink($_SERVER['DOCUMENT_ROOT'] . '/config.sql');

    $sql = "exec proc_A_GetPluginPackage '" . $sys . "','" . $menu . "','" . $app . "'";
	$result = $db -> query($sql);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql','USE [Things+]' . "\r\n",FILE_APPEND);
    foreach ($result as $obj){
        $zip -> addEmptyDir('Plugins/' . $obj -> F_PluginTag);
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Plugins/' . $obj -> F_PluginTag),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Plugins/' . $obj -> F_PluginTag,'Plugins/' . $obj -> F_PluginTag);
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/Plugins' . '/' . $obj -> F_PluginTag . '/setup.sql')){
            $plugin_sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/Plugins' . '/' . $obj -> F_PluginTag . '/setup.sql');
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql',$plugin_sql ,FILE_APPEND);
        }
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql')){
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql','plugin.sql');
    }
    
    $sql = "exec proc_A_GetPluginSqlScript '" . $sys . "','" . $menu . "','" . $app . "'";
    $result = $db -> query($sql);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/config.sql','USE [Things]' . "\r\n",FILE_APPEND);
    foreach ($result as $obj){
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/config.sql',($obj -> F_Header != "" ? $obj -> F_Header . "\r\n" : "") . "INSERT " . $obj -> F_Table . " (" . $obj -> F_Fields . ") VALUES (" . $obj -> F_Values . ")\r\n" . ($obj -> F_Footer != "" ? $obj -> F_Footer . "\r\n" : "") ,FILE_APPEND);
    }
    $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/config.sql','config.sql');
   
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
    unlink($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql');
    unlink($_SERVER['DOCUMENT_ROOT'] . '/config.sql');
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

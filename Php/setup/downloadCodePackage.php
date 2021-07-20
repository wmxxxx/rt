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
    
    $delfiles = scandir($_SERVER['DOCUMENT_ROOT']);
    $delarr = preg_grep("/(.*).rar$/",$delfiles);
    foreach($delarr as $value)
    {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $value);
    }
    
	$sys = $_GET["sys"];
	$menu = $_GET["menu"];
	$app = $_GET["app"];
	$dtree = $_GET["dtree"];
	$gtree = $_GET["gtree"];
    $zipname = $_SERVER['DOCUMENT_ROOT'] . '/Things_code_setup[' . date('YmdHis') . '].rar';
 
    $zip = new ZipArchive();
    if($zip -> open($zipname, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
        $zip -> addEmptyDir('API');
        $zip -> addEmptyDir('Config');
        $zip -> addEmptyDir('Lib');
        $zip -> addEmptyDir('Php');
        $zip -> addEmptyDir('Plugins');
        $zip -> addEmptyDir('Portal');
        $zip -> addEmptyDir('Project');
        $zip -> addEmptyDir('Resources');
        $zip -> addEmptyDir('Scripts');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/API'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/API','API');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Config'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Config','Config');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Lib'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Lib','Lib');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Php'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Php','Php');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Portal'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Portal','Portal');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Resources'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Resources','Resources');
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Scripts'),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Scripts','Scripts');
        
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/favicon.ico','favicon.ico');
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/index.php','index.php');
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/data_tpl.json','data.json');
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/setup.sql','setup.sql');
    }
    $sql = "select b.F_ProjectTag from dbo.fun_SplitByComma('" . $sys . "') a,dbo.tb_A_Project b where a.F_ObjectID = b.F_ProjectNo union select distinct b.F_ProjectTag from dbo.fun_SplitByComma('" . $menu . "') a,dbo.tb_A_Project b,dbo.tb_A_ProjectToMenu c where substring(a.F_ObjectID,1,charindex('_',a.F_ObjectID) - 1) = c.F_ProjectNo and substring(a.F_ObjectID,charindex('_',a.F_ObjectID) + 1,len(a.F_ObjectID)) = c.F_MenuCode and c.F_ProjectNo = b.F_ProjectNo";
	$result = $db -> query($sql);
    foreach ($result as $obj){
        $zip -> addEmptyDir('Project/' . $obj -> F_ProjectTag);
        createZip(opendir($_SERVER['DOCUMENT_ROOT'] . '/Project/' . $obj -> F_ProjectTag),$zip,$_SERVER['DOCUMENT_ROOT'] . '/Project/' . $obj -> F_ProjectTag,'Project/' . $obj -> F_ProjectTag);
    }
    
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql')) unlink($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql');
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/data.sql')) unlink($_SERVER['DOCUMENT_ROOT'] . '/data.sql');
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/task.sql')) unlink($_SERVER['DOCUMENT_ROOT'] . '/task.sql');

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
    
    $sql = "exec proc_A_GetDataSqlScript '" . $sys . "','" . $menu . "','" . $app . "'," . $dtree . ",'" . $gtree . "'";
    $result = $db -> query($sql);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data.sql','USE [Things]' . "\r\n",FILE_APPEND);
    foreach ($result as $obj){
        if($obj -> F_Values != null && $obj -> F_Values != ''){
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data.sql',($obj -> F_Header != "" ? $obj -> F_Header . "\r\n" : "") . "INSERT " . $obj -> F_Table . " (" . $obj -> F_Fields . ") VALUES (" . $obj -> F_Values . ")\r\n" . ($obj -> F_Footer != "" ? $obj -> F_Footer . "\r\n" : "") ,FILE_APPEND);
        }
    }
    $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/data.sql','data.sql');

    $sql = "exec proc_A_GetPlanSqlScript '" . $sys . "','" . $menu . "'";
    $result = $db -> query($sql);
    
    foreach ($result as $obj){
        if($obj -> F_Values != null && $obj -> F_Values != ''){
            if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/task.sql')){
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/task.sql','USE [Things]' . "\r\n",FILE_APPEND);
            }
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/task.sql',($obj -> F_Header != "" ? $obj -> F_Header . "\r\n" : "") . "INSERT " . $obj -> F_Table . " (" . $obj -> F_Fields . ") VALUES (" . $obj -> F_Values . ")\r\n" . ($obj -> F_Footer != "" ? $obj -> F_Footer . "\r\n" : "") ,FILE_APPEND);
        }
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/task.sql')){
        $zip -> addFile($_SERVER['DOCUMENT_ROOT'] . '/task.sql','task.sql');
    }
   
    $zip -> close();
    header('Cache-Control: must-revalidate');
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Transfer-Encoding: Binary');
    header('Accept-Ranges: bytes');
    header('Expires: 0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zipname));
    header("Content-Disposition: attachment; filename=\"" . basename($zipname) . "\"");

    $fp = fopen($zipname, 'rb');
    fseek($fp, 0);
 
    // 开启缓冲区
    ob_start();
    // 分段读取文件
    while (!feof($fp)) {
        $chunk_size = 1024 * 1024 * 2; // 2MB
        echo fread($fp, $chunk_size);
        ob_flush(); // 刷新PHP缓冲区到Web服务器
        flush(); // 刷新Web服务器缓冲区到浏览器
        sleep(1); // 每1秒 下载 2 MB
    }
    // 关闭缓冲区
    ob_end_clean();
    fclose($fp);
    unlink($zipname);
    unlink($_SERVER['DOCUMENT_ROOT'] . '/data.sql');
    unlink($_SERVER['DOCUMENT_ROOT'] . '/plugin.sql');
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/task.sql')){
        unlink($_SERVER['DOCUMENT_ROOT'] . '/task.sql');
    }
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

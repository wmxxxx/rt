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
	include_once("../lib/file.php");
	ini_set('date.timezone','Asia/Shanghai');
    
	$dict = $_GET["dict"];
    $sql = "select b.F_DictionaryName,a.F_Key,a.F_Value from dbo.tb_B_KeyValueList a,dbo.tb_B_KeyValueTable b where a.F_DictionaryID = " . $dict . " and b.F_DictionaryID = " . $dict;
	$result = $db -> query($sql);
    $csv = "字典值,字典名称\n";    
    //$csv = iconv('utf-8','gb2312',$csv); 
    $dict_name = '';
    foreach ($result as $obj){
        if($dict_name == '') $dict_name = $obj -> F_DictionaryName;
        $id = $obj -> F_Key;
        $name = $obj -> F_Value;
        $csv .= $id . "," . $name . "\n";
    }
    $filename = urlencode($dict_name . '-配置字典参照表（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

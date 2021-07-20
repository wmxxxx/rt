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
	
    $tempname = $_FILES["p_import_file"]["tmp_name"];
    $filename = $_FILES["p_import_file"]["name"];
    $file_array = explode(".", $filename);
    $file_ext = array_pop($file_array);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
    
    if($file_ext != 'csv'){
        echo json_encode(array('result' => -1,'msg' => '请选择csv格式文件！'));
        exit;
    }
    
    $handle = fopen($tempname, 'r');
    $csv = File::read_csv($handle);
    $len = count($csv);
    if($len == 0){
        echo json_encode(array('result' => 0,'msg' => '文件内容为空！'));
    }else{
        $tag = true;
        for($i = 1; $i < $len; $i++) {
            $F_TemplateLabel = $csv[$i][0];
            $F_TemplateName = $csv[$i][1];
            $F_TemplateType = $csv[$i][2];
            $F_ValueLabel = $csv[$i][3];
            $F_ValueName = $csv[$i][4];
            $F_ValueType = $csv[$i][5];
            $F_ValueProperty = $csv[$i][6];
            $F_DataType = $csv[$i][7];
            $F_PrecisionRatio = $csv[$i][8] ;
            $F_DecimalPoint = $csv[$i][9];
            $F_DefaultValue = $csv[$i][10];
            $F_Unit = $csv[$i][11];
            $F_ReadWrite = $csv[$i][12];
            $F_CommCycle = $csv[$i][13];
            $F_Formula = $csv[$i][14];
            $F_KV = $csv[$i][15];
            $F_IsStorage = $csv[$i][16];
            $F_StorageCycle = $csv[$i][17];
            $F_RangeLower = $csv[$i][18];
            $F_RangeUpper = $csv[$i][19];
            $F_SlopeLower = $csv[$i][20];
            $F_SlopeUpper = $csv[$i][21];
            $F_Benchmark = $csv[$i][22];
            $F_Fluctuated = $csv[$i][23];
            $F_MinValue = $csv[$i][24];
            $F_MaxValue = $csv[$i][25];
            $F_IsDisplay = $csv[$i][26];
            $F_OrderNum = $csv[$i][27];
            if(!$db -> execute("if exists(select F_TemplateCode from dbo.tb_A_Template where F_TemplateLabel = '$F_TemplateLabel') update dbo.tb_A_Template set F_TemplateName='$F_TemplateName',F_TemplateType='$F_TemplateType' where F_TemplateLabel = '$F_TemplateLabel' else insert into dbo.tb_A_Template (F_TemplateCode,F_TemplateName,F_TemplateLabel,F_TemplateType,F_IsRefer) values (dbo.fun_MakeSerialNum(),'$F_TemplateName','$F_TemplateLabel','$F_TemplateType',0)")){
                $tag = false;
            }
            if(!$db -> execute("exec proc_A_ImportTplValue '$F_TemplateLabel','$F_ValueName','$F_ValueLabel','$F_ValueType','$F_ValueProperty','$F_DataType','$F_PrecisionRatio','$F_DecimalPoint','$F_DefaultValue',N'$F_Unit','$F_ReadWrite','$F_CommCycle','$F_Formula','$F_KV','$F_IsStorage','$F_StorageCycle','$F_RangeLower','$F_RangeUpper','$F_SlopeLower','$F_SlopeUpper','$F_Benchmark','$F_Fluctuated','$F_MinValue','$F_MaxValue','$F_IsDisplay','$F_OrderNum'")){
                $tag = false;
            }
        }
        if($tag){
            echo json_encode(array('result' => 1,'msg' => ''));
        }else{
            echo json_encode(array('result' => 0,'msg' => '数据记录导入出错，请确认文件编码为UTF-8格式！'));
        }
        fclose($handle);
    }
?>

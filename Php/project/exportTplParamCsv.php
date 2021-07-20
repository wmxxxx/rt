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

    $csv = "模板标识,模板名称,模板类型,变量标签,变量名称,变量类型,变量性质,数据类型,倍率系数,小数位数,默认值,显示单位,读写属性,通讯周期,映射公式,键值字典,存储开关,存储周期,量程(低)值,量程(高)值,变率(低)值,变率(高)值,基准值,波动值,最小值,最大值,显示/隐藏,排序号\n";
    
    $sql = "select a.F_TemplateLabel,a.F_TemplateName,a.F_TemplateType,b.F_ValueLabel,b.F_ValueName,b.F_ValueType,b.F_ValueProperty,b.F_DataType,b.F_PrecisionRatio,b.F_DecimalPoint,b.F_DefaultValue,b.F_Unit,b.F_ReadWrite,b.F_CommCycle,b.F_Formula,b.F_KV,b.F_IsStorage,b.F_StorageCycle,b.F_RangeLower,b.F_RangeUpper,b.F_SlopeLower,b.F_SlopeUpper,b.F_Benchmark,b.F_Fluctuated,b.F_MinValue,b.F_MaxValue,b.F_IsDisplay,b.F_OrderNum from dbo.tb_A_Template a,dbo.tb_A_Value b where a.F_TemplateCode = b.F_TemplateCode order by a.F_TemplateType,b.F_OrderNum";
	$result = $db -> query($sql);
    foreach ($result as $obj){
        $csv .= $obj -> F_TemplateLabel . "," . $obj -> F_TemplateName . "," . $obj -> F_TemplateType . "," . $obj -> F_ValueLabel . "," . $obj -> F_ValueName . "," . $obj -> F_ValueType . "," . $obj -> F_ValueProperty . "," . $obj -> F_DataType . "," . $obj -> F_PrecisionRatio . "," . $obj -> F_DecimalPoint . "," . $obj -> F_DefaultValue . "," . $obj -> F_Unit . "," . $obj -> F_ReadWrite . "," . $obj -> F_CommCycle . "," . $obj -> F_Formula . "," . $obj -> F_KV . "," . $obj -> F_IsStorage . "," . $obj -> F_StorageCycle . "," . $obj -> F_RangeLower . "," . $obj -> F_RangeUpper . "," . $obj -> F_SlopeLower . "," . $obj -> F_SlopeUpper . "," . $obj -> F_Benchmark . "," . $obj -> F_Fluctuated . "," . $obj -> F_MinValue . "," . $obj -> F_MaxValue . "," . $obj -> F_IsDisplay . "," . $obj -> F_OrderNum . "\n";
    }
    $filename = urlencode('设备模板与变量信息（' . date('Y-m-d-His') . '）.csv');
    File::export_csv($filename,$csv);
?>

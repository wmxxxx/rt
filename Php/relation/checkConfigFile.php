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
	
    $tempname = $_FILES["r_import_file"]["tmp_name"];
    $filename = $_FILES["r_import_file"]["name"];
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
        $sql = '';
        $db -> execute("delete from tb_A_ImportTemp where F_FileNum = 1;insert into tb_A_ImportTemp(F_FileNum,F_FileName) values (1,'" . $filename . "');delete from tb_A_IoTNodeTemp;delete from tb_A_IoTNodeTempV;");
        for($i = 1; $i < $len; $i++) {
            $code = $csv[$i][0];
            $name = $csv[$i][1];
            $tname = $csv[$i][2];
            $etype = $csv[$i][3];
            $dtype = $csv[$i][5];
            $parent = $csv[$i][9];
            $to = $csv[$i][10];
            if(!$db -> execute("insert into tb_A_IoTNodeTemp (F_NodeCode,F_NodeName,F_NodeTemplate,F_ParentCode,F_ToEntity,F_EnergyTypeID,F_DeviceTypeID) values ('$code','$name','$tname','$parent','$to','$etype','$dtype')")){
                $tag = false;
            }
            for($k = 11; $k < count($csv[0]); $k++) {
                if($csv[0][$k] == '' || $csv[0][$k] == null) break;
                $tags = explode("/", $csv[0][$k]);
                $tag = array_pop($tags);
                $value = $csv[$i][$k];
                if(!$db -> execute("insert into tb_A_IoTNodeTempV (F_NodeCode,F_PropertyIdentifier,F_PropertyValue) values ('$code','$tag','$value')")){
                    $tag = false;
                }
            }
        }
        if($tag){
            $result = $db -> query("exec proc_A_CheckNodeCsvFile");
            echo json_encode($result[0]);
        }else{
            echo json_encode(array('result' => 0,'msg' => '数据记录导入出错，请确认文件编码为UTF-8格式！'));
        }
        fclose($handle);
    }
?>

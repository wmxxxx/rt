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
	
    $tempname = $_FILES["txt_ImportEntityToDeviceFile"]["tmp_name"];
    $filename = $_FILES["txt_ImportEntityToDeviceFile"]["name"];
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
        $db -> execute("delete from tb_B_EntityTreeToDeviceTemp");
        for($i = 1; $i < $len; $i++) {
            $F_EntityID = $csv[$i][0];
            $F_DeviceID = $csv[$i][2];
            if(!$db -> execute("insert into tb_B_EntityTreeToDeviceTemp (F_EntityID,F_DeviceID) values ('$F_EntityID','$F_DeviceID')")){
                $tag = false;
            }
        }
        if($tag){
            $result = $db -> query("exec proc_B_CheckEntityToDeviceCsvFile");
            echo json_encode($result[0]);
        }else{
            echo json_encode(array('result' => 0,'msg' => '文件内容导入失败，没有生成数据记录信息！'));
        }
        fclose($handle);
    }
?>

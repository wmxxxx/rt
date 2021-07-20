<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$operFlg = $_POST["operFlg"];
	$user = $_POST["user"];
    if($operFlg == "1"){
	    $name = $_POST["txt_PropertyName"];
        $identifier = $_POST["txt_PropertyIdentifier"];
        $unit = $_POST["txt_PropertyUnit"];
        $objectTypeID = $_POST["objectTypeID"];
        $groupTypeID = $_POST["groupTypeID"];
        $propertyTypeID = $_POST["propertyTypeID"];
        $dictionaryType = $_POST["dictionaryType"];
        $dictionaryID = $dictionaryType != '' ? $_POST["dictionaryID"] : '';
        $dictionaryKey = $dictionaryType != '' ? $_POST["dictionaryKey"] : '';
        $defaultVaue = $dictionaryType == '' ? $_POST["txt_DefaultValue"] : '';
	    $regular = $_POST["txt_RegularFormula"];
        $memo = $_POST["txt_PropertyMemo"];
        $isOnlyRead = $_POST["isOnlyRead"] == "false" ? 0 : 1;
	    $sql = "exec proc_B_ObjectPropertyOperate '" . $operFlg . "',null,'" . $name . "','" . $identifier . "','" . $unit . "','" . $objectTypeID . "','" . $groupTypeID . "','"  . $propertyTypeID . "','" . $dictionaryType . "','" . $dictionaryID . "','" . $dictionaryKey . "','" . $defaultVaue . "','" . $regular . "','" . $isOnlyRead . "','" . $memo . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
	}else if($operFlg == "2"){
	    $code = $_POST["code"];
	    $name = $_POST["txt_PropertyName"];
        $identifier = $_POST["txt_PropertyIdentifier"];
        $unit = $_POST["txt_PropertyUnit"];
        $objectTypeID = $_POST["objectTypeID"];
        $groupTypeID = $_POST["groupTypeID"];
        $propertyTypeID = $_POST["propertyTypeID"];
        $dictionaryType = $_POST["dictionaryType"];
        $dictionaryID = $dictionaryType != '' ? $_POST["dictionaryID"] : '';
        $dictionaryKey = $dictionaryType != '' ? $_POST["dictionaryKey"] : '';
        $defaultVaue = $dictionaryType == '' ? $_POST["txt_DefaultValue"] : '';
	    $regular = $_POST["txt_RegularFormula"];
        $memo = $_POST["txt_PropertyMemo"];
        $isOnlyRead = $_POST["isOnlyRead"] == "false" ? 0 : 1;
	    $sql = "exec proc_B_ObjectPropertyOperate '" . $operFlg . "','" . $code . "','" . $name . "','" . $identifier . "','" . $unit . "','" . $objectTypeID . "','" . $groupTypeID . "','"  . $propertyTypeID . "','" . $dictionaryType . "','" . $dictionaryID . "','" . $dictionaryKey . "','" . $defaultVaue . "','" . $regular . "','" . $isOnlyRead . "','" . $memo . "','" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }else if($operFlg == "3"){
	    $code = $_POST["code"];
	    $sql = "exec proc_B_ObjectPropertyOperate '" . $operFlg . "','" . $code . "',null,null,null,null,null,null,null,null,null,null,null,null,null,'" . $user . "','" . $_SERVER["REMOTE_ADDR"] . "'";
    }
    echo json_encode($db -> execute($sql));
?>

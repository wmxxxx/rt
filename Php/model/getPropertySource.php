<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $DictionaryType = $_POST["DictionaryType"];
    $DictionaryID = $_POST["DictionaryID"];
    $DictionaryKey = $_POST["DictionaryKey"];
    $sql = "";
    if($DictionaryType == "1"){
        $sql = "select F_Key,F_Value from tb_B_KeyValueList where F_DictionaryID='" . $DictionaryID . "'";
    }else{
        if($DictionaryKey == ""){
            $sql = "select F_EntityID as F_Key,F_EntityName as F_Value from tb_B_EntityTreeModel where F_TemplateID='" . $DictionaryID . "' order by F_OrderTag";
        }else{
            $sql = "select b.F_PropertyValue as F_Key,a.F_EntityName as F_Value from tb_B_EntityTreeModel a left outer join dbo.tb_B_EntityTreeProperty b on a.F_EntityID = b.F_EntityID and b.F_PropertyID = '" . $DictionaryKey . "' and F_EndYM = '9999-12-31' where a.F_TemplateID='" . $DictionaryID . "' and (b.F_PropertyValue is not null or b.F_PropertyValue <> '') order by a.F_OrderTag";
        }
    }
	$result = $db -> query($sql);
    $resArray = array();
    array_push($resArray,array('无',''));
    foreach ($result as $obj){
        array_push($resArray,array($obj -> F_Value,$obj -> F_Key));
    }
	echo json_encode($resArray);
?>

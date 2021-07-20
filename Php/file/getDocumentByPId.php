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
	
	$parent = $_POST["parent"];
	
	$sql = "select F_DocumentCode,F_DocumentName,F_DocumentType,F_FileType,F_UploadName,F_CreateUser,convert(varchar,F_CreateDate,120) as F_CreateDate,dbo.fun_GetChildDocumentNum(F_DocumentCode,'folder') as F_FolderNum,dbo.fun_GetChildDocumentNum(F_DocumentCode,'file') as F_FileNum from dbo.tb_A_DocumentInfo where F_ParentCode ='" . $parent . "' order by F_DocumentType desc,F_DocumentName";
	$result = $db -> query($sql);
    foreach ($result as $doc){
        if($doc -> F_DocumentType == 'file'){
            if(file_exists("../../Resources/file/" . $doc -> F_DocumentName . '.' . $doc -> F_FileType)){
                $doc -> F_FileSize = filesize("../../Resources/file/" . $doc -> F_DocumentName . '.' . $doc -> F_FileType);
            }
        }
    }
	echo json_encode($result);
?>

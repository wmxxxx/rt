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
	
	$fun = $_POST["fun"];
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
    $sql = "select b.F_GroupID,b.F_GroupName,c.F_ParentProperty from(select F_TemplateID,F_EntityDepth from dbo.tb_B_EntityTreeModel where F_EntityTreeNo = " . $tree . " and F_ParentID <> 0 and F_TemplateID is not null group by F_TemplateID,F_EntityDepth) a,dbo.tb_B_DictTreeModel b left outer join dbo.tb_A_PluginToCustom c on c.F_FunctionCode = " . $fun . " and c.F_PluginCode = " . $plugin . " and F_EntityTreeNo = " . $tree . " and c.F_TemplateID = b.F_GroupID where a.F_TemplateID = b.F_GroupID order by a.F_EntityDepth";
	$result = $db -> query($sql);
	echo json_encode($result);
?>

<?php
    /*
     * Created on 2018-11-30
     *
     * To change the template for this generated file go to
     * Window - Preferences - PHPeclipse - PHP - Code Templates
     */
 
	include_once("base.php");

    class T_API {
        /*
         * 用途：获取类对象
         * 
         * 参数：$group：分类标识（''-全部；1-管理类；2-表具类；3-设备类）
         * 
         * 返回：[{ F_GroupID:类对象ID,F_GroupName:类对象名称,F_ObjectGroup:(1-管理类；2-表具类；3-设备类)}]
         */
        public static function getObjectList($group)
        {
            $db = new DB();
            $sql = "select F_GroupID,F_GroupName,F_ObjectGroup from dbo.tb_B_DictTreeModel where F_ObjectTypeID is not null and F_ObjectGroup like '%" . $group . "%' order by F_ObjectGroup,F_GroupID";
	        $result = $db -> query($sql);
            return $result;
        }
        
        /*
         * 用途：获取类对象画像分组
         * 
         * 参数：$group_id：类对象ID
         * 
         * 返回：[{ F_PortraitID:画像分组ID,F_PortraitName:画像分组名称 }]
         */
        public static function getObjectPortraitList($group)
        {
            $db = new DB();
            $sql = "select F_PortraitID,F_PortraitName from dbo.tb_B_DictPortrait where F_GroupID =" . $group_id;
	        $result = $db -> query($sql);
            return $result;
        }
        
        /*
         * 用途：获取类对象分组属性
         * 
         * 参数：$group_id：类对象ID, $portrait_id（''-全部）
         * 
         * 返回：[{ F_PropertyID:属性ID,F_PropertyName:属性名称,F_PropertyIdentifier:属性标识,F_PortraitID:画像分组ID,F_PortraitName:画像分组名称 }]
         */
        public static function getObjectPropertyList($group_id,$portrait_id)
        {
            $db = new DB();
            $sql = "select b.F_PropertyID,b.F_PropertyName,b.F_PropertyIdentifier,c.F_PortraitID,c.F_PortraitName from dbo.tb_B_DictTreeProperty a,dbo.tb_B_ObjectProperty b,dbo.tb_B_DictPortrait c where a.F_GroupID = " . $group_id . ($portrait_id == "" ? "" : " and a.F_PortraitID = " . $portrait_id) . " and a.F_PropertyID = b.F_PropertyID and a.F_GroupID = c.F_GroupID and a.F_PortraitID = c.F_PortraitID";
	        $result = $db -> query($sql);
            return $result;
        }
        
        /*
         * 用途：新增类对象画像分组
         * 
         * 参数：$group_id：类对象ID,$portrait_name：画像分组名称
         * 
         * 返回：true-成功，false-失败
         */
        public static function addObjectPortrait($group_id,$portrait_name)
        {
            $db = new DB();
            $sql = "insert into dbo.tb_B_DictPortrait values (" . $group_id . ",dbo.fun_MakeSerialNum(),'" . $portrait_name . "')";
	        $result = $db -> execute($sql);
            return $result;
        }
        
        /*
         * 用途：修改类对象画像分组
         * 
         * 参数：$group_id：类对象ID,$portrait_id：画像分组ID,$portrait_name：画像分组名称
         * 
         * 返回：true-成功，false-失败
         */
        public static function updateObjectPortrait($group_id,$portrait_id,$portrait_name)
        {
            $db = new DB();
            $sql = "update dbo.tb_B_DictPortrait set F_PortraitName = '" . $portrait_name . "' where F_GroupID = " . $group_id . " and F_PortraitID = " . $portrait_id;
	        $result = $db -> execute($sql);
            return $result;
        }
        
        /*
         * 用途：删除类对象画像分组
         * 
         * 参数：$group_id：类对象ID,$portrait_id：画像分组ID
         * 
         * 返回：true-成功，false-失败
         */
        public static function delObjectPortrait($group_id,$portrait_id)
        {
            $db = new DB();
            $sql = "delete from dbo.tb_B_DictPortrait where F_GroupID = " . $group_id . " and F_PortraitID = " . $portrait_id . " update tb_B_DictTreeProperty set F_PortraitID = null where F_GroupID = " . $group_id . " and F_PortraitID = " . $portrait_id;
	        $result = $db -> execute($sql);
            return $result;
        }
        
        /*
         * 用途：更新属性画像分组
         * 
         * 参数：$group_id：类对象ID,$portrait_id：画像分组ID,$property_str：属性ID逗号字符串
         * 
         * 返回：true-成功，false-失败
         */
        public static function upatePropertyPortrait($group_id,$portrait_id,$property_str)
        {
            $db = new DB();
            $sql = "update dbo.tb_B_DictTreeProperty set F_PortraitID = null where F_GroupID = " . $group_id . " and F_PortraitID = " . $portrait_id . " update dbo.tb_B_DictTreeProperty set F_PortraitID = " . $portrait_id . " where F_GroupID = " . $group_id . " and F_PropertyID in (select F_ObjectID from dbo.fun_SplitByComma('" . $property_str . "'))";
	        $result = $db -> execute($sql);
            return $result;
        }
        /*
         * 用途：获取对象画像属性
         * 
         * 参数：$entity_id：对象ID, $portrait_id 画像分组ID
         * 
         * 返回：[{ F_PropertyID:属性ID,F_PropertyName:属性名称,F_PropertyIdentifier:属性标识,F_PropertyTypeID:属性数据类型,F_PropertyUnit:属性单位,F_GroupTypeID:属性类型,F_PropertyValue:属性值,F_PropertyText:属性文本 }]
         */
        public static function getEntityPortraitProperty($entity_id,$portrait_id)
        {
            $db = new DB();
            $sql = "exec proc_API_GetEntityPortraitInfo " . $entity_id . "," . $portrait_id;
	        $result = $db -> query($sql);
            return $result;
        }
        /*
         * 用途：获取功能环境属性
         * 
         * 参数：$fun_id：功能ID
         * 
         * 返回：[{ F_EnvVar:环境属性 }]
         */
        public static function getFunctionEnvVar($fun_id)
        {
            $db = new DB();
            $sql = "select dbo.fun_GetFunctionEnvVar(" . $fun_id . ") as F_EnvVar";
	        $result = $db -> query($sql);
            return $result;
        }
        
        /*
         * 用途：获取对象月份日三段时间
         * 
         * 参数：$entity_id：对象ID, $year 指定年份, $start_month 起始月份, $end_month 截止月份
         * 
         * 返回：[{ year：年,month：月,work_start_time：工作开始时间,work_end_time：工作截止时间,tran_start_time1：过渡开始时间1,tran_end_time1：过渡截止时间1,tran_start_time2：过渡开始时间2,tran_end_time2：过渡截止时间2,data_src：数据标识（0-未设置，1-系统，2-平台） }]
         */
        public static function getMonthSplitTime($entity_id,$year,$start_month,$end_month)
        {
            $db = new DB();
            $sql = "select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2,data_src from dbo.fun_GetEntityMonthWorkTime(" . $entity_id . "," . $year . "," . $start_month . "," . $end_month . ") ";
	        $result = $db -> query($sql);
            return $result;
        }
        /*
         * 用途：获取（多）对象月份日三段时间
         * 
         * 参数：$entity_id：对象ID,多个逗号分割, $start 起始年月, $end 截止年月
         * 
         * 返回：[{ entity_id：对象ID,entity_name：对象名称,year：年,month：月,work_start_time：工作开始时间,work_end_time：工作截止时间,tran_start_time1：过渡开始时间1,tran_end_time1：过渡截止时间1,tran_start_time2：过渡开始时间2,tran_end_time2：过渡截止时间2,data_src：数据标识（0-未设置，1-系统，2-平台） }]
         */
        public static function getExMonthSplitTime($entity_id,$start,$end)
        {
            $db = new DB();
            $sql = "select entity_id,entity_name,year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2,data_src from dbo.fun_GetExEntityMonthWorkTime('" . $entity_id . "','" . $start . "','" . $end . "') order by entity_tag,year,month ";
	        $result = $db -> query($sql);
            return $result;
        }
        /*
         * 用途：更新对象月份日三段时间
         * 
         * 参数：$entity_id：对象ID, $year：年,$start_month：开始月,$end_month：截至月,$wstart：工作开始时间,$wend：工作截止时间,$tstart1：过渡开始时间1,$tend1：过渡截止时间1,$tstart2：过渡开始时间2,$tend2：过渡截止时间2
         * 
         * 返回：true-成功，false-失败
         */
        public static function updateSplitTime($entity_id,$year,$start_month,$end_month,$wstart,$wend,$tstart1,$tend1,$tstart2,$tend2)
        {
            session_start();
            $db = new DB();
            $year = $year == '' ? 9999 : $year;
            $start_month = $start_month == '' ? 99 : $start_month;
            $end_month = $end_month == '' ? 99 : $end_month;
            $sql = "exec proc_D_EntitySplitTimeOperate '" . $entity_id . "','" . $year . "','" . $start_month . "','" . $end_month . "','" . $wstart . "','"  . $wend . "','" . $tstart1 . "','"  . $tend1 . "','" . $tstart2 . "','"  . $tend2 . "','" . $_SESSION['user']['code'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            $result = $db -> execute($sql);
            return $result;
        }
        /*
         * 用途：更新（多）对象（多）月份日三段时间
         * 
         * 参数：$entity_id：对象ID,多个逗号分割, $start：开始年月,$end_month：截至年月,$wstart：工作开始时间,$wend：工作截止时间,$tstart1：过渡开始时间1,$tend1：过渡截止时间1,$tstart2：过渡开始时间2,$tend2：过渡截止时间2
         * 
         * 返回：true-成功，false-失败
         */
        public static function updateExSplitTime($entity_id,$start,$end,$wstart,$wend,$tstart1,$tend1,$tstart2,$tend2)
        {
            session_start();
            $db = new DB();
            $sql = "exec proc_D_ExEntitySplitTimeOperate '" . $entity_id . "','" . $start . "','" . $end . "','" . $wstart . "','"  . $wend . "','" . $tstart1 . "','"  . $tend1 . "','" . $tstart2 . "','"  . $tend2 . "','" . $_SESSION['user']['code'] . "','" . $_SERVER["REMOTE_ADDR"] . "'";
            $result = $db -> execute($sql);
            return $result;
        }
        
        /*
         * 用途：获取指定系统派单记录
         * 
         * 参数：$sys_code：系统编号, $type 派单类型, $start 起始日期, $end 截止日期
         * 
         * 返回：[{ F_RowNum：序号,month：月,F_TaskCode：任务号,F_TaskType：任务类型,F_TaskDetail：任务内容,F_TaskStatus：任务状态,F_DispatchTime：派单时间,F_ExpectedTime：期望时间,F_UserName：指派人员名,F_DispatchUser：指派人员号,F_FinishTime：完成时间,F_PreImage：事前拍照,F_PostImage：事后拍照,F_PreDes：事前描述,F_PostDes：事后描述,F_UnableDes：退单说明,F_PushStatus：通知状态,F_ProjectNo：来自系统 }]
         */
        public static function getDispatchTaskList($sys_code,$type,$start,$end)
        {
            $db = new DB();
            $sql = "exec proc_D_GetSysDispatchTaskList '" . $sys_code . "','" . $type . "','" . $start . "','" . $end . "'";
	        $result = $db -> query($sql);
            return $result;
        }
        /*
         * 用途：更新指定系统派单记录
         * 
         * 参数：$oper：操作类型（1：添加，2：修改，3：删除，4：重新指派）, $code：任务号,$type：任务类型,$detail：任务内容,$etime：期望时间,$duser：指派人员,$sys：对应系统
         * 
         * 返回：true-成功，false-失败
         */
        public static function updateDispatchTask($oper,$code,$type,$detail,$etime,$duser,$sys)
        {
            session_start();
            $db = new DB();
            $sql = "exec proc_D_DispatchTaskOperate '" . $oper . "','" . $code . "','" . $type . "','" . $detail . "','" . $etime . "','" . $duser . "','" . $_SESSION['user']['code'] . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys . "'";
            $result = $db -> execute($sql);
            return $result;
        }
        /*
         * 用途：批量更新对象属性
         * 
         * 参数：$entity：对象ID，多个逗号分割, $tag：属性标识,$value：属性值
         * 
         * 返回：true-成功，false-失败
         */
        public static function batchEntityProperty($entity,$tag,$value)
        {
            session_start();
            $db = new DB();
            $sql = "exec proc_API_EntityPropertyImport '" . $entity . "','" . $tag . "','" . $value . "','" . $_SESSION['user']['id'] . "'";
            $result = $db -> execute($sql);
            return $result;
        }
    }
?>

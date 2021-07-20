<?php
    header("Content:text/html;charset=utf-8");
    include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/comm.php");
    include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/file.php");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

    $base = base();
    // 解析csv文件
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $handle = fopen($fileTmpName, 'r');
    $csvFile = File::read_csv($handle);
    array_shift($csvFile);
    if(count($csvFile) == 0){
		ob_clean();
        echo json_encode(array('errCode' => 0,'msg' => '文件内容为空！'));
    }else{
        // 获取课程配置
        $config = $db -> query("select * from [Things+].[dbo].[TB_Common_TimeTable_Config]");
        $config = json_decode($config[0] -> F_Config);
        $begin = date("Y-m-d",strtotime("this week Monday",strtotime($config -> termStart)));
        $time = $config -> class;
        if($_POST['type'] == "upload"){
            $db -> query("delete [Things+].[dbo].[TB_Common_TimeTable];delete [Things+].[dbo].[TB_Common_TimeTable_PlanInfo];delete [Things+].[dbo].[TB_Common_TimeTable_PlanMx];delete [Things+].[dbo].[TB_Common_TimeTable_Plan];");
        }
        // 按教室合并课程
        $classroom = array();
        $classname = array();
        foreach($csvFile as $csv){
            if(!array_key_exists($csv[1],$classroom)){
                $classroom[$csv[1]] = array();
                $classname[$csv[1]] = $csv[0];
            }
            array_push($classroom[$csv[1]],$csv);
        }
        // 将课程分解为每一天

        foreach($classroom as $room => $class){
            $timeTable = array();
            foreach($class as $kc){
                foreach(explode(",",$kc[2]) as $week){
                    foreach(explode(",",$kc[3]) as $day){
                        $today = explode("-",$day);
                        $timeTable[$week][$today[0]][$today[1]] = true;
                    }
                }
            }
            
            $sql = "";
            foreach($timeTable as $week => $wSub){
                $hasClass = "";
                foreach($wSub as $day => $dSub){
                    foreach($dSub as $now => $nSub){
                        $hasClass .= $hasClass == "" ? $now : ",".$now;
                    }
                }
                $sql .= "insert into [Things+].[dbo].[TB_Common_TimeTable] values('".$classname[$room]."',".$room.",'".date("Y-m-d",strtotime($begin." +".($week*1-1)." week +".($day*1-1)." day"))."','".$hasClass."');";
            }
            $db -> query($sql);
        }
        // 将课程转换成策略
        $all = $db -> query("select * from [Things+].[dbo].[Tb_Common_TimeTable]");
        foreach($all as $han){
            $SID = md5(microtime().$node -> code);
            $db -> query("insert into [Things+].[dbo].[TB_Common_TimeTable_PlanInfo] values(default,'课表-".$han -> F_name."',0,'".$han -> F_date."',2001,'".$SID."','".$han -> F_date."','".date("Y-m-d h:i:sa")."',1,8,1,1)");
            $hClass = explode(",",$han -> F_class);
            $first = "";$last = "";
            foreach($hClass as $num => $class){
                $curr = $time[$class-1];
                if($first == ""){
                    $first = $time[$class-1];
                    $last = $time[$class-1];
                }else{
                    if($curr -> type != $last -> type){
                        $db -> query("insert into [Things+].[dbo].[TB_Common_TimeTable_PlanMx] values(default,'".$first -> sControl."','".md5(microtime().$class)."','".$SID."','".$first -> cStart."','".$first -> sTime."','".($first -> sTime * $first -> sNum)."');insert into [Things+].[dbo].[TB_Common_TimeTable_PlanMx] values(default,'".$last -> eControl."','".md5(microtime().$class)."','".$SID."','".$last -> cEnd."','".$last -> eTime."','".($last -> eTime * $last -> eNum)."');");
                        $first = $curr;$last = $curr;
                    }else{
                        $last = $time[$class-1];
                    }
                    if(count($hClass) == $num+1){
                        $db -> query("insert into [Things+].[dbo].[TB_Common_TimeTable_PlanMx] values(default,'".$first -> sControl."','".md5(microtime().$class)."','".$SID."','".$first -> cStart."','".$first -> sTime."','".($first -> sTime * $first -> sNum)."');insert into [Things+].[dbo].[TB_Common_TimeTable_PlanMx] values(default,'".$last -> eControl."','".md5(microtime().$class)."','".$SID."','".$last -> cEnd."','".$last -> eTime."','".($last -> eTime * $last -> eNum)."');");
                    }
                }
            }
            $nodes = curl($base -> ip"/API/RData/GetMultiDeviceData/?type_id=".$_POST["device"]."&entity_str=".$han -> F_nodeid."&haschild=1");
            foreach($nodes as $node){
                $db -> query("insert into [Things+].[dbo].[TB_Common_TimeTable_Plan] values(default,'".$node -> code."',0,'".$han -> F_date."',2001,'".md5(microtime().$node -> code)."','".$han -> F_date."','".date("Y-m-d h:i:sa")."',1,8,'".$SID."')");
            }
        }
		ob_clean();
        echo json_encode(array('errCode' => 1,'msg' => '导入成功！'));
    }
?>

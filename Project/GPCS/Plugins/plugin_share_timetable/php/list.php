<?php
    header("Content:text/html;charset=utf-8");
    include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

    function csv_export($data=array(),$headlist=array(),$fileName){    
        header('Content-Type: application/vnd.ms-excel');    
        header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');    
        header('Cache-Control: max-age=0');    
        //打开PHP文件句柄,php://output 表示直接输出到浏览器    
        $fp = fopen('php://output','a');// 打开文件资源，不存在则创建 
        //输出Excel列名信息    
        foreach($headlist as $key => $value){        
        //CSV的Excel支持GBK编码，一定要转换，否则乱码        
            $headlist[$key] = iconv('utf-8','gbk',$value);    
        }    
        //将数据通过fputcsv写到文件句柄    
        fputcsv($fp,$headlist);    
        //计数器    
        $num = 0;    
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小    
        $limit = 100000;    
        //逐行取出数据，不浪费内存
        $count = count($data);    
        for($i=0;$i<$count;$i++){
            $num++;        
            //刷新一下输出buffer，防止由于数据过多造成问题       
            if($limit == $num){            
                ob_flush();           
                flush();           
                $num = 0;        
            }        
            $row = $data[$i];        
            foreach($row as $key => $value){            
                $row[$key] = iconv('utf-8','gbk',$value);        
            }        
            fputcsv($fp,$row);    
        }
    }
    $room = $db -> query("select F_EntityName as name,F_EntityID as id,null as week,null as day from [Things].[dbo].[tb_B_EntityTreeModel] where F_IsHasChild = 0 and F_EntityTreeNo =".$_GET["id"]);
    csv_export(json_decode(json_encode($room),true),array("name"=>"房间名称","id"=>"房间编号","week"=>"第几周（1,2）","day"=>"星期几的第几节课（1-1）"),"课表模板");
?>

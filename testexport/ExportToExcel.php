<?php

header('Content-type:text/html; Charset=utf-8');
class ExportToExcel
{
    /* 
    *导出数据到excel中
    *@param $title string 设置标题
    *@param $titlename string 设置表头
    *@param $datas array 设置表格数据
    *@param $filename string 导出excel文件名
    */
    public static function excelData($title, $head, $result, $filename="test") {
        $tab = "\t";
        $br = "\n";

        $str .= $title.$br;
        $str .= $head.$br; 

        // 测试数据
        $str .= "张三".$tab."30".$tab."男".$br;
        $str .= "李四".$tab."40".$tab."女".$br;
        $str .= "王五".$tab."20".$tab."男".$br;
        $str .= "李耳".$tab."10".$tab."女".$br;

        // while($row = mysql_fetch_row($result)) { // 和显示数据相同
        //     foreach ($row as $value){
        //         echo $value."<br/>";
        //         $str .= $value].$tab;
        //     }
        //     $str .=$br;
        // }
       
        header( "Content-Type: application/vnd.ms-excel; name='excel';  charset=gbk"); 
        header( "Content-type: application/octet-stream" ); 
        header( "Content-Disposition: attachment; filename=".$filename ); 
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
        header( "Pragma: no-cache" ); 
        header( "Expires: 0" ); 
        exit($str); 
    } 
}
?>

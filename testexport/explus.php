<?php
require_once "ExportToExcel.php";

// 测试数据
$title = "temp"; 
$head = "姓名\t年龄\t性别"; 
$dataResult = array();
$filename = $title.".xls"; 

ExportToExcel::excelData($title, $head, $dataResult, $filename);
?> 
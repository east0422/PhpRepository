<?php
date_default_timezone_set('PRC');
// $type=$_GET['type'];
// $sys=$_GET['sys'];




// if($type==1)
// {
// 	 $time=date('Y-m-d',time());
// }
// else
// {
// 	$time=date('Y-m-d',strtotime("-1 day"));
// }
$time=date('Y-m-d',time());
// require_once  '../../class/mysql.php';
header('Content-type:text/html;charset=utf-8');
header("Content-type: application/vnd.ms-excel; charset=gbk");
header("Content-Disposition:attachment;filename=".$time.".xls");
//输出内容如下： 




// $mysql=new mysql;
// $result = $mysql ->today_post_show(2,$sys);


echo iconv("UTF-8","GBK","收件人姓名(必填)")."\t"; 
echo iconv("UTF-8","GBK","收件人电话(必填)")."\t"; 
echo iconv("UTF-8","GBK","地址(必填)")."\t"; 
echo iconv("UTF-8","GBK","品名(必填)")."\t"; 
echo iconv("UTF-8","GBK","规格型号")."\t"; 
echo iconv("UTF-8","GBK","数量")."\t"; 
echo iconv("UTF-8","GBK","发件人姓名")."\t"; 
echo iconv("UTF-8","GBK","发件人电话")."\t"; 
echo iconv("UTF-8","GBK","备注（重量）")."\t"; 
echo iconv("UTF-8","GBK","快递单号")."\t"; 
echo iconv("UTF-8","GBK","备注")."\t"; 
echo  "\n"; 

echo iconv("UTF-8","GBK","aaa")."\t"; 
echo iconv("UTF-8","GBK","12345678901213")."\t"; 
echo iconv("UTF-8","GBK","121341442")."\t"; 
echo iconv("UTF-8","GBK","生活用品")."\t"; 
echo iconv("UTF-8","GBK","100")."\t"; 
echo iconv("UTF-8","GBK","1")."\t"; 
echo iconv("UTF-8","GBK","电商仓库中转站-昆山")."\t"; 
echo iconv("UTF-8","GBK","15312193901")."\t"; 
echo "9973227182486"."\t";
echo iconv("UTF-8","GBK","")."\t";
echo "\n"; 

// for($i=0;$i < count($result);$i++)
// {

// $n= $n+1;

// echo iconv("UTF-8","GBK",$result[$i]['name'])."\t"; 
// echo iconv("UTF-8","GBK",$result[$i]['mobile'])."\t"; 
// echo iconv("UTF-8","GBK",$result[$i]['address'])."\t"; 
// echo iconv("UTF-8","GBK","生活用品")."\t"; 
// echo iconv("UTF-8","GBK",$result[$i]['put_gifts_number'])."\t"; 
// echo iconv("UTF-8","GBK","1")."\t"; 
// echo iconv("UTF-8","GBK","电商仓库中转站-昆山")."\t"; 
// echo iconv("UTF-8","GBK","15312193901")."\t"; 


// $weight = $mysql->query_weight($result[$i]['put_gifts_number']);

// if($weight==''|| $weight==null)
// { echo   iconv("UTF-8","GBK","")."\t"; 
//    }
//    else{
// echo   iconv("UTF-8","GBK",$weight.'g')."\t"; 
//  }

 echo " ".$result[$i]['post_num']."\t";

// echo  iconv("UTF-8","GBK","")."\t";

// echo  "\n"; 

// }





/*
echo  iconv("UTF-8","GBK","姓名")."\t"; 
echo  iconv("UTF-8","GBK","年龄")."\t"; 
echo  iconv("UTF-8","GBK","学历")."\t"; 
echo   "\n"; 
echo   iconv("UTF-8","GBK","张三")."\t"; 
echo   "25"."\t"; 
echo    iconv("UTF-8","GBK","本科")."\t"; 

*/

?>
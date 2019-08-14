<?php
    //直接用头部信息输出excel格式文件，内容以表格形式展示。
 
    $filename='test';
    header("Content-type: application/vnd.ms-excel; charset=gbk");
    header("Content-Disposition: attachment; filename=$filename.xls");
    //$list为数据库查询结果，既二维数组。利用循环出表格，直接输出，既在线生成execl文件
    // foreach($list as $key => $val)
    // {
    //     $data .= "<table border='1'>";
    //     $data .= "<tr><td colspan='2'>订单号：".$val['order_sn']."</td><td>用户名：".$val['user_name']."</td><td colspan='2'>收货人：".$val['consignee']."</td><td colspan='2'>联系电话：".$val['tel']."</td></tr>";
    //     $data .= "<tr><td colspan='5'>送货地址：".$val['address']."</td><td colspan='2'>下单时间：".$val['add_time']."</td></tr>";
    //     $data .= "<tr bgcolor='#999999'><th>序号</th><th>货号</th><th>商品名称</th><th>市场价</th><th>本店价</th><th>购买数量</th><th>小计</th></tr>";
    //     $data .= "<tr><th>1</th><th>".$val['goods_sn']."</th><th>".$val['goods_name']."</th><th>".$val['market_price']."</th><th>".$val['goods_price']."</th><th>".$val['goods_number']."</th><th>".$val['money']."</th></tr>";
    //     $data .= "</table>";
    //     $data .= "<br>";
    // }
    $data .= "<table border='1'>";
        $data .= "<tr><td colspan='2'>订单号："."订单111"."</td><td>用户名："."张三"."</td><td colspan='2'>收货人："."李四"."</td><td colspan='2'>联系电话："."1234567890"."</td></tr>";
        $data .= "<tr><td colspan='5'>送货地址："."中国湖北"."</td><td colspan='2'>下单时间："."2018-11-05"."</td></tr>";
        $data .= "<tr bgcolor='#999999'><th>序号</th><th>货号</th><th>商品名称</th><th>市场价</th><th>本店价</th><th>购买数量</th><th>小计</th></tr>";
        $data .= "<tr><th>1</th><th>"."货号11111"."</th><th>"."商品121"."</th><th>"."129$"."</th><th>"."100$"."</th><th>"."10"."</th><th>"."1000$"."</th></tr>";
        $data .= "</table>";
        $data .= "<br>";
    $data.='</table>';
    echo $data. "\t";
    // if (EC_CHARSET != 'gbk')
    // {
    //     echo yzy_iconv(EC_CHARSET, 'gbk', $data) . "\t";
    // }
    // else
    // {
    //     echo $data. "\t";
    // }

?>
<?php
include_once './Mysql.php';

header('Content-type:text/html;charset=utf-8');
    
    $mysql = new Mysql();
    $rows = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $wxid = $_POST['wxid'];
        $txstation = $_POST['txstation'];
        if (empty($wxid) && empty($txstation)) { // 都为空
            $rows = $mysql->queryAll();
        } elseif (!empty($wxid) && !empty($txstation)) { // 都不为空
            $rows = $mysql->queryByWxidAndTxstation($wxid, $txstation);
        } elseif (!empty($wxid)) {
             $rows = $mysql->queryByWxid($wxid);
        } elseif (!empty($txstation)) {
            $rows = $mysql->queryByTxstation($txstation);
        } else {
            $rows = $mysql->queryAll();
        }
    } else {
        $rows = $mysql->queryAll();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>提现记录</title>
        <style type='text/css'>
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
            }
        </style>
    </head>
    <body>
        <h1>提现记录</h1>
        <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'> 
           微信id: <input type='text' name='wxid' value="">
           平台代号: <input type='number' name='txstation' value="">
           <input type="submit" name="submit" value="查询"> 
        </form>
        <br></br>
        <table border="1">
            <tr>
                <th>id</th>
                <th>wxid</th>
                <th>金额(分)</th>
                <th>平台代号</th>
                <th>平台id</th>
                <th>订单号</th>
                <th>提现时间</th>
            </tr>
            <?php foreach($rows as $item) {
            ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['wxid']; ?></td>
                <td><?php echo $item['txmoney']; ?></td>
                <td><?php echo $item['txstation']; ?></td>
                <td><?php echo $item['txid']; ?></td>
                <td><?php echo $item['txorder']; ?></td>
                <td><?php echo $item['txtime']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>
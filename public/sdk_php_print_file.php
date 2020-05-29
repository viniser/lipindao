<?php
/**
 *@author：xurushan
 *@datetime：2013-1-9 下午12:55:21
 *@todo：客户方按照订单唯一序列号，订单号，运单号查询订单信息：具体为唯一序列号，订单号，运单号，
 *订单当前状态
 */
//header("Content-type:text/xml;charset=utf-8");
header("Content-type:application/pdf;");
include_once './interface_fun.php';

/*
 * 外部订单接入接口 测试文件
 * */ 
$str="
	<orders>
        <order>
	    	<mailno>2310000628637</mailno>
        </order>
	</orders>
";  


$url="http://orderdev.yundasys.com:10209/cus_order/order_interface/interface_print_file.php";

$data=make_send_data_in($_REQUEST['orderdata'],'data',$_REQUEST['user'],$_REQUEST['pass']); 
//$data=make_send_data_in($str);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);      // set url to post to
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
curl_setopt($ch, CURLOPT_TIMEOUT, 60);      // times out 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);     // add POST fields
$result = curl_exec($ch); 
//print_r($result);
if($error = curl_error($ch)) 
{   
	echo $error;
	return -1;   
}   	
// print_r($result);
echo $result;

//file_put_contents('../print_file/2310000088547.pdf', $result)


?>
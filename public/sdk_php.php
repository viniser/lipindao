<?php
error_reporting(E_ERROR);

/****************header******************/
header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); # HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); #过去的时间

//function-start
function WriteCombo($vals,$selected='') {
	$res = "";

	if(count($vals)==0){
		return $res;
	}else{
		foreach ( $vals as $k=>$v ){
			$checked=($k==$selected?' checked="checked"':'');
			$remark='';
			if(in_array($k, array(7,8))){
				$checked.=' disabled';
				$remark='<span style="color:red;">(此接口停用)</span>';
			}elseif (in_array($k,array(100)) && $_SESSION['_gs_bm']!=999169){
				continue;
			}
			$res.='<input '.$checked.' style="width:20px;" type="radio" name="interface" value="'.$k.'" />'.$k.'.'.$v['name'].'('.$v['interface'].')'.$remark.'<br />';
		}
		return $res;
	}
}
function vfunction($str,$form_interface,$act){
	$form_interface=(int) $form_interface;
	switch ($form_interface){
		case 1:
		case 2:
		case 7:
		case 11:
		case 100:
		case 101:
		case 12:
		case 13:
		case 102:
		case 9:
			$rt=make_send_data_in($str,'data',$_REQUEST['user'],$_REQUEST['pass']);
			return $rt;
			break;
		case 3:
			$rt=make_send_data_in($str,'cancel_order',$_REQUEST['user'],$_REQUEST['pass']);
			return $rt;
			break;
		case 4:
			$rt=make_send_data_in($str,'reprint_order',$_REQUEST['user'],$_REQUEST['pass']);
			return $rt;
			break;
		case 5:
			$rt=make_send_data_in($str,'valid_order',$_REQUEST['user'],$_REQUEST['pass']);
			return $rt;
			break;
	}
}
/*生成指定格式的发送数据	外部系统接入*/
function make_send_data_in($xmldata,$request='data',$user='YUNDA',$pass='123456')
{
	return ("partnerid=".$user."&version=1.4&request={$request}&xmldata=".urlencode(base64_encode($xmldata))."&validation=".urlencode(strtolower(md5(base64_encode($xmldata).$user.$pass))));
}
//function-end


//配置信息-start
//接单信息每次发送数量上限
define('ORDER_SEND_NUM_LIMIT',10);

$test_str=array(
		1=>"<orders>
		   <order>
		    <order_serial_no>12226115</order_serial_no>
		    <khddh>BCA1000012315</khddh>
		   	<nbckh>2001123</nbckh>
	        <sender>
	            <name>王小虎</name>
	            <company>凯利</company>
	            <city>江苏省，徐州市，新沂市</city>
	            <address>湖东路999号</address>
	            <postcode>221435</postcode>
	            <phone>021-8592652</phone>
	            <mobile>13761960078</mobile>
	            <branch>410000</branch>
	        </sender>
	        <receiver>
	            <name>陆大有</name>
	            <company>千千</company>
	            <city>上海市，上海市，青浦区</city>
	            <address>上海市，上海市，青浦区盈港东路6633号</address>
	            <postcode>201700</postcode>
	            <phone>020-57720341</phone>
	            <mobile>13761960075</mobile>
	            <branch>315100</branch>
	        </receiver>
	        <weight>11</weight>
	        <size></size>
	        <value>20</value>
	        <freight></freight>
	        <premium></premium>
	        <other_charges></other_charges>
	        <collection_currency></collection_currency>
	        <collection_value></collection_value>
	        <special>服装</special>
	        <items>
	        	<item>
	                <name>外套</name>
	                <number>1</number>
	                <remark>黑色</remark>
	            </item>
	         </items>
	        <remark></remark>
	        <cus_area1>订单号：123 \n批次号：456212</cus_area1>
	        <cus_area2></cus_area2>
	      </order>
		</orders>",
		2=>'<orders>
		    <order>
		    <order_serial_no>12226115</order_serial_no>
		    <khddh>BCA1000012315</khddh>
		    <nbckh></nbckh>
	        <sender>
	            <name>王里</name>
	            <company>凯利</company>
	            <city>江苏省，徐州市，新沂市</city>
	            <address>湖东路999号</address>
	            <postcode>221435</postcode>
	            <phone>8592652</phone>
	            <mobile>13761960078</mobile>
	            <branch>410000</branch>
	        </sender>
	        <receiver>
	            <name>胡大明</name>
	            <company>BBS</company>
	            <city>上海市，上海市，青浦区</city>
	            <address>盈港东路6679号</address>
	            <postcode>201700</postcode>
	            <phone>57720341</phone>
	            <mobile>13761960010</mobile>
	            <branch>315100</branch>
	        </receiver>
	        <weight>11</weight>
	        <size></size>
	        <value>20</value>
	        <collection_value></collection_value>
	        <special>服装</special>
	        <items>
	        	<item>
	                <name>外套1</name>
	                <number>1</number>
	                <remark>粉色</remark>
	            </item>
	            <item>
	                <name>手套2</name>
	                <number>1</number>
	                <remark>红色</remark>
	            </item>
	         </items>
	        <remark></remark>
	        </order>
		</orders>',
		3=>'<orders>
		    <order>
		    	<order_serial_no>12226115</order_serial_no>
	        </order>
		</orders>',
		4=>'<orders>
		    <order>
		    	<order_serial_no>12226115</order_serial_no>
	        </order>
		</orders>',
		5=>'<orders>
		    <order>
		    	<order_serial_no>12226115</order_serial_no>
	        </order>
		</orders>',
		6=>'',
		7=>'<mailnos>
			<mailno>2310000072138</mailno>
			<mailno>2310000012016</mailno>
			<mailno>1255</mailno>
		</mailnos>',
		8=>'',
		9=>'<orders>
		    <order>
		    	<order_serial_no>12102566942170</order_serial_no>
		    	<mailno>2310000088543</mailno>
	        </order>
	        <order>
		    	<order_serial_no>12060100033154</order_serial_no>
	        </order>
		</orders>',
		10=>'<orders>
	        <order>
		    	<mailno>2310000072162</mailno>
	        </order>
		</orders>',
		11=>'<orders>
				<order>
				   <partner_orderid>13073011000009</partner_orderid>
				   <wave_no>1234567455</wave_no>
				   <wave_sno>42423</wave_sno>
				   <task_no>01</task_no>
				   <bhplan_no>46464154845155</bhplan_no>
				   <task_orderno>1585184184</task_orderno>
				   <order_date>2013-08-16</order_date>
				   <buyer_id>张三</buyer_id>
				   <total_num>2</total_num>
				   <buyer_address>江苏省徐州市新沂市湖东路999号</buyer_address>
				   <remark>亲，收货后记得给5分好评</remark>
				   <items>
					  <item>
						<serial_no>1</serial_no>
						<storage>12</storage>
						<num>1</num>
						<product_number>001</product_number>
						<format>5*10</format>
						<brand_name>手机</brand_name>
					  </item>
					  <item>
						<serial_no>2</serial_no>
						<storage>08</storage>
						<num>1</num>
						<product_number>002</product_number>
						<format>50*10*40</format>
						<brand_name>电视</brand_name>
					  </item>
				   </items>
				</order>
			</orders>',
		12=>"<orders>
		   <order>
		    <order_serial_no>12226115</order_serial_no>
		    <khddh>BCA1000012315</khddh>
		   	<nbckh>2001123</nbckh>
	        <sender>
	            <name>王小虎</name>
	            <company>凯利</company>
	            <city>江苏省，徐州市，新沂市</city>
	            <address>湖东路999号</address>
	            <postcode>221435</postcode>
	            <phone>021-8592652</phone>
	            <mobile>13761960078</mobile>
	            <branch>410000</branch>
	        </sender>
	        <receiver>
	            <name>陆大有</name>
	            <company>千千</company>
	            <city>上海市，上海市，青浦区</city>
	            <address>上海市，上海市，青浦区盈港东路6633号</address>
	            <postcode>201700</postcode>
	            <phone>020-57720341</phone>
	            <mobile>13761960075</mobile>
	            <branch>315100</branch>
	        </receiver>
	        <weight>11</weight>
	        <size></size>
	        <value>20</value>
	        <freight></freight>
	        <premium></premium>
	        <other_charges></other_charges>
	        <collection_currency></collection_currency>
	        <collection_value></collection_value>
	        <special>服装</special>
	        <items>
	        	<item>
	                <name>外套</name>
	                <number>1</number>
	                <remark>黑色</remark>
	            </item>
	         </items>
	        <remark></remark>
	        <cus_area1>订单号：123 \n批次号：456212</cus_area1>
	        <cus_area2></cus_area2>
	      </order>
		</orders>",
		13=>"<orders>
			<order>
				<id>2310000088544</id>
				<address>青浦</address>
			</order>
			<order>
				<id>2310000088545</id>
				<address>嘉定</address>
			</order>
		</orders>",
		100=>'<orders>
	        <order>
		    	<mailno>2310000072162</mailno>
	        </order>
		</orders>',
		101=>"<orders>
		   <order>
		    <order_serial_no>12226115</order_serial_no>
		    <khddh>BCA1000012315</khddh>
		   	<nbckh>2001123</nbckh>
	        <sender>
	            <name>王小虎</name>
	            <company>凯利</company>
	            <city>江苏省，徐州市，新沂市</city>
	            <address>湖东路999号</address>
	            <postcode>221435</postcode>
	            <phone>021-8592652</phone>
	            <mobile>13761960078</mobile>
	            <branch>410000</branch>
	        </sender>
	        <receiver>
	            <name>陆大有</name>
	            <company>千千</company>
	            <city>上海市，上海市，青浦区</city>
	            <address>上海市，上海市，青浦区盈港东路6633号</address>
	            <postcode>201700</postcode>
	            <phone>020-57720341</phone>
	            <mobile>13761960075</mobile>
	            <branch>315100</branch>
	        </receiver>
	        <weight>11</weight>
	        <size></size>
	        <value>20</value>
	        <freight></freight>
	        <premium></premium>
	        <other_charges></other_charges>
	        <collection_currency></collection_currency>
	        <collection_value></collection_value>
	        <special>服装</special>
	        <items>
	        	<item>
	                <name>外套</name>
	                <number>1</number>
	                <remark>黑色</remark>
	            </item>
	         </items>
	        <remark></remark>
	        <cus_area1>订单号：123 \n批次号：456212</cus_area1>
	        <cus_area2></cus_area2>
	      </order>
		</orders>",
		102=>"<orders>
			<order>
				<id>2310000088544</id>
				<sender_address>青浦</sender_address>
				<receiver_address>山西省太原市小店区山西大学</receiver_address>
			</order>
			<order>
				<id>2310000088545</id>
				<sender_address>杭锦后旗</sender_address>
				<receiver_address>嘉定</receiver_address>
			</order>
		</orders>",

);
//配置信息-end


//测试服务器全部开通
//正式服务器部分开通,请注意
$interface=array(
		1=>array('name'=>'订单创建接口','interface'=>'interface_receive_order__mailno.php'),		// ./test_order_add.php  interface_receive_order__mailno.php
		2=>array('name'=>'订单更新接口','interface'=>'interface_modify_order.php'),		// ./test_order_modify.php	有点问题
		3=>array('name'=>'订单取消接口','interface'=>'interface_cancel_order.php'),		// ./test_order_cancel.php
		4=>array('name'=>'订单重新打印接口','interface'=>'interface_cancel_order.php'),	// ./test_order_cancel.php
		5=>array('name'=>'订单重新生效接口','interface'=>'interface_cancel_order.php'),	// ./test_order_cancel.php
		6=>array('name'=>'推送接单信息','interface'=>'interface_customer_receive.php'),		// ./interface_customer_receive.php
		//7=>array('name'=>'订单运输状态查询','interface'=>'interface_transite_search.php'),	//暂停
		//8=>array('name'=>'订单运输状态推送','interface'=>'B->A'),						// 暂停
		9=>array('name'=>'订单信息查询','interface'=>'interface_order_info.php'),		// ./test_order_info.php 
		10=>array('name'=>'PDF打印查询接口','interface'=>'interface_print_file.php'),	// ./test_print_file.php 
		11=>array('name'=>'拣货单创建接口','interface'=>'interface_receive_pick.php'),	// 
		12=>array('name'=>'订单创建接口(含筛单)','interface'=>'interface_receive_order__mailno.php'),		// ./test_order_add.php
		13=>array('name'=>'筛单接口','interface'=>'order_select_reach.php'),		
		
		100=>array('name'=>'本地服务拉取条码','interface'=>'interface_send_mailno_local_server.php'),
		101=>array('name'=>'创建/更新 本地服务订单','interface'=>'interface_receive_order_local_server.php'),
		102=>array('name'=>'是否送达,获取集包地','interface'=>'interface_select_reach_package.php'),
);


/*
 * 外部订单接入接口 测试文件
 * */ 

$act=!empty($_REQUEST['act'])?$_REQUEST['act']:'none';
if($_REQUEST['interface']==6){
	//此接口是推送接口
}elseif($_POST['orderdata']!=''){
	if($_REQUEST['act']=='sub'){
		
		$str=$_POST['orderdata'];
		if($_REQUEST['interface']==13){
			$url="http://orderdev.yundasys.com:10209/cus_order/pub_crontab/".$interface[$_REQUEST['interface']]['interface'];
		}else{
			$url="http://orderdev.yundasys.com:10209/cus_order/order_interface/".$interface[$_REQUEST['interface']]['interface'];
		}
		
		$data=vfunction($str,$_REQUEST['interface']);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);      // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);      // times out 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);     // add POST fields
		$result = curl_exec($ch); 
		echo($result);
		if($error = curl_error($ch)) 
		{   
			$result= $error;
			return -1;   
		}
		switch ($form_interface){
			case 3:
				$xml = new SimpleXMLElement($result);
				if(!is_object($xml)){
					die("空对象");
				}
				
				foreach($xml as $k=>$v){
					$filename="../receive_file/print_".$v->order_serial_no.'.pdf';
					$handle = fopen($filename, "w");
					$contents = fwrite($handle,base64_decode($v->msg));
					fclose($handle);
				}
				break;
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js" type="text/javascript"> </script>
<title>二维码系统联调页面</title>
<style>
textarea{font-size:12px}
</style>
<script type="text/javascript">
 
$(function(){
	$('input[name="interface"]').click(function(){
		$('#form').attr('target','');
		$('#form').attr('action','');
		$('#act').val('interface_type');
		if($('#p_interface').attr('attr')!=$(this).val()){
			$('#form').submit();
		}
	});
	$('input[name="sub"]').click(function(){
		$('#act').val('sub');
		if($('input[name="interface"]:checked').val()==10){
			$('#form').attr('target','_blank');
			$('#form').attr('action','./sdk_php_print_file.php');
			$('#form').submit();
		}else{
			$('#form').submit();
		}
	});
});
</script>
</head>

<body style='font-size:12px'>
	<form action='' method='post' id="form" style="margin-top: 5px;">
		<input value="<?php echo $act=='none'?'submit':'none'; ?>" name="act" type="hidden" id="act" />
		<div style="width: 45%;float:left;border-right:1px solid black;">
			<fieldset style="width: 90%;float:left;">
				<legend>接口类型</legend>
				<p id="p_interface" attr="<?php echo $_REQUEST['interface']?>">
					<?php echo WriteCombo($interface,$_REQUEST['interface']);?>
				</p>
			</fieldset>
			
			<fieldset style="clear:both;width: 90%;float:left;<?php echo $_REQUEST['interface']==6?'display:none;':''?>">
				<legend>用户和密码</legend>
				<p>
					用户:<input name="user" type="text" value="200064123456" /><br />
					密码:<input name="pass" type="text" value="dmp4hjnQKJHZfB2xWDRMicyI5VCesP" /><br />
				</p>
			</fieldset>
			
			<fieldset style="clear:both;width: 90%;float:left;<?php echo $_REQUEST['interface']==6?'':'display:none;'?>">
				<legend>客户接收接口</legend>
				<p>
					<?php 
					if(trim($_REQUEST['act'])=='interface_type'){
						if(!empty($rs[0]['interface_response_host'])){
							$kh_interface=$rs[0]['interface_response_host'];
						}else{
							$kh_interface='http://127.0.0.1/cus_order/pub/interface_customer_receive.php';
						}
					}else{
						$kh_interface=$_REQUEST['kh_interface'];
					}
					?>
					<input name="kh_interface" type="text" style="width:100%;" value="<?php echo $kh_interface;?>" /><br />
				</p>
			</fieldset>
			
			<fieldset style="clear:both;width: 90%;float:left;">
				<?php 
				if($_REQUEST['interface']!=6){
				?>
				<legend>原始XML格式数据</legend>
				<p>
					<?php //print_r($_SESSION)?>
					<textarea name='orderdata' id='orderdata' style='width:100%;height:200px;'>
						<?php echo $_REQUEST['act']=='none'?'':($_REQUEST['act']=='interface_type'?$test_str[$_REQUEST['interface']]:$_REQUEST['orderdata']);?>
					</textarea>
				</p>
				<?php 
				}else{
				?>
				<legend>发送数据的条件</legend>
				<p>
					在<span style="color: red;"> 所有 </span>本客户的订单中,发送一组数据<input name="send_order_info" type="radio" value="1" <?php echo $_REQUEST['send_order_info']==1?'checked':''?> /><br />
					本客户<span style="color: red;"> 接单成功的订单 </span>中,发送一组数据<input name="send_order_info" type="radio" value="0" <?php echo empty($_REQUEST['send_order_info'])?'checked':''?> />
				</p>
				<?php 
				}
				?>
			</fieldset>
			<input name="sub" type="button" value="提交" style="float: right;width:100px;margin-right:50px;" />
		</div>
		
		<div style="width: 45%;float:left;margin-left:10px;">
			<fieldset style="clear:both;width: 90%;float:left;">
				<legend>经过编码过的数据</legend>
				<textarea name='send_data' style='width:100%;height:300px;'><?php echo $_REQUEST['interface']!=6?$data:$send_xml;?></textarea>
			</fieldset>
			
			<fieldset style="clear:both;width: 90%;float:left;">
				<legend>返回的结果</legend>
				<p>
					<textarea name='result' id="result" style='width:100%;height:200px;<?php echo $_REQUEST['interface']==10?'display:none;':''?>'><?php print_r($result) ;?></textarea>
				</p>
			</fieldset>
		</div>
	</form>
</body>
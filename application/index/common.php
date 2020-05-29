<?php
use think\Db;

 function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}	
//获取等级信息
  function getlv($lvid = 1){  
	 $lvinfo = Db::name('user_level')->where('id', $lvid)->find(); 
	 return $lvinfo;
  }
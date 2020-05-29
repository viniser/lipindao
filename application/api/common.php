<?php
use think\Db;
//获取等级信息
  function getlv($lvid = 1){  
	 $lvinfo = Db::name('user_level')->where('id', $lvid)->find(); 
	 return $lvinfo;
  }


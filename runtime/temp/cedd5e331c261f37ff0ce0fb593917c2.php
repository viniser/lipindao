<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:74:"/www/wwwroot/fenzhandemo/public/../application/index/view/express/buy.html";i:1545126716;s:67:"/www/wwwroot/fenzhandemo/application/index/view/layout/default.html";i:1544612382;s:64:"/www/wwwroot/fenzhandemo/application/index/view/common/meta.html";i:1540352400;s:67:"/www/wwwroot/fenzhandemo/application/index/view/common/sidenav.html";i:1544436128;s:66:"/www/wwwroot/fenzhandemo/application/index/view/common/script.html";i:1536722460;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<?php if(isset($keywords)): ?>
<meta name="keywords" content="<?php echo $keywords; ?>">
<?php endif; if(isset($description)): ?>
<meta name="description" content="<?php echo $description; ?>">
<?php endif; ?>
<meta name="author" content="FastAdmin">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />

<link href="/assets/css/frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config: <?php echo json_encode($config); ?>
    };
</script>
        <link href="/assets/css/user.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo url('/'); ?>" style="padding:6px 15px;"><img src="/assets/img/logo.png" style="height:40px;" alt=""></a>
                </div>
                <div class="collapse navbar-collapse" id="header-navbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/" ><?php echo __('Home'); ?></a></li>
                        <li><a href="<?php echo url('recharge/recharge'); ?>" >充值</a></li>
                        <?php if($user): ?>
                            <li><a href="<?php echo url('user/index'); ?>">您好：<?php echo $user['username']; ?></a></li>
                            <li><a href="<?php echo url('user/score'); ?>"><i class="fa fa-rmb fa-fw"></i><?php echo $user['money']; ?></a></li>
                            <li><a href="<?php echo url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i>退出</a></li>
                             <?php else: ?>
                             <li><a href="<?php echo url('user/login'); ?>"><i class="fa fa-sign-in fa-fw"></i> <?php echo __('Sign in'); ?></a></li>
                             <li><a href="<?php echo url('user/register'); ?>"><i class="fa fa-user-o fa-fw"></i> <?php echo __('Sign up'); ?></a></li>
                         <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content">
            <div id="content-container" class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="sidenav">
    <?php echo hook('user_sidenav_before'); ?>
    <ul class="list-group">
        <li class="list-group-heading">单号管理</li>
        <li class="list-group-item <?php echo $config['actionname']=='index'?'active':''; ?>"> <a href="<?php echo url('user/index'); ?>"><i class="fa fa-user-circle fa-fw"></i> 会员首页</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='buy'?'active':''; ?>"> <a href="<?php echo url('express/buy'); ?>"><i class="fa fa-shopping-cart fa-fw"></i>购买单号</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='uploads'?'active':''; ?>"> <a href="<?php echo url('express/uploads'); ?>"><i class="fa fa-upload fa-fw"></i>批量导入</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='exlist'?'active':''; ?>"> <a href="<?php echo url('express/exlist'); ?>"><i class="fa fa-list fa-fw"></i>单号管理</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='ulist'?'active':''; ?>"> <a href="<?php echo url('express/ulist'); ?>"><i class="fa fa-list fa-fw"></i>导入记录</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='adds'?'active':''; ?>"> <a href="<?php echo url('express/adds'); ?>"><i class="fa fa-map-marker fa-fw"></i>发货地址</a>
	</ul>    
    <ul class="list-group">
        <li class="list-group-heading">用户中心</li>
        <li class="list-group-item <?php echo $config['actionname']=='recharge'?'active':''; ?>"><a href="<?php echo url('index/recharge/recharge'); ?>"><i class="fa fa-cny fa-fw"></i> <?php echo __('充值'); ?></a></li>
        <li class="list-group-item <?php echo $config['actionname']=='score'?'active':''; ?>"> <a href="<?php echo url('user/score'); ?>"><i class="fa fa-rmb fa-fw"></i>资金明细</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='changepwd'?'active':''; ?>"> <a href="<?php echo url('user/changepwd'); ?>"><i class="fa fa-key fa-fw"></i>修改密码</a> </li>
        <li class="list-group-item <?php echo $config['actionname']=='logout'?'active':''; ?>"> <a href="<?php echo url('user/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i>退出</a> </li>
    </ul>
    <?php echo hook('user_sidenav_after'); ?>
</div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default ">
                  <div class="panel-body">
                       <h2 class="page-header">发布快递</h2>
                       
						 <form name="form" id="adds-form" class="form-horizontal" method="POST" action="/express/checkyto">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-2">快递:</label>
								<div class="col-xs-12 col-sm-6">
								 <div class="radio">
								     <label ><input  name="expressid" type="radio" value="1" checked="checked"> 圆通 -全网通用</label>
								     <label ><input  name="expressid" type="radio" value="3"> 圆通-拼多多专用 </label>
								     <label >您当前的单价为：<span id="lp"><?php echo getlv($user['level'])['yto']; ?></span>元</label>
								 </div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-2">发货地址:</label>
								<div class="col-xs-12 col-sm-6">
									<select class="form-control" name="addsid" data-rule="required" >
									  <?php if(is_array($myaddress) || $myaddress instanceof \think\Collection || $myaddress instanceof \think\Paginator): $i = 0; $__LIST__ = $myaddress;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
									  <option value="<?php echo $vo['id']; ?>"><?php echo $vo['dianpu']; ?>-<?php echo $vo['fajianren']; ?>-<?php echo $vo['a_province']; ?>-<?php echo $vo['city']; ?>-<?php echo $vo['area']; ?>-<?php echo $vo['address']; ?></option>
									  <?php endforeach; endif; else: echo "$empty" ;endif; ?>                         
									</select>
								</div>
                           </div>
                           <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2">重量:</label>
                            <div class="col-xs-12 col-sm-4">
                                <input type="text" class="form-control" id="weight" name="weight" value="" data-rule="required;range[0.1~40]" placeholder="范围0.05-40kg">
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2">物品:</label>
                            <div class="col-xs-12 col-sm-4">
                                <input type="text" class="form-control" id="goods" name="goods" value=""  placeholder="10个字内">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2">地址说明:</label>
                            <div class="col-xs-12 col-sm-8">
                                <div class="b">格式：姓名，手机，地址</div>
                                <div class="b">例如：张三，13812345678，浙江省 杭州市 下城区 文兴路888号</div>
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2">收货地址:</label>
                            <div class="col-xs-12 col-sm-8">
                               <textarea name="addstext" id="addstext" class="form-control" rows="5" cols="50" placeholder="一行一个地址" data-rule="required" ></textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2"></label>
                            <div class="col-xs-12 col-sm-4">
                                <button type="button" class="btn btn-danger btn-dialog">检查格式</button>
                                <button type="submit" class="btn btn-success btn-embossed" disabled><?php echo __('Ok'); ?></button>
                            </div>
                          </div>		  	
						</form>
					</div>
            </div>
        </div>
    </div>
</div> 
 
        </main>

        <footer class="footer" style="clear:both">
             
            <p class="copyright">Copyright&nbsp;©&nbsp;2017-2018 Powered by <a href="/" target="_blank"><?php echo $site['name']; ?></a> All Rights Reserved <a href="https://www.miibeian.gov.cn" target="_blank"><?php echo $site['beian']; ?></a></p>
        </footer>

        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
 <script type="text/javascript">
function OnlineOver(){
document.getElementById("aFloatTools_Show").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "block";
document.getElementById("floatTools").style.width = "190px";
}
function OnlineOut(){
document.getElementById("aFloatTools_Show").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "none";
document.getElementById("floatTools").style.width = "36px";
}
if(typeof(HTMLElement)!="undefined")    //给firefox定义contains()方法，ie下不起作用
{   
      HTMLElement.prototype.contains=function(obj)   
      {   
          while(obj!=null&&typeof(obj.tagName)!="undefind"){ //通过循环对比来判断是不是obj的父元素
   　　　　if(obj==this) return true;   
   　　　　obj=obj.parentNode;
   　　}   
          return false;   
      };   
}  
function hideMsgBox(theEvent){ //theEvent用来传入事件，Firefox的方式
　 if (theEvent){
　 var browser=navigator.userAgent; //取得浏览器属性
　 if (browser.indexOf("Firefox")>0){ //如果是Firefox
　　 if (document.getElementById('divFloatToolsView').contains(theEvent.relatedTarget)) { //如果是子元素
　　 return; //结束函式
} 
} 
if (browser.indexOf("MSIE")>0){ //如果是IE
if (document.getElementById('divFloatToolsView').contains(event.toElement)) { //如果是子元素
return; //结束函式
}
}
}
/*要执行的操作*/
document.getElementById("aFloatTools_Show").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "none";
document.getElementById("floatTools").style.width = "36px";
}
</script>
<style type="text/css">
	.rides-cs {  font-size: 12px; background:#29a7e2; position: fixed; top: 250px; right: 0px; _position: absolute; z-index: 1500; border-radius:6px 0px 0 6px;}
	.rides-cs a { color: #00A0E9;}
	.rides-cs a:hover { color: #ff8100; text-decoration: none;}
	.rides-cs .floatL { width: 36px; float:left; position: relative; z-index:1;margin-top: 21px;height: 181px;}
	.rides-cs .floatL a { font-size:0; text-indent: -999em; display: block;}
	.rides-cs .floatR { width: 130px; float: left; padding: 5px; overflow:hidden;}
	.rides-cs .floatR .cn {background:#F7F7F7; border-radius:6px;margin-top:4px;}
	.rides-cs .cn .titZx{ font-size: 14px; color: #333;font-weight:600; line-height:24px;text-align:center;}
	.rides-cs .cn ul {padding:0px;}
	.rides-cs .cn ul li { padding-top: 3px; border-bottom: solid 1px #E6E4E4;overflow: hidden;text-align:center;}
	.rides-cs .cn ul li span { color: #777;}
	.rides-cs .cn ul li a{color: #777;}
	.rides-cs .cn ul li img { vertical-align: middle; display:inline}
	.rides-cs .btnOpen, .rides-cs .btnCtn {  position: relative; z-index:9; top:25px; left: 0;  background-image: url(/qqopen.png); background-repeat: no-repeat; display:block;  height: 146px; padding: 8px;}
	.rides-cs .btnOpen { background-position: 0 0;}
	.rides-cs .btnCtn { background-position: -37px 0;}
	.rides-cs ul li.top { border-bottom: solid #ACE5F9 1px;}
	.rides-cs ul li.bot { border-bottom: none;}
</style>
<div id="floatTools" class="rides-cs" style="height:246px;">
  <div class="floatL">
  	<a id="aFloatTools_Show" class="btnOpen" title="查看在线客服" style="top:20px;display:block" href="javascript:OnlineOver();">展开</a>
  	<a id="aFloatTools_Hide" class="btnCtn" title="关闭在线客服" style="top:20px;display:none" href="javascript:hideMsgBox()">收缩</a>
  </div>
  <div id="divFloatToolsView" class="floatR" style="display:none;height:237px;width:140px;">
    <div class="cn">
      <h4 class="titZx">在线咨询</h4>
      <ul>                                            
        <li><span>客服</span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $site['qqhao']; ?>&site=qq&menu=yes"><img border="0" src="/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></li>
        <li><span>代理</span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=	<?php echo $site['dailiqq']; ?>&site=qq&menu=yes"><img border="0" src="/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></li>
        <?php if($site['weixinkf'] != '0'): ?>
         <li><img src="	<?php echo $site['weixinkf']; ?>" width="130" height="130"></li>
        <?php endif; ?> 
        <li style="border:none;"><span>每天9:00-18:00在线</span></li>
      </ul>
    </div>
  </div>
</div>
    </body>

</html>
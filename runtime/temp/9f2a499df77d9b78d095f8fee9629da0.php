<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"/www/wwwroot/fenzhandemo/public/../application/index/view/index/news.html";i:1545027186;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $data['title']; ?>-<?php echo $site['name']; ?></title>
        <meta name="keywords" content="<?php echo $data['keywords']; ?>">
        <meta name="description" content="<?php echo $data['desc']; ?>">
        <!-- Bootstrap Core CSS -->
        <link href="/assets/css/frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="//cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
            <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body id="page-top">

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
            <div class="container">
              <div class="row">
              	<div class="col-lg-9">
				  <ol class="breadcrumb">
					<li><a href="/" aria-label="首页">首页</a></li>
					<li class="active">详情</li>
				  </ol>
           	      <h4 class="page-header" align="center"><?php echo $data['title']; ?><small><?php echo date("Y-m-d",$data['create_time']); ?></small></h4>
            	  <div class="message">
            	  	 <?php echo $data['content']; ?>
            	  </div> 
             	</div>
              	<div class="col-lg-3">
				  <div class="panel panel-default index-discussionlist">
                    <div class="panel-heading"><a href="/a/jiaocheng/" class="more"><i class="es-icon es-icon-morehoriz"></i></a>
                        <h3 class="panel-title"><i class="es-icon es-icon-whatshot pull-left"></i>最新文章</h3>
                    </div>
                    <div class="panel-body row">
                        <ul class="list-unstyled">
                           <?php if(is_array($newslist) || $newslist instanceof \think\Collection || $newslist instanceof \think\Paginator): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nl): $mod = ($i % 2 );++$i;?>
					    	  <li><p class="clearfix"><a href="<?php echo $nl['id']; ?>" class="pull-left"><?php echo $nl['title']; ?></a></p></li>
							<?php endforeach; endif; else: echo "" ;endif; ?>
                       </ul>
                    </div>
                </div>  
                </div>
              </div>
			</div>
        </main>
        <footer class="footer" style="clear:both">  
            <p class="copyright">Copyright&nbsp;©&nbsp;2017-2018 Powered by <a href="/" target="_blank"><?php echo $site['name']; ?></a> All Rights Reserved <a href="https://www.miibeian.gov.cn" target="_blank"><?php echo $site['beian']; ?></a></p>
        </footer>

    </body>

</html>
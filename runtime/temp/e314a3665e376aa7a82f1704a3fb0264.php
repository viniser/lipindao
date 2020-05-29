<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/fenzhandemo/public/../application/index/view/index/index.html";i:1544527460;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $site['name']; ?></title>
        <meta name="keywords" content="FastAdmin,ThinkPHP5通用后台,ThinkPHP框架,Bootstrap后台">
        <meta name="description" content="">
        <!-- Bootstrap Core CSS -->
        <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/index.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.fastadmin.net/assets/css/main.css">
        <!-- Plugin CSS -->
        <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="//cdn.bootcss.com/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">

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

        <header>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-content">
                            <div class="header-content-inner">
                                <h1>快递</h1>
                                <h3>电子面单管理系统</h3>
                                 
                                <a href="<?php echo url('index/user/index'); ?>" class="btn btn-warning btn-xl page-scroll"><?php echo __('Go to Member center'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="pix_section pix-padding-v-75 pix-company-1" id="hongbao" style="display: block; padding-top: 74px;">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-xs-12 col-sm-7 column ui-droppable">
                <div class="pix-content text-left">
                    <h1 class="pix-white secondary-font">
                        <span class="pix_edit_text"><strong>FastAdmin专享红包</strong></span>
                    </h1>
                    <h5 class="pix-navy-blue-2 pix-small2-width-text pix-margin-bottom-20 pix-no-margin-top">
                        <span class="pix_edit_text">领取支付宝红包后再购买FastAdmin插件更优惠</span>
                    </h5>
                    <div class="pix-content text-left pix-margin-bottom-10">
                        <img src="http://www.fastadmin.net/addons/qrcode/index/build?text=https%3A%2F%2Fqr.alipay.com%2Fcpx04734fvksdoeze5amucc" alt="">
                    </div>
                    <div class="pix-padding-bottom-40">
                        <a href="javascript:;" target="_blank" class="btn small-text blue-bg btn-xl pix-white pix-margin-right-10 wide pix-margin-top-10">
                            <span class="pix_edit_text"><b>打开支付宝扫一扫</b></span>
                        </a>
                        <a href="/store.html" target="_blank" class="btn small-text light-green-bg btn-xl pix-white pix-margin-right-10 wide pix-margin-top-10">
                            <span class="pix_edit_text"><b>去购买插件</b></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12 col-sm-5 column ui-droppable">
                <div class="pix-content pix-radius-3 white-bg pix-padding-h-20 pix-padding-v-50 pix-margin-v-20 text-center">
                    <div class="pix_section inner_section" id="section_headers_1" style="display: block;">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 column ui-droppable">
                                    <div class="pix-content pix-padding-v-10  text-left">
                                        <div class="media">
                                            <div class="media-left pix-icon-area text-center pix-padding-right-20 pix-padding-top-20">
                                                <img src="https://cdn.fastadmin.net/assets/images/company/letter.png" alt="">
                                            </div>
                                            <div class="media-body">
                                                <h5 class="pix-navy-blue">
                                                    <span class="pix_edit_text"><b>支付宝扫二维码领取</b></span>
                                                </h5>
                                                <p class="pix-navy-blue-2 pix-margin-bottom-30">
                                                    <span class="pix_edit_text">扫描左侧二维码即可领取</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 column ui-droppable">
                                    <div class="pix-content pix-padding-v-10 text-left">
                                        <div class="media ic">
                                            <div class="media-left pix-icon-area text-center pix-padding-right-20 pix-padding-top-10">
                                                <img src="https://cdn.fastadmin.net/assets/images/company/store.png" alt="">
                                            </div>
                                            <div class="media-body">
                                                <h5 class="pix-navy-blue">
                                                    <span class="pix_edit_text"><strong>支付宝搜索564830505</strong></span>
                                                </h5>
                                                <p class="pix-navy-blue-2 pix-margin-bottom-30">
                                                    <span class="pix_edit_text">打开支付宝搜索即可领红包</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 column ui-droppable">
                                    <div class="pix-content pix-padding-v-10  text-left">
                                        <div class="media">
                                            <div class="media-left pix-icon-area text-center pix-padding-right-20 pix-padding-top-20">
                                                <img src="https://cdn.fastadmin.net/assets/images/company/card.png" alt="">
                                            </div>
                                            <div class="media-body">
                                                <h5 class="pix-navy-blue">
                                                    <span class="pix_edit_text"><strong>温馨提示</strong></span>
                                                </h5>
                                                <p class="pix-navy-blue-2 pix-margin-bottom-30">
                                                    <span class="pix_edit_text">请使用支付宝扫码，微信不支持</span><br>
                                                    <span class="pix_edit_text">购买插件时务必切换到支付宝支付</span><br>
                                                    <span class="pix_edit_text">红包每天均可领，3天有效</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
        </header>

       <!-- <section id="features" class="features">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="section-heading">
                            <h2><?php echo __('Features'); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-user text-primary"></i>
                                        <h3><?php echo __('Auth'); ?></h3>
                                        <p class="text-muted"><?php echo __('Auth tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-screen-smartphone text-primary"></i>
                                        <h3><?php echo __('Responsive'); ?></h3>
                                        <p class="text-muted"><?php echo __('Responsive tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-present text-primary"></i>
                                        <h3><?php echo __('Languages'); ?></h3>
                                        <p class="text-muted"><?php echo __('Languages tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-layers text-primary"></i>
                                        <h3><?php echo __('Module'); ?></h3>
                                        <p class="text-muted"><?php echo __('Module tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-docs text-primary"></i>
                                        <h3><?php echo __('CRUD'); ?></h3>
                                        <p class="text-muted"><?php echo __('CRUD tips'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="icon-puzzle text-primary"></i>
                                        <h3><?php echo __('Extension'); ?></h3>
                                        <p class="text-muted"><?php echo __('Extension tips'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>-->
 
        <footer>
            <div class="container">
                 
                <p>&copy; 2017-2018 <a href="/" target="_blank">电子面单API</a>. All Rights Reserved.</p>
               
            </div>
        </footer>

        <!-- jQuery -->
        <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="//cdn.bootcss.com/jquery-easing/1.4.1/jquery.easing.min.js"></script>

       
 

    </body>

</html>
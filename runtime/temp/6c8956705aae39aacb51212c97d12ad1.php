<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"/www/wwwroot/fenzhandemo/public/../application/admin/view/pay/order/index.html";i:1543673640;s:67:"/www/wwwroot/fenzhandemo/application/admin/view/layout/default.html";i:1536722460;s:64:"/www/wwwroot/fenzhandemo/application/admin/view/common/meta.html";i:1536722460;s:66:"/www/wwwroot/fenzhandemo/application/admin/view/common/script.html";i:1536722460;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" title="<?php echo __('Refresh'); ?>" ><i class="fa fa-refresh"></i> </a>
                        <a href="javascript:;" class="btn btn-success btn-edit btn-disabled disabled <?php echo $auth->check('pay/order/edit')?'':'hide'; ?>" title="<?php echo __('Edit'); ?>" ><i class="fa fa-pencil"></i> <?php echo __('Edit'); ?></a>
                        <a href="javascript:;" class="btn btn-danger btn-del btn-disabled disabled <?php echo $auth->check('pay/order/del')?'':'hide'; ?>" title="<?php echo __('Delete'); ?>" ><i class="fa fa-trash"></i> <?php echo __('Delete'); ?></a>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="<?php echo $auth->check('pay/order/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('pay/order/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/html" id="notifytpl">
    <div class="form-group">
        <label class="control-label" for="url">订单号</label>
        <input class="form-control" type="text" id="order_id" value="<%=notify.order_id%>">
    </div>
    <div class="form-group">
        <label class="control-label" for="url">请求URL</label>
        <input class="form-control" type="text" id="url" value="<%=notify.url%>">
    </div>
    <div class="form-group">
        <label class="control-label" for="params">请求参数</label>
        <textarea class="form-control" rows="5" id="params"><%=notify.params%></textarea>
    </div>
    <div class="form-group" style="position:relative;">
        <label class="control-label" for="response">返回结果</label>
        <textarea class="form-control" rows="10" id="response"><%=notify.response%></textarea>
        <a href="javascript:;" class="btn btn-primary btn-xs btn-preview" style="position:absolute;right:0px;top:0px;"><i class="fa fa-eye"></i> 预览</a>
    </div>
    <div class="form-group">
        <label class="control-label" for="url">请求时间</label>
        <input class="form-control" type="text" id="createtime" value="<%=notify.createtime_text%>">
    </div>
    <div class="form-group">
        <label class="control-label" for="url">响应时间</label>
        <input class="form-control" type="text" id="updatetime" value="<%=notify.updatetime_text%>">
    </div>
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>
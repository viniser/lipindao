<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"/www/wwwroot/fenzhandemo/addons/pay/view/api/create.html";i:1543673640;}*/ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class=""> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="renderer" content="webkit">
    <title>立即支付</title>

    <link rel="stylesheet" media="screen" href="/assets/addons/pay/css/pay.css?v=?v=<?php echo $site['version']; ?>"/>
    <link rel="stylesheet" media="screen" href="/assets/libs/font-awesome/css/font-awesome.min.css?v=?v=<?php echo $site['version']; ?>"/>

    <!--[if lt IE 9]>
    <script src="/libs/html5shiv.js?v=?v=<?php echo $site['version']; ?>"></script>
    <script src="/libs/respond.min.js?v=?v=<?php echo $site['version']; ?>"></script>
    <![endif]-->

</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo <?php echo $order['type']; ?>"></div>
    </div>
    <div class="mainbody">
        <div class="realprice">￥<?php echo $order['realprice']; ?></div>
        <?php if($order['discountprice']>0): ?>
        <div class="discountprice"><del>原价:<?php echo $order['price']; ?></del>，随机立减￥<?php echo $order['discountprice']; ?></div>
        <?php endif; ?>
        <div class="warning <?php if(!$order['manual']): ?>hidden<?php endif; ?>"><?php echo $isWechat ? '长按识别后' : '扫码后'; ?>请务必手动输入金额￥<?php echo $order['realprice']; ?></div>
        <div class="qrcode">
            <img class="image" src="<?php echo $qrcodeUrl; ?>" alt="">
            <div class="logo hidden-xs logo-<?php echo $order['type']; ?>"></div>
            <div class="expired hidden"></div>
            <div class="paid hidden"></div>
        </div>
        <div class="remainseconds">
            <div class="time minutes">
                <b><?php echo sprintf('%02d',intval($order['remainseconds']/60)); ?></b>
                <em>分</em>
            </div>
            <div class="colon">:</div>
            <div class="time seconds">
                <b><?php echo sprintf('%02d',intval($order['remainseconds']%60)); ?></b>
                <em>秒</em>
            </div>
        </div>

        <div class="help">
            <?php echo $addon['contacttips']; ?>
        </div>

        <div class="tips">
            <?php if($isMobile): if($order['type'] == 'wechat'): if($isWechat): ?>
                        请长按二维码进行支付
                    <?php else: ?>
                        请截屏后，打开<?php echo $payAppName; ?>，从相册选择二维码图片
                    <?php endif; else: if($isWechat): ?>
                        请截屏后，打开<?php echo $payAppName; ?>，从相册选择二维码图片
                    <?php else: ?>
                        <a href="<?php echo $order['qrcodeurl']; ?>" class="btn btn-info btn-lg">启动<?php echo $payAppName; ?>进行支付</a>
                    <?php endif; endif; else: ?>
            打开<?php echo $payAppName; ?>「扫一扫」
            <?php endif; ?>
        </div>
    </div>

    <?php if($addon['poweredby']): ?>
    <div class="footer">
        Powered by <a href="https://www.fastadmin.net?ref=pay-create">FastAdmin</a>
    </div>
    <?php endif; ?>
    <script>
        var order = <?php echo json_encode($order); ?>;
        var addon = {successtips:"<?php echo $addon['successtips']; ?>", expiretips:"<?php echo $addon['expiretips']; ?>", jumptips:"<?php echo $addon['jumptips']; ?>"}
    </script>
    <script type="text/javascript" src="/assets/libs/jquery/dist/jquery.min.js?v=<?php echo $site['version']; ?>"></script>
    <script type="text/javascript" src="/assets/addons/pay/js/pay.js?v=<?php echo $site['version']; ?>"></script>
</div>
</body>
</html>
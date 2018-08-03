<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';
$cateid=intval($_GPC['cateid']);
if (empty($cateid)){
    $msg=error(1,'请扫描包装外的二维码参与活动！');
    include $this->template('error') ;
    exit();
}
$cate=pdo_fetch('select * from '.tablename('hxq_ywym_cate')." where uniacid={$_W['uniacid']} and id={$cateid}");
if (empty($cate)){
    $msg=error(1,'请扫描包装外的二维码参与活动！');
    include $this->template('error') ;
    exit;
}
$act=pdo_fetch('select * from '.tablename('hxq_ywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
$act['thumbs']=$_W['attachurl'].$act['thumbs'];
$act['ucthumb']=$_W['attachurl'].$act['ucthumb'];
$act['faninfoarr']=iunserializer($act['faninfoarr']);
$pdt=pdo_fetch('select * from '.tablename('hxq_ywym_product')." where uniacid={$_W['uniacid']} and id={$cate['pdtid']}");
$pdt['description']=cutstr(str_replace(array(' ','&nbsp;',"\r","\n\r","\n"),'',strip_tags(htmlspecialchars_decode($pdt['description']))),24);
$_M['cate']=$cate;
$_M['act']=$act;
$_M['pdt']=$pdt;
$act['hpctnt']=htmlspecialchars_decode($act['hpctnt']);
$fan=pdo_fetch('select * from '.tablename('hxq_ywym_fan')." where uniacid={$_W['uniacid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
include $this->template('help');
exit;

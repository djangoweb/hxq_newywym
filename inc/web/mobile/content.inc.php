<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';	    
$this->checkact();
$act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
$act['faninfoarr']=iunserializer($act['faninfoarr']);
$act['description']=htmlspecialchars_decode($act['description']);
$act['page']=iunserializer($act['page']);

$_M['act']=$act;

$this->dooauth();
$fan=pdo_fetch('select * from '.tablename('hxq_newywym_fan')." where uniacid={$_W['uniacid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
include $this->template('content');
exit;

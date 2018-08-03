<?php
require_once  MODULE_ROOT.'/inc/function.php';
require_once  MODULE_ROOT.'/com/sendmessage.php';
global $_W,$_GPC,$_M;
if (empty($_M['slat'])){
    $msg=error(1,"出错了，系统设置有误！");
    include $this->template('error') ;
    exit;
}
$_SESSION['oauth_openid']='oauth_op';
$uniacid=$_W['uniacid'];
load()->model('account');
$oauth_acc=account_fetch($_W['account']['oauth']['acid']);
$_W['account']['oauth']['name']=$oauth_acc['name'];
unset($oauth_acc);
if (is_file(MODULE_ROOT.'/inc/menus_'.$uniacid.'.php')){
   require MODULE_ROOT.'/inc/menus_'.$uniacid.'.php';
}else{
   require MODULE_ROOT.'/inc/menus.php';
}
$lists=pdo_fetch("SELECT creditnames FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
if (!empty($lists['creditnames'])){
    $creditnames=iunserializer($lists['creditnames']);
}
$keyword=pdo_get('rule_keyword',array('uniacid' => $_W['uniacid'],'module'=>$this->modulename),array('content'));
$_M['keyword']=$keyword['content'];
foreach ($this->module['config']['notice'] as $key=>&$item){
    if (in_array($key,array('tplopenids','chtplopenids','censustplopenids'))){
    if (strexists($item, ',')){
        $item=explode(',',$item);
    }else{
        $item=array($item);
    }
    }
}
unset($item);
//var_dump();
   
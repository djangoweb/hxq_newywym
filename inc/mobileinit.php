<?php
require_once  MODULE_ROOT.'/inc/functions.php';
require_once  MODULE_ROOT.'/com/sendmessage.php';
global $_W,$_GPC,$_M;
if (empty($_M['slat'])){
    $msg=error(1,"出错了，系统设置有误！");
    include $this->template('error') ;
    exit;
}
$uniacid=$_W['uniacid'];
load()->model('account');
$oauth_acc=account_fetch($_W['account']['oauth']['acid']);
$_W['account']['oauth']['name']=$oauth_acc['name'];
unset($oauth_acc);
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
//多域名跳转防封杀
$jump=$this->module['config']['jump'];
if (!empty($jump['display'])){
    if (strexists($jump['display'],"\r\n")){
        $displays=explode("\r\n",$jump['display']);
    }elseif(strexists($jump['display'],"\n")){
        $displays=explode("\n",$jump['display']);
    }elseif(strexists($jump['display'],"\r")){
        $displays=explode("\r",$jump['display']);
    }else{
        $displays=array($jump['display']);
    }
    $jump['displays']=array_filter($displays);
    $jump['display']=$jump['displays'][0];
} 
if (defined('IN_MOBILE')&&!$_W['isajax']&&!empty($jump['redirect'])&&!empty($jump['display'])&&!in_array($_W['siteroot'],$jump['displays'])){
    //echo $jump['display'].substr($_W['script_name'],1) . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];
    header('location:'.$jump['display'].substr($_W['script_name'],1) . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING']);
}
//多域名跳转防封杀
//var_dump();
if ($_W['container'] == 'wechat') {
    $unisetting = uni_setting_load();
    if (!empty($unisetting['jsauth_acid'])) {
        $jsauth_acid = $unisetting['jsauth_acid'];
    } else {
        if ($_W['account']['level'] < 3 && !empty($unisetting['oauth']['account'])) {
            $jsauth_acid = $unisetting['oauth']['account'];
        } else {
            $jsauth_acid = $_W['acid'];
        }
    }
    if (!empty($jsauth_acid)) {
        $account_api = WeAccount::create($jsauth_acid);
        if (!empty($account_api)) {
            $urls = parse_url($_W['siteroot']);
            //$urls['path'] = str_replace(array('/web', '/app', '/payment/wechat', '/payment/alipay', '/payment/jueqiymf', '/api'), '', $urls['path']);
            //$_W['siteroot'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];
            $url = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '') . $_W['script_name'] . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];
            $_W['account']['jssdkconfig'] = $account_api->getJssdkConfig($url);
            $_W['account']['jsauth_acid'] = $jsauth_acid;
        }
    }
    //var_dump($_W['account']['jssdkconfig']);
    unset($jsauth_acid, $account_api);
}
   
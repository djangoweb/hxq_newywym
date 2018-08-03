<?php
require 'framework/bootstrap.inc.php';
defined('IN_IA') or exit('Access Denied');
define('IN_MOBILE', true);
define('MODULE_NAME','hxq_newywym');
require_once IA_ROOT.'/addons/'.MODULE_NAME.'/inc/functions.php';
load()->app('common');
load()->app('template');
$str=$_SERVER['QUERY_STRING'];
$strarr=explode('-',$str);
$tk=$strarr[0];
$vtk=$strarr[1];
if (EMPTY($tk)||empty($vtk)){
    exit('error');
}
$slat=pdo_fetch('select * from '.tablename('hxq_newywym_slat').'');
if (empty($slat)){
    exit('未设置加密盐');
}
$slat=$slat['slat'];
$cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where mintk<=$tk and maxtk>=$tk");
if (empty($cate)){
   exit('请扫码参与活动');
}
if (empty($cate['uniacid'])){
    exit('该码尚未绑定活动');
}
if (substr(md5($tk.$slat),0,8)!=strtolower($vtk)){
    exit('请扫码参与活动');
}
if ($_COOKIE[session_name()]){
    $keys = array_keys($_SESSION);
    foreach ($keys as $key) {
        unset($_SESSION[$key]);
    }
    unset($keys, $key);
    setcookie(session_name(),'',TIMESTAMP-3600);
    session_write_close();
}
header('location:'.$_W['sitescheme'].$_SERVER['HTTP_HOST']."/app/index.php?c=entry&m=".MODULE_NAME."&do=index&tk=$tk&vtk=$vtk&i={$cate['uniacid']}");
exit;
?>
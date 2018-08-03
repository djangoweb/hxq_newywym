<?php
require '../../framework/bootstrap.inc.php';
defined('IN_IA') or exit('Access Denied');
define('IN_MOBILE', true);
define('MODULE_NAME','hxq_newywym');
require_once IA_ROOT.'/addons/'.MODULE_NAME.'/inc/functions.php';
load()->app('common');
load()->app('template');
if ($_GPC['tk']&&$_GPC['vtk']){
    $tk=$_GPC['tk'];
    $vtk=$_GPC['vtk'];
}else{
    $str=$_SERVER['QUERY_STRING'];
    $strarr=explode('-',$str);
    $tk=intval($strarr[0]);
    $vtk=addslashes($strarr[1]);
}
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
header('location:'.$_W['sitescheme'].$_SERVER['HTTP_HOST']."/app/index.php?c=entry&m=".MODULE_NAME."&do=index&tk=$tk&vtk=$vtk&i={$cate['uniacid']}");
exit;
?>
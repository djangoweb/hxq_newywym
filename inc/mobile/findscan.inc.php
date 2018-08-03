<?php
	   global $_W,$_GPC;
       $uniacid=$_W['uniacid'];
       $slat=pdo_fetchcolumn('select slat from '.tablename('hxq_newywym_slat')."");
       $scan=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid=$uniacid and actid={$_GPC['actid']} and isgrant=0 and oauth_openid='{$_SESSION['oauth_openid']}' order by addtime desc");
       $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['actid']}");
       if (empty($scan)){
           $msg=error(1,$act['name'].'没有您的扫码记录或您已扫码领奖。');
           include $this->template('error');
       }else{
           $tk=$scan['tk'];
           $vtk=substr(md5($tk.$slat),0,8);
           header('location:'.$this->createmobileurl('index',array('tk'=>$tk,'vtk'=>$vtk)));
           exit;
       }
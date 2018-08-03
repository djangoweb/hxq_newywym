<?php
	   global $_W,$_GPC;
       $uniacid=$_W['uniacid'];
       $slat=pdo_fetchcolumn('select slat from '.tablename('hxq_newywym_slat')."");
       $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid=$uniacid and actid={$_GPC['actid']} and depth=1  and isgrant=0 and oauth_openid='{$_SESSION['oauth_openid']}' order by addtime desc");
       $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['actid']} order by addtime desc");
       if (empty($fission)){
           $msg=error(1,'您还没有参与'.$act['name']);
           include $this->template('error');
       }else{
           if ($_W['account']['level']<4&&$_SESSION['openid']){
               pdo_update('hxq_newywym_fission',array('openid'=>$_SESSION['openid']),array('oauth_openid'=>$_SESSION['oauth_openid'],'actid'=>$_GPC['actid']));
           }
           $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$fission['cateid']}");
           $pfission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where id={$fission['pid']}  order by addtime desc");
           header('location:'.$this->createmobileurl('fission',array('fid'=>$pfission['idstr'],'cateid'=>$cate['id'])));
           exit;
       }
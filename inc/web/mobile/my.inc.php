<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $op=in_array($_GPC['op'],array('display'))?$_GPC['op']:'display';
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $act['faninfoarr']=iunserializer($act['faninfoarr']);
	    $act['mycloselinks']=iunserializer($act['mycloselinks']);
	    $act['page']=iunserializer($act['page']);
	    $_M['act']=$act;	    
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    $biz['hascoupon']=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and endtime>unix_timestamp() and bizid={$act['bizid']}");
	    
	    $this->dooauth();
	    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid} and oauth_openid='{$_SESSION['oauth_openid']}'");	    
	    if ($_M['act']['rptype']==0){
	        require_once MODULE_ROOT.'/inc/mobile/my.pt.php';
	    }else{
	        require_once MODULE_ROOT.'/inc/mobile/my.tx.php';
	    }
	    
	


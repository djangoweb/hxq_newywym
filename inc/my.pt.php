<?php
if ($_W['fans']['uid']){
	        $credit1arr=pdo_get('mc_members',array('uid'=>$_W['fans']['uid']),array('credit1'));
	        if ($credit1arr){
	            $credit1=$credit1arr['credit1'];
	        }
}else{
    if ($_W['account']['level']==4&&$_W['oauth_account']['acid']==$_W['account']['acid']){
        $uidarr=pdo_get('mc_mapping_fans',array('openid'=>$_SESSION['oauth_openid']),array('uid'));
        if ($uidarr){
            $uid=$uidarr['uid'];
        }
        if ($uid){
            $credit1arr=pdo_get('mc_members',array('uid'=>$uid),array('credit1'));
        }
        if ($credit1arr){
            $credit1=$credit1arr['credit1'];
        }
    }
}
        $credit1=intval($credit1);
        $totalcoupon=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." a left join ".tablename('hxq_newywym_coupon')." as b on a.couponid=b.id where a.uniacid={$uniacid} and a.isgrant=1 and a.actid={$act['id']} and a.veriftime=0 and b.endtime>unix_timestamp() and a.oauth_openid='{$_SESSION['oauth_openid']}'");
        $totalsw=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=4");
        $rp['ytx']=$rp['total']=0;
	    $rp['ytx']=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
	    //echo "select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=0 and oauth_openid='{$_SESSION['oauth_openid']}' and type=1";	    
	    $rp['total']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
	    
	    $rp['ytx']=number_format($rp['ytx'],2);
	    $grp['ytx']=$grp['total']=0;
	    $grp['ytx']=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=2");
	    $grp['total']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=2");
	    $grp['ytx']=number_format($grp['ytx'],2);
	    //$verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and actid={$this->act['id']}",array(),'id');
	    unset($row);
	    include $this->template('my');
	    
	


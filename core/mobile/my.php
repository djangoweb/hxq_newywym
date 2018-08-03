<?php
class my extends page{
    public function main($op=''){
	    global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $this->dojump();
	    $op=in_array($op,array('display'))?$op:'display';
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $act['faninfoarr']=iunserializer($act['faninfoarr']);
	    $act['mycloselinks']=iunserializer($act['mycloselinks']);
	    $act['page']=iunserializer($act['page']);   
	    $this->act=$act;
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    $biz['hascoupon']=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and endtime>unix_timestamp() and bizid={$act['bizid']}");
	    $this->dooauth();
	    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid} and oauth_openid='{$_SESSION['oauth_openid']}'");	    
	    if ($_M['act']['rptype']==0){
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
        $totalcoupon=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." a inner join ".tablename('hxq_newywym_coupon')." as b on a.couponid=b.id where a.uniacid={$uniacid}  and a.actid={$act['id']} and a.veriftime=0 and b.endtime>unix_timestamp() and a.oauth_openid='{$_SESSION['oauth_openid']}'");
       //echo 'select count(a.*) from '.tablename('hxq_newywym_coupongrant')." a inner join ".tablename('hxq_newywym_coupon')." as b on a.couponid=b.id where a.uniacid={$uniacid}  and a.actid={$act['id']} and a.veriftime=0 and b.endtime>unix_timestamp() and a.oauth_openid='{$_SESSION['oauth_openid']}'";
        
        //echo $_SESSION['oauth_openid'];
        $totalsw=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']} and veriftime=0 and oauth_openid='{$_SESSION['oauth_openid']}' and type=4");
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
	    }else{
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
        $totalcoupon=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
        $totalsw=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=4");
        $rp['wtx']=$rp['ytx']=$rp['total']=0;
        $rp['wtx']=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']} and istx=0 and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
        $rp['ytx']=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']} and istx=1 and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
        $rp['total']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
        $rp['wtx']=number_format($rp['wtx'],2);
        $rp['ytx']=number_format($rp['ytx'],2);
        $rp['wjh']=number_format($rp['wjh'],2);
        $grp['wtx']=$grp['ytx']=$grp['total']=0;
        $grp['ytx']=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=2");
        $grp['total']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=2");
        $grp['wtx']=number_format($grp['wtx'],2);
        $grp['ytx']=number_format($grp['ytx'],2);
        //$verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and actid={$this->act['id']}",array(),'id');
        unset($row);
        include $this->template('my');
}
}}
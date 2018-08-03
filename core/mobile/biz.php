<?php
class biz extends page{
    public function main ($op='display'){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $this->dojump();
	    $op=in_array($op,array('display','detail'))?$op:'display';
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $act['thumbs']=$_W['attachurl'].$act['thumbs'];
	    $act['page']=iunserializer($act['page']);
	    parent::$act=$act;
	    $this->dooauth();
	    if ($op=='detail'){
            $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and bizid={$biz['id']} order by id desc");
	        $act['faninfoarr']=iunserializer($act['faninfoarr']);
	        $act['mycloselinks']=iunserializer($act['mycloselinks']);
	        $act['page']=iunserializer($act['page']);
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and endtime>unix_timestamp()",array(),'id');
	        foreach($coupons  as &$row){
    	        $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and couponid={$row['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    	        $row['starttime']=date('Y-m-d',$row['starttime']);
    	        $row['endtime']=date('Y-m-d',$row['endtime']);
	        }
	        include $this->template('biz');
	    }elseif ($op=='display'){
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']}",array(),'id');
	        $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and id={$grant['couponid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$coupon['bizid']}");
	        $coupon['starttime']=date('Y-m-d',$coupon['starttime']);
	        $coupon['endtime']=date('Y-m-d',$coupon['endtime']);
	        /*
	        $data=array(
	        'openid'=>$_SESSION['openid'],
	        'oauth_openid'=>$_SESSION['oauth_openid'],
	        'uniacid'=>$_W['uniacid'],
	        'actid'=>$act['id'],
	        'cid'=>$_GPC['id'],
	        'couponid'=>$coupon['couponid'],
	        'addtime'=>TIMESTAMP,
	        'nickname'=>$_W['fans']['nickname'],
	        'avatar'=>$_W['fans']['tag']['avatar'],
	        'addip'=>getip(),
	        );
	        pdo_insert('hxq_newywym_coupongrant',$data);*/
	        $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
	        include $this->template('coupon');
	    }   
    }
}


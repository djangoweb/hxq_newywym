<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $op=in_array($_GPC['op'],array('display','detail'))?$_GPC['op']:'display';
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    //print_r($biz);
	    if ($act['getfaninfo']){
	        $this->dooauth();
	    }
	    if ($op=='detail'){
            $biz['thumbs']=iunserializer($biz['thumbs']);
            $biz['totalthumbs']=count($biz['thumbs']);
            $totfancp=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." a inner join ".tablename('hxq_newywym_coupon')." as b on a.couponid=b.id where a.uniacid={$_W['uniacid']} and a.actid={$act['id']} and a.oauth_openid='{$_SESSION['oauth_openid']}' and a.veriftime=0 and b.endtime>unix_timestamp()");
            $totfannormalrp=pdo_fetchcolumn('select sum(redpack) from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=1");
            $totfanfisnrp=pdo_fetchcolumn('select sum(redpack) from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
            $totfankeeprp=pdo_fetchcolumn('select sum(redpack) from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
            
            $totfanrp=floatval($totfannormalrp)+floatval($totfanfisnrp)+floatval($totfankeeprp);
            $totfancp=intval($totfancp);
            $totfanrp=floatval($totfanrp);
            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and enable=1 and isshow=1 and endtime>unix_timestamp() order by id desc",array(),'id');
	        foreach($coupons  as &$row){
    	        $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and couponid={$row['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}' ");
    	    }
	        unset($row);
	        $products=pdo_fetchall('select * from '.tablename('hxq_newywym_product')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and disabled=0 order by id desc",array(),'id');
	        foreach($products as &$row){
	            $row['name']=mb_substr($row['name'], 0,8);
	            $row['nlen']=strlen($row['name']);
	        }
	        unset($row);
	        include $this->template('biz');
	    }elseif ($op=='display'){
            $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and disabled=0 order by id desc",array(),'id');
	        foreach ($coupons as &$row){
	            $coupon['starttime']=date('Y-m-d',$coupon['starttime']);
	            $coupon['endtime']=date('Y-m-d',$coupon['endtime']);
	        }
	        unset($row);
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
	        include $this->template('coupon');
	    }
	    
	    
	


<?php 
	global $_W,$_GPC,$_M;
	$uniacid=$_W['uniacid'];
	$op=in_array($_GPC['op'],array('display','add','delete','qrcode','download','reset'))?$_GPC['op']:'display';
    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
	$actid=$_GPC['actid'];
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
    if (!empty($actid)){
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
    }else{
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}   order by id desc");
    }
    $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
    $cates_=array_map('array_shift',$cates);
    $cates_=array_values($cates_);
    $str_cates=join(',',$cates_);
    
    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ({$str_cates}) order by id desc",array(),'id');
    $awards_=array_map('array_shift',$awards);
    $awards_=array_values($awards_);
    $str_awards=join(',',$awards_);
    $tokens=pdo_fetchall("select awardid,title,type,count(id) as totalawarded,sum(redpack) as redpack,sum(credit1) as credit1 from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and awardid in ({$str_awards}) and type!=100 and type!=5 group by awardid",array(),'awardid');
    
    $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$act['bizid']} order by id desc",array(),'id');
    $grants=pdo_fetchall("select couponid,type,count(id) as totalgrant from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$act['bizid']}  group by couponid",array(),'couponid');
    //var_dump($grants);;
    foreach ($grants as &$row){
        $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and couponid={$row['couponid']} and veriftime!=0 and deleted=0");
    }
    unset($row);
    
    $fisnawards=pdo_fetchall("select * from ".tablename('hxq_newywym_fisnaward')." where uniacid={$uniacid} and actid={$act['id']} order by id desc",array(),'id');
    $fisnawards_=array_map('array_shift',$fisnawards);
    $fisnawards_=array_values($fisnawards_);
    $str_awards=join(',',$fisnawards_);
    $fisns=pdo_fetchall("select awardid,title,type,count(id) as totalawarded,sum(redpack) as redpack,sum(credit1) as credit1 from ".tablename('hxq_newywym_fission')." where uniacid={$uniacid} and awardid in ({$str_awards}) and isgrant=1 and type!=5  and type!=100 group by awardid",array(),'awardid');
    //扫码统计
    $where.=" and actid={$act['id']} ";
    //分享统计
    $thirtydaystart=$act['starttime'];
    $thirtydayend=TIMESTAMP;
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
        $date=date('Y-m-d',$thirtydaystart);
        $sharecensus[$date]=pdo_fetchcolumn("select count(distinct(oauth_openid))  from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} $where and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $sharedates=array_keys($sharecensus);
    $sharecensuss=array_values($sharecensus);
    $sharecensuss=array_map('intval',$sharecensuss);
    unset($tokencensus);
    //总点击
    $thirtydaystart=$act['starttime'];
    $thirtydayend=TIMESTAMP;
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
        $date=date('Y-m-d',$thirtydaystart);
        $tokencensus[$date]=pdo_fetchcolumn("select count(distinct(oauth_openid)) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} $where and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $fansclickdates=array_keys($tokencensus);
    $fansclickcensuss=array_values($tokencensus);
    $fansclickcensuss=array_map('intval',$fansclickcensuss);
    
    $sharept=$sharesm=$sharelb=$sharedl=$clickpt=$clicksm=$clicklb=$clickdl=0;
    $sharept=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=1");    
    $sharesm=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=2");    
    $sharelb=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=3");
    $sharedl=pdo_fetchcolumn("select count(distinct(oauth_openid)) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and actid={$act['id']}");
    $clickpt=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=1");
    $clicksm=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=2");
    $clicklb=pdo_fetchcolumn("select count(*)  from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} and actid={$act['id']} and sharefrom=3");
    $clickdl=pdo_fetchcolumn("select count(distinct(oauth_openid))  from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} and actid={$act['id']}");
	//$awarded=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and actid={$act['id']}");
	
    
    include $this->template('census');
<?php 

class census extends page{
    public function main ($op='display'){
    global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $this->dojump();
    $actid=$_GPC['actid'];
    if (!empty($actid)){
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
    }else{
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}   order by id desc");
    }
    $act['thumbs']=$_W['attachurl'].$act['thumbs'];
    $act['page']=iunserializer($act['page']);
    parent::$act=$act;
    $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
    $cates_=array_map('array_shift',$cates);
    $cates_=array_values($cates_);
    $str_cates=join(',',$cates_);
    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ({$str_cates}) order by id desc",array(),'id');
    $awards_=array_map('array_shift',$awards);
    $awards_=array_values($awards_);
    $str_awards=join(',',$awards_);
    $tokens=pdo_fetchall("select awardid,title,type,count(id) as totalawarded,sum(redpack) as redpack,sum(credit1) as credit1 from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and awardid in ({$str_awards}) and isgrant=1 and deleted=0 and type!=100 and type!=5 group by awardid",array(),'awardid');
    foreach ($tokens as &$row){
        $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and awardid={$row['awardid']} and isgrant=1 and veriftime!=0 and deleted=0");
    }
    unset($row);
    $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$act['bizid']} order by id desc",array(),'id');
    $grants=pdo_fetchall("select couponid,type,count(id) as totalgrant from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$act['bizid']}  group by couponid",array(),'couponid');
    //var_dump($grants);;
    foreach ($grants as &$row){
        $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and couponid={$row['couponid']} and veriftime!=0 and deleted=0");
    }
    unset($row);
    //扫码统计
    $where.=" and actid={$act['id']} ";
    $thirtydaystart=$act['starttime'];
    $thirtydayend=$act['endtime']>TIMESTAMP?TIMESTAMP:$act['endtime'];
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
        $date=date('Y-m-d',$thirtydaystart);
        $tokencensus[$date]=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} $where and isgrant=1 and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $awardeddates=array_keys($tokencensus);
    $awardedcensuss=array_values($tokencensus);
    $awardedcensuss=array_map('intval',$awardedcensuss);
    unset($tokencensus);
    //分享统计
    
    $thirtydaystart=$act['starttime'];
    $thirtydayend=$act['endtime']>TIMESTAMP?TIMESTAMP:$act['endtime'];
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
        $date=date('Y-m-d',$thirtydaystart);
        $sharecensus[$date]=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} $where and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $sharedates=array_keys($sharecensus);
    $sharecensuss=array_values($sharecensus);
    $sharecensuss=array_map('intval',$sharecensuss);
    unset($tokencensus);
    //朋友圈点击
    $thirtydaystart=$act['starttime'];
    $thirtydayend=$act['endtime']>TIMESTAMP?TIMESTAMP:$act['endtime'];
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
        $date=date('Y-m-d',$thirtydaystart);
        $tokencensus[$date]=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} $where and type=2 and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $timelinedates=array_keys($tokencensus);
    $timelinecensuss=array_values($tokencensus);
    $timelinecensuss=array_map('intval',$timelinecensuss);
    unset($tokencensus);
    //总点击
    $thirtydaystart=$act['starttime'];
    $thirtydayend=$act['endtime']>TIMESTAMP?TIMESTAMP:$act['endtime'];
    while(date('Y-m-d',$thirtydaystart)<=date('Y-m-d',$thirtydayend)){
       $date=date('Y-m-d',$thirtydaystart);
        $tokencensus[$date]=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid} $where and addtime>".strtotime(date('Y-m-d 00:00:00',$thirtydaystart))." and addtime<".strtotime(date('Y-m-d 23:59:59',$thirtydaystart)));
        $thirtydaystart=strtotime('+1 day',$thirtydaystart);
    }
    $fansclickdates=array_keys($tokencensus);
    $fansclickcensuss=array_values($tokencensus);
    $fansclickcensuss=array_map('intval',$fansclickcensuss);
	$token=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$act['id']}");
    $timeline=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid}  and actid={$act['id']} and type=2");
    $fans=pdo_fetchcolumn("select count(distinct(oauth_openid)) from ".tablename('hxq_newywym_click')." where uniacid={$uniacid}  and actid={$act['id']}");
    $share=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_share')." where uniacid={$uniacid}  and actid={$act['id']}");
    $cb=$share*500;
	//$awarded=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and actid={$act['id']}");
	$act['sharetitle']='活动数据统计-'.$act['sharetitle'];
    include $this->template('census');}}

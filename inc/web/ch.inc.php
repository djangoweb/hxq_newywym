<?php 
    require MODULE_ROOT.'/inc/function.php';
	global $_W,$_GPC,$_M;
	$uniacid=$_W['uniacid'];
	$op=in_array($_GPC['op'],array('display','add','delete','qrcode','download','reset'))?$_GPC['op']:'display';
	$op_=in_array($_GPC['op_'],array('txt','zip','url','xls'))?$_GPC['op_']:'';
    //var_dump($activitys);
$actid=$_GPC['actid'];
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
    if (!empty($actid)){
        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
        $cateid=$_GPC['cateid'];
        if (!empty($cateid)){
          $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$actid} and  id=$cateid order by id desc",array(),'id');
        }
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
    }else{
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid}  order by id desc",array(),'id');
    }
    if ($actid){
        $where.=" and actid={$actid} ";
    }
    if (!empty($cateid)){
        $where.=" and cateid={$cateid} ";
    }
	$pagesize=30;
	$page=$_GPC['page']>0?$_GPC['page']:1;
	$total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_ch')." where  uniacid={$uniacid} $where ");
	$start=($page-1)*$pagesize;
	$activitys=pdo_fetchall('select * from '.tablename('hxq_newywym_ch')." where uniacid=$uniacid $where order by id desc limit $start ,$pagesize");
    
	$pager = pagination($total, $page, $pagesize);
	include $this->template('ch');

<?php 
global $_W,$_GPC,$_M;
load()->model('mc');
$uniacid=$_W['uniacid'];
$op=in_array($_GPC['op'],array('display','edit','save','page','savepage','pagestep1','pagestep2'))?$_GPC['op']:'display';
$groups=mc_groups($uniacid);
$bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
if ($op=='edit'){
   if (!empty($_GPC['id'])){
      $activity=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['id']}");
      $activity['starttime']=date('Y-m-d H:i:s',$activity['starttime']);
      $activity['endtime']=date('Y-m-d H:i:s',$activity['endtime']);
      $activity['thumb']=$activity['thumb'];
      $activity['faninfoarr']=iunserializer($activity['faninfoarr']);
      $activity['blacklistgroups']=iunserializer($activity['blacklistgroups']);
      $activity['mycloselinks']=iunserializer($activity['mycloselinks']);
   }
   include $this->template('activityedit');
}
if($op=='save'){
    //var_dump($_GPC['activity']);  
    $activity=$_GPC['activity'];
   if (empty($activity['name'])){
       message('请填写名称');
   }
   if (empty($activity['bizid'])){
       message('请选择商家');
   }
   if (empty($activity['daterange']['start'])||empty($activity['daterange']['end'])){
       message('请选择时间');
   }
   if ($activity['daterange']['start']>=$activity['daterange']['end']){
       message('活动结束时间必须大于开始时间！');
   }
   if (!isset($activity['focusfollow'])){
       $activity['focusfollow']=0;
   }
   if (!is_numeric($activity['lnum'])||$activity['lnum']<0){
       message('每活动粉丝最大扫码次数必须为数字！');
   }
   if (!is_numeric($activity['daymaxawarded'])||$activity['daymaxawarded']<0){
       message('请输入粉丝最大扫码次数(每日)');
   }
   if (!is_numeric($activity['maxawarded'])||$activity['maxawarded']<0){
       message('请输入粉丝最大扫码次数(每活动)');
   }
   if (empty($activity['getfaninfo'])){
       $activity['getfaninfo']=0;
   }
   $activity['starttime']=strtotime($activity['daterange']['start']);
   $activity['endtime']=strtotime($activity['daterange']['end']);
   $activity['faninfoarr']=iserializer($activity['faninfoarr']);
   $activity['blacklistgroups']=iserializer($activity['blacklistgroups']);
   $activity['mycloselinks']=iserializer($activity['mycloselinks']);
   unset($activity['daterange']);
   $activity['uniacid']=$uniacid;
   if (empty($activity['id'])){
       $nameexists=pdo_fetchcolumn('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and name='{$activity['name']}'");
       if ($nameexists){
           message('该活动名称已存在');
       }
       $activity['addtime']=TIMESTAMP;
       $a=pdo_insert('hxq_newywym_activity',$activity);
       message('产品添加成功',$this->createweburl('activity'),'success');
   }else{
       $nameexists=pdo_fetchcolumn('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and name='{$activity['name']}' and id<>{$activity['id']}");
       if ($nameexists){
           message('该活动名称已存在');
       }
       pdo_update('hxq_newywym_activity',$activity,array('id'=>$activity['id']));
       message('产品更新成功',$this->createweburl('activity'),'success');
   }
}
if($op=='display'){
    $total=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid");
    $page=$_GPC['page']?intval($_GPC['page']):1;
    $pagesize=30;
    $pager=pagination($total,$page,$pagesize);
    $activitys=pdo_fetchall('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid");
    foreach ($activitys as $key=>&$row){
        $row['total']=pdo_fetchcolumn('select sum(total) from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and actid={$row['id']}");
        $row['total']=intval($row['total']);
        $row['totalused']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and isgrant=1 and actid={$row['id']}");
        $row['totalused']=intval($row['totalused']);
        $row['starttime']=date('Y-m-d',$row['starttime']);
        $row['endtime']=date('Y-m-d',$row['endtime']);
        $row['faninfoarr']=iunserializer($row['faninfoarr']);
    }
    unset($row);
   include $this->template('activity');
}
if ($op=='pagestep1'){
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['id']}");
    $act['page']=iunserializer($act['page']);
    include $this->template('activity_pagestep1');
}
if ($op=='pagestep2'){
    if (checksubmit('save')){
        $page=$_GPC['page'];
        $template=$_GPC['template'];
        $ay=array('template'=>$template,'pagesetting'=>$page);
        $ay=iserializer($ay);
        pdo_update('hxq_newywym_activity',array('page'=>$ay),array('id'=>$_GPC['id']));
        die(json_encode(error(0,'更新成功')));
    }
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['id']}");
    $act['page']=iunserializer($act['page']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']}",array(),'id');
    //var_dump($act['page']);
    foreach($coupons as $key=>&$row){
        $row['starttime']=date('Y-m-d');
        $row['endtime']=date('Y-m-d');
    }
    unset($row);
    if ($_GPC['template']=='activity_v1'){
        if ($act['page']['template']=='activity_v1'){
            $page=$act['page']['pagesetting'];
        }else{
            $page=array();
        }
        include $this->template('activity_pagestep2_v1');
    }elseif($_GPC['template']=='avtivity_v2'){
        echo 'kkkkkk';
        if ($act['page']['template']=='activity_v2'){
            $page=$act['page']['pagesetting'];
        }else{
            $page=array();
        }
        echo 'kkkkkk';
        include $this->template('activity_pagestep2_v2');
    }elseif ($_GPC['template']=='activity_v3'){
        if ($act['page']['template']=='activity_v3'){
            $page=$act['page']['pagesetting'];
        }else{
            $page=array();
        }
        include $this->template('activity_pagestep2_v3');
    }elseif($_GPC['template']=='activity_v4'){
        if ($act['page']['template']=='activity_v4'){
            $page=$act['page']['pagesetting'];
        }else{
            $page=array();
        }
        include $this->template('activity_pagestep2_v4');
    }
}
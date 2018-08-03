<?php 
global $_W,$_GPC,$_M;
$uniacid=$_W['uniacid'];
$op=in_array($_GPC['op'],array('display','edit','save'))?$_GPC['op']:'display';
if($op=='save'){
   $id=$_GPC['id'];
   $description=$_GPC['description'];
   if (empty($description)){
       message('请填写活动详细');
   }
   $dthumb=$_GPC['dthumb'];
   if (empty($dthumb)){
       message('请上传top图片');
   }
   $jj=$_GPC['jj'];
   if (empty($jj)){
       message('请填写  简介');
   }
   $activity=pdo_fetchall('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id=$id");
   if (empty($activity)){
       message('活动不存在！');
   }

   pdo_update('hxq_newywym_activity',array('description'=>$description,'dthumb'=>$dthumb,'jj'=>$jj),array('id'=>$id));
   message('更新成功',referer(),'success');
}
if($op=='display'){
    $id=$_GPC['id'];
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id=$id");
    if (empty($act)){
        message('活动不存在！');
    }
    $description=$act['description'];
    $dthumb=$act['dthumb'];
    $jj=$act['jj'];
    include $this->template('content');
}
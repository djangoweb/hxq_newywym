<?php 
global $_W,$_GPC,$_M;
$uniacid=$_W['uniacid'];
$op=in_array($_GPC['op'],array('display','edit','save'))?$_GPC['op']:'display';
if($op=='save'){
   $id=$_GPC['id'];
   $hpctnt=$_GPC['hpctnt'];
   if (empty($hpctnt)){
       message('请填写帮助内容');
   }
   $hpthumb=$_GPC['hpthumb'];
   if (empty($hpthumb)){
       message('请上传top图片');
   }
   $activity=pdo_fetchall('select * from '.tablename('hxq_ywym_activity')." where uniacid=$uniacid and id=$id");
   if (empty($activity)){
       message('活动不存在！');
   }
   pdo_update('hxq_ywym_activity',array('hpthumb'=>$hpthumb,'hpctnt'=>$hpctnt),array('id'=>$id));
   message('更新成功',referer(),'success');
}
if($op=='display'){
    $id=$_GPC['id'];
    $act=pdo_fetch('select * from '.tablename('hxq_ywym_activity')." where uniacid=$uniacid and id=$id");
    if (empty($act)){
        message('活动不存在！');
    }
    $hpctnt=$act['hpctnt'];
    $hpthumb=$act['hpthumb'];
    include $this->template('help');
}
<?php

class activity extends page{
      public function main($op='display'){
        global $_W,$_GPC,$_M;
        load()->model('mc');
        $uniacid=$_W['uniacid'];
        $groups=mc_groups($uniacid);
        if ($op=='edit'){
            if ($_W['role']=='operator'){
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id asc",array(),'id');
            }elseif ($_GPC['bizid']){
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$_GPC['bizid']} order by id asc");
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid}  order by id asc");
            }else{
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid}  order by id asc");
            }
           if (!empty($_GPC['id'])){
              $activity=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['id']}");
              if ($_W['role']=='operator'){
                  $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$activity['bizid']} order by id asc");
                  if ($biz['uid']!=$_W['uid']){
                      message('非法操作');
                  }
              }
              $activity['starttime']=date('Y-m-d H:i:s',$activity['starttime']);
              $activity['endtime']=date('Y-m-d H:i:s',$activity['endtime']);
              $activity['thumb']=$activity['thumb'];
              $activity['faninfoarr']=iunserializer($activity['faninfoarr']);
              $activity['blacklistgroups']=iunserializer($activity['blacklistgroups']);
              $activity['mycloselinks']=iunserializer($activity['mycloselinks']);
           }
           include $this->template('activity/activityedit');
           exit;
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
               message('每活动粉丝最大中奖次数必须为数字！');
           }
           if (!is_numeric($activity['daymaxawarded'])||$activity['daymaxawarded']<0){
               message('请输入粉丝最大中奖次数(每日)');
           }
           if (!is_numeric($activity['maxawarded'])||$activity['maxawarded']<0){
               message('请输入粉丝最大中奖次数(每活动)');
           }
           if (!is_numeric($activity['maxawarded'])||$activity['maxawarded']<0){
               message('请输入粉丝最大扫码次数(每活动)');
           }
           if (empty($activity['getfaninfo'])){
               $activity['getfaninfo']=0;
           }
           if (empty($activity['titleshowtype'])){
               $activity['titleshowtype']=0;
           }

           if ($_W['role']=='operator'){
               $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id='{$activity['bizid']}'");
               if ($biz['uid']!=$_W['uid']){
                   message('非法操作');
               }
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
               message('产品添加成功',weburl('activity'),'success');
           }else{
               $nameexists=pdo_fetchcolumn('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and name='{$activity['name']}' and id<>{$activity['id']}");
               if ($nameexists){
                   message('该活动名称已存在');
               }
               pdo_update('hxq_newywym_activity',$activity,array('id'=>$activity['id']));
               message('产品更新成功',weburl('activity'),'success');
           }
        }
        if($op=='display'){
            if ($_W['role']=='operator'){
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id asc");
                $where=" and bizid={$biz['id']}";
            }else{
                $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid",array(),'id');
                if ($_GPC['bizid']){
                    $where=" and bizid={$_GPC['bizid']}";
                }
            }
            $total=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid $where");
            $page=$_GPC['page']?intval($_GPC['page']):1;
            $pagesize=30;
            $pager=pagination($total,$page,$pagesize);
            $activitys=pdo_fetchall('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid $where");
            foreach ($activitys as $key=>&$row){
                $row['total']=pdo_fetchcolumn('select sum(total) from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and actid={$row['id']}");
                $row['total']=intval($row['total']);
                $row['totalused']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and isgrant=1 and actid={$row['id']}");
                $row['totalused']=intval($row['totalused']);
                $row['starttime']=date('Y-m-d',$row['starttime']);
                $row['endtime']=date('Y-m-d',$row['endtime']);
                $row['faninfoarr']=iunserializer($row['faninfoarr']);
                $biz=pdo_fetch('select name,id from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$row['bizid']}");
                $row['bizname']=$biz['name'];
                $row['bizid']=$biz['id'];
            }
            unset($row);
           include $this->template('activity/activity');
        }
        if ($op=='pagestep1'){ 
                if ($_W['role']=='operator'){
                    $activity=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id='{$_GPC['id']}'");
                    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$activity['bizid']}");
                    if ($biz['uid']!=$_W['uid']){
                        message('非法操作');
                    }
                }
            $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['id']}");
            $act['page']=iunserializer($act['page']);
            include $this->template('activity/activity_pagestep1');
        }
        if ($op=='pagestep2'){
            if ($_W['role']=='operator'){
                $activity=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id='{$_GPC['id']}'");
                $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id='{$activity['bizid']}'");
                if ($biz['uid']!=$_W['uid']){
                    message('非法操作');
                }
            }
            if (checksubmit('save')){
                $page=$_GPC['page'];
                foreach($page['desc'] as $k=>&$v){
                    if ($v['type']=='video'||$v['type']=='audio'){
                        if (strpos($v['value'],'http')){
                            
                        }else{
                            $v['value']=$_W['attachurl'].$v['value'];
                        }
                    }
                }
                unset($v);
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
                include $this->template('activity/activity_pagestep2_v1');
            }elseif($_GPC['template']=='activity_v2'){
                if ($act['page']['template']=='activity_v2'){
                    $page=$act['page']['pagesetting'];
                }else{
                    $page=array();
                }
                include $this->template('activity/activity_pagestep2_v2');
            }elseif ($_GPC['template']=='activity_v3'){
                if ($act['page']['template']=='activity_v3'){
                    $page=$act['page']['pagesetting'];
                }else{
                    $page=array();
                }
                include $this->template('activity/activity_pagestep2_v3');
            }elseif($_GPC['template']=='activity_v4'){
                if ($act['page']['template']=='activity_v4'){
                    $page=$act['page']['pagesetting'];
                }else{
                    $page=array();
                }
                include $this->template('activity/activity_pagestep2_v4');
            }
        }
     }
     
     function getcoupon(){
         global $_W,$_GPC,$_M;
         $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
         $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
         $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and isshow=1 and bizid={$biz['id']} and endtime>unix_timestamp()");
         if (empty($coupons)){
             $msg=error(1,'参数错误');
             die(json_encode($msg));
         }
         foreach($coupons as $key=>&$row){
             $row['starttime']=date('Y-m-d');
             $row['endtime']=date('Y-m-d');
         }
         unset($row);
         $msg=array('errno'=>0,'message'=>'成功','coupons'=>$coupons) ;
         die(json_encode($msg));
         exit;
     }
     
}

<?php 
class award extends page{ 
    public function main($op='display'){
        global $_W,$_GPC,$_M;
        $uniacid=$_W['uniacid'];
	    $op=in_array($op,array('display','edit','save','delete','sort','coupon','disable'))?$op:'display';
        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
        //var_dump($coupons);
        //var_dump($cate);
       //var_dump($cateid);
    if ($op=='disable'){
        $id=intval($_GPC['id']);
        $award=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where uniacid=$uniacid and  id={$id}");
        pdo_query("update ".tablename('hxq_newywym_award')." set disable=mod(disable+1,2) where id={$id}");
        message('操作成功！',referer(),'success');
    }elseif ($op=='edit'){
        $cateid=$_GPC['cateid'];
        $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$cateid}");
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$cate['bizid']}");

        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
        $id=intval($_GPC['id']);
        if (!empty($id)){
            $award=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where id={$id} and uniacid={$uniacid}");
            $award['verifers']=@iunserializer($award['verifers']);
            $award['starttime']=date('Y-m-d H:i:s',$award['starttime']);
            $award['endtime']=date('Y-m-d H:i:s',$award['endtime']);
        }
        //var_dump($award);
        if (!empty($award)){
            $totalrate=pdo_fetchcolumn("select sum(rate) from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']}  and disable=0 and id<>{$award['id']}");
        }else{
            $totalrate=pdo_fetchcolumn("select sum(rate) from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']}  and disable=0 ");
        }
        $totalrate=intval($totalrate);
        load()->func('tpl');
        include $this->template('award/awardedit');
        exit;
    }elseif ($op=='save'){
            $award=$_GPC['award'];
            $cateid=$_GPC['cateid'];
            $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid<>0  order by maxtk desc",array(),'id');
            $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$cateid}");
            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
            if($award['type']==5){
                if (empty($award['couponid'])){
                    message('请选择优惠券');
                }
                $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and id={$award['couponid']}");
                $award['title']=$coupon['name'];
                /*               if ($award['verifway']==1){
                 if (empty($award['verifers'])){
                 message('您选择核销，请选择至少一个门店');
                 }
                 }
                $award['verifers']=iserializer($award['verifers']); */
            }
            if (empty($award['title'])){
                message('请输入奖品名称');
            }/* 
            if (!is_numeric($award['rate'])||$award['rate']<0){
                message('请输入奖品中奖几率');
            } */
            if ($award['type']!=100&&(!is_numeric($award['total'])||$award['total']<0)){
                message('请输入奖品份数');
            }
            if ($award['type']==4&&empty($award['thumb'])){
                message('实物奖品请上传奖品图片');
            }
            if($award['type']==1){
                if ($act['rptype']==0){
                    if (!is_numeric($award['redpack'])||$award['redpack']<1){
                        message('直发模式下红包金额必须大于1元');
                    }   
                }else{
                    if (!is_numeric($award['redpack'])||$award['redpack']<=0){
                        message('红包金额必须大于0元');
                    }
                }
            }if($award['type']==2){
                if (!is_numeric($award['redpack'])||$award['redpack']<3){
                    message('裂变红包金额至少3元。');
                }
                if (!is_numeric($award['total_num'])||$award['total_num']<3){
                    message('裂变红包裂变人数至少3人。');
                }
                if ($award['redpack']/$award['total_num']<1){
                    message('裂变红包金额除以裂变人数的值必须大于1');
                }
            }elseif($award['type']==3){
                if (!$act['focusfollow']){
                    message('活动必须要勾选强制关注，才能添加积分类型的奖品');
                }
                if (!is_numeric($award['credit1'])||$award['credit1']<1){
                    message('积分必须大于0');
                }
                $award['credit1']=intval($award['credit1']);
                
            }elseif($award['type']==4){
  /*               if ($award['verifway']==1){
                    if (empty($award['verifers'])){
                        message('您选择核销，请选择至少一个门店');
                    }
                }
                $award['verifers']=iserializer($award['verifers']); */
            }
            $award['cateid']=$cateid;
            if (!empty($award['id'])){
                /* $totalrate=pdo_fetchcolumn("select sum(rate) from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0 and id<>{$award['id']}");
                $disable=pdo_fetchcolumn("select disable from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']}  and id={$award['id']}");
                if (!$disable){
                    if ($totalrate+$award['rate']>10000){
                        message('奖品总获奖几率不能大于10000');
                    }
                } */
                $result=pdo_update('hxq_newywym_award',$award,array('id'=>$award['id']));
            }else{
                /* $totalrate=pdo_fetchcolumn("select sum(rate) from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0");
                if ($totalrate+$award['rate']>10000){
                    message('奖品总获奖几率不能大于10000');
                } */
                $award['addtime']=TIMESTAMP;
                $award['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_award',$award);
            }
            if ($result){
                message('更新成功',weburl('award',array('cateid'=>$cate['id'])),'success');
            }
            else{
                message('更新失败','referer','error');
            }
        }elseif($op=='delete'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            if (pdo_delete('hxq_newywym_award',array('id'=>$id))){
                message('删除成功',referer(),'success');
            }else{
                message('奖品不存在！');
            }
        }
	    }
	    $actid=$_GPC['actid'];
	    $cateid=$_GPC['cateid'];
	    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
        $bizids=array_keys($bizs);
        $strbizids=join(',',$bizids);
        $where=" and bizid in ($strbizids)";
        if ($cateid){
           $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where id={$cateid} $where order by id desc");
           if (empty($cate)){
               message('权限不足');
           }
           if ($cate['actid']){
               $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where actid={$cate['actid']}  order by id desc",array(),'id');
               $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where id={$cate['actid']} order by id desc");
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$cate['bizid']} order by id desc",array(),'id');
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$cate['bizid']} order by id desc");
           }else{
               $cates=array($cate);
               if ($cate['bizid']){
                   $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$cate['bizid']} order by id desc");
                   $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$cate['bizid']} order by id desc",array(),'id');
               }
           }
        }elseif($actid){
           $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where id={$actid} $where order by id desc");
           if (empty($act)){
               message('权限不足');
           }
           $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$act['bizid']} order by id desc",array(),'id');
           $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where actid={$act['id']} order by id desc",array(),'id');
           $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where actid={$act['id']} order by id desc");
           if (empty($cate)){
               message('请先添加码段再管理奖品');
           }
        }else{
           $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid $where order by id desc");
           if (empty($cate)){
               message('请先码段绑定到商家再管理奖品');
           }
           if ($cate['actid']){
               $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where actid={$cate['actid']} order by id desc",array(),'id');
               $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where id={$cate['actid']} order by id desc");
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$cate['bizid']} order by id desc",array(),'id');
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$cate['bizid']} order by id desc");
           }else{
               $cates=array($cate);
               if ($cate['bizid']){
                   $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$cate['bizid']} order by id desc");
                   $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$cate['bizid']} order by id desc",array(),'id');
               }
           }
        }
        $where=" and cateid=".$cate['id'];
	    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} $where order by id asc");
	    $minrateaward=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} $where order by rate desc");
	    $totalaward=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} $where and disable=0 ");
	    foreach($awards as $key=>&$row){
	        $row['awarded']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and cateid={$cate['id']}  and awardid={$row['id']}  ");
	        if ($row['disable']==0&&$row['awarded']<$row['total']){
	            $totalrate+=$row['total'];
	        }
	    }
	    unset($row);
        foreach($awards as $key=>&$row){
	        //echo $row['id'];
	        $row['awarded']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and cateid={$cate['id']}  and awardid={$row['id']}  ");
	        if ($row['awarded']<$row['total']&&$row['disable']==0){
	            $row['ratepercent']=($row['total']/$totalrate);
	        }else{
	            $row['ratepercent']='0';
	        }
	        $row['ratepercent']=floor(floor($row['ratepercent']*10000))/100;
	        
	    }
	   unset($row);
	    include $this->template('award/awardmanage');
	    //
}}
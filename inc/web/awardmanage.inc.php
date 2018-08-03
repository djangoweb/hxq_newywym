<?php 
global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    if (!empty($_GPC['uniacid_'])&&$uniacid!=$_GPC['uniacid_']){
	        load()->model('module');
	        load()->model('account');
	        module_save_switch($this->modulename, $_GPC['uniacid_']);
	        $uniacid = intval($_GPC['uniacid_']);
	        $role = uni_permission($_W['uid'], $uniacid);
	        if(empty($role)) {
	            itoast('操作失败, 非法访问.', '', 'error');
	        }
	        uni_account_save_switch($uniacid);
	        uni_account_switch($uniacid,url('site/entry/awardmanage',array('m'=>$this->modulename,'cateid'=>$_GPC['cateid'])),'success');
	    }
	    $op=in_array($_GPC['op'],array('display','edit','save','delete','sort','coupon','disable'))?$_GPC['op']:'display';
        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and enable=1 and endtime>unix_timestamp() order by id desc",array(),'id');
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
        include $this->template('awardedit');
        exit;
    }elseif ($op=='save'){
            $awards=$_GPC['awards'];
            $ids='0';
            foreach($awards as $award){
                if ($award['id']){
                    $ids.=','.$award['id'];
                }
            }
            pdo_query('delete from '.tablename('hxq_newywym_award')." where uniacid=$uniacid and cateid={$_GPC['cateid']} and id not in ($ids)");
            $dir='images/'.$_W['uniacid'].'/'.date('Y').'/'.date('m').'/';
            mkdirs(ATTACHMENT_ROOT.$dir);
            foreach($awards as $award){
                if (empty($award['thumb'])){
                $thumb=$dir.random(30).'.jpg';
                $award['thumb']=$thumb;
                copy(MODULE_ROOT.'/images/hbxr2.png',ATTACHMENT_ROOT.$thumb);
                }
                $data=array(
                    'uniacid'=>$uniacid,
                    'cateid'=>$_GPC['cateid'],
                    'title'=>$award['name'],
                        'thumb'=>$award['thumb'],
                        'type'=>$award['type'],
                        'couponid'=>$award['couponid'],
                        'redpack'=>$award['redpack'],
                    'credit1'=>$award['credit1'],
                        'total'=>$award['total'],
                    'tokenids'=>$award['tokenids'],
                    );
                if ($award['id']){
                    pdo_update('hxq_newywym_award',$data,array('id'=>$award['id']));
                }else{
                    $data['addtime']=TIMESTAMP;
                    pdo_insert('hxq_newywym_award',$data);
                }
            }
            message('更新成功',referer(),'success');
    }elseif($op=='delete'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            if (pdo_delete('hxq_newywym_award',array('id'=>$id))){
                message('删除成功','referer','success');
            }else{
                message('奖品不存在！');
            }
        }
	}
	    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
	    $actid=$_GPC['actid'];
	    $cateid=$_GPC['cateid'];
	    //error_reporting(E_ALL);
	    if ($cateid){
	        $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and id=$cateid order by id desc");
	        if ($cate['actid']){
	            $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$cate['actid']} order by id desc",array(),'id');
	            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']} order by id desc");
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid  order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
	        }else{
	            $cates[$cate['id']]=$cate;
	        }
	    }elseif ($actid){
	        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id=$actid order by id desc");
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid  order by id desc",array(),'id');
	        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and actid={$act['id']} order by id desc",array(),'id');
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
	        $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and actid={$act['id']} order by id desc");         
	    }else{
	        $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} order by id desc");
	        if ($cate['actid']){
	            $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$cate['actid']} order by id desc",array(),'id');
	            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']} order by id desc");
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
	        }else{
	            $cates[$cate['id']]=$cate;
	        }
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid order by id desc",array(),'id');
	    }
	    if (empty($cate)){
	        message('码段不存在，请先添加');
	    }
	    if ($cate){
	        $where.=" and cateid={$cate['id']} ";
	    }
	    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where cateid={$cate['id']} and uniacid={$uniacid} order by id asc");
	    $totalqr=pdo_fetchcolumn('select sum(total) from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$cate['id']}");
	    $totalqr=intval($totalqr);
	    $totalrate=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} $where and disable=0 order by id asc");
	    $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and enable=1 and endtime>unix_timestamp() and bizid={$biz['id']} order by id asc",array(),'id');
	    foreach($awards as $key=>&$row){
	        //echo $row['id'];
	        $row['awarded']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and cateid={$cate['id']}  and awardid={$row['id']}  ");
	        if ($row['disable']==0){
	            $row['ratepercent']=($row['total']/$totalrate);
	        }else{
	            $row['ratepercent']='0';
	        }
	        $row['ratepercent']=floor(floor($row['ratepercent']*10000))/100;
	        
	    }
	   unset($row);
	    include $this->template('awardedit');
	    //
	
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
	$op=in_array($_GPC['op'],array('display','edit','save','delete','disable','coupongrant','couponverif'))?$_GPC['op']:'display';
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
    //var_dump($cate);
       //var_dump($cateid);
    
    if ($op=='disable'){
        $id=intval($_GPC['id']);
        pdo_query("update ".tablename('hxq_newywym_coupon')." set disabled=mod(disabled+1,2) where id={$id}");
        message('操作成功！',referer(),'success');
    }elseif ($op=='edit'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$id} and uniacid={$uniacid}");
        }
        load()->func('tpl');
        include $this->template('couponedit');
        exit;
    }elseif ($op=='save'){
            $coupon=$_GPC['coupon'];
            if (empty($coupon['bizid'])){
                message('请选择商家');
            }
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']}");
            $coupon['biz']=$biz['name'];
            if (empty($coupon['bizid'])){
                message('请选择商家');
            }
            if (empty($coupon['name'])){
                message('请输入优惠券名称');
            }
            if (empty($coupon['coupontype'])){
                message('请输入优惠券类型');
            }
            if (empty($coupon['requirement'])){
                message('请输入优惠条件');
            }
            if (empty($coupon['logo'])){
                message('请上传logo');
            }
            if (empty($coupon['phone'])){
                message('请输入电话');
            }
            if (empty($coupon['style'])){
                message('请输入颜色');
            }
            if (empty($coupon['address'])){
                message('请输入地址');
            }
            if (empty($coupon['daterange']['start'])){
                message('请选择开始时间');
            }
            if (empty($coupon['daterange']['end'])){
                message('请选择结束时间');
            }
            if (empty($coupon['description'])){
                message('请输入使用说明');
            }
            $coupon['starttime']=strtotime($coupon['daterange']['start']);
            $coupon['endtime']=strtotime($coupon['daterange']['end']);
            unset($coupon['daterange']);
            if (!empty($coupon['id'])){
               
                $result=pdo_update('hxq_newywym_coupon',$coupon,array('id'=>$coupon['id']));
            }else{
                
                $coupon['addtime']=TIMESTAMP;
                $coupon['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_coupon',$coupon);
            }
            if ($result){
                message('更新成功',$this->createweburl('coupon',array('cateid'=>$cate['id'])),'success');
            }
            else{
                message('更新失败','referer','error');
            }
        }elseif($op=='delete'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            if (pdo_delete('hxq_newywym_coupon',array('id'=>$id))){
                message('删除成功','referer','success');
            }else{
                message('奖品不存在！');
            }
        }
	    }elseif($op=='display'){
            if (!$_GPC['bizid']){
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc");
            }else{
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
            }
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} order by id asc");
            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} order by id asc limit $start,$pagesize");
            $pager = pagination($total, $page,30);
    	    include $this->template('coupon');
	    }elseif($op=='coupongrant'){
	        if (!$_GPC['bizid']){
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
	        }else{
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc",array(),'id');
	        }
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid}  order by id asc",array(),'id');
            if ($_GPC['couponid']){
	            $where=" and couponid={$_GPC['couponid']}";
	        }
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']} {$where}  order by id desc ");
	        $coupongrants=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']} $where  order by id desc limit $start,$pagesize");
	        $pager = pagination($total, $page, 30);
	        include $this->template('coupongrant');
	    }elseif($op=='couponverif'){
	        if (!$_GPC['bizid']){
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
	        }else{
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc",array(),'id');
	        }
	        $where.=" and bizid={$biz['id']}";
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} order by id asc",array(),'id');
	        if ($_GPC['couponid']){
	            $where.=" and couponid={$_GPC['couponid']}";
	        }
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0 $where order by id desc");
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $couponverifs=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize");
	        //echo "select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize";
	        $pager = pagination($total, $page, 30);
	        include $this->template('couponverif');
	    }
	    //
	
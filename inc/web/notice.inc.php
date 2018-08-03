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
	        uni_account_switch($uniacid,url('site/entry/notice',array('m'=>$this->modulename)),'success');
	    }
	 $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
    //var_dump($cate);
       //var_dump($cateid);       if ($_GPC['actid']){            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");            $where=" and actid={$_GPC['actid']}";       }            //var_dump($_GPC['disable']);
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_noticelog')." where uniacid={$uniacid} $where order by id asc");            $notices=pdo_fetchall("select * from ".tablename('hxq_newywym_noticelog')." where uniacid={$uniacid} $where order by id asc limit $start,$pagesize");            ///echo "select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc limit $start,$pagesize";            //
            $pager = pagination($total, $page,30);
    	    include $this->template('notice');
	    
	    //
	
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
	$op=in_array($_GPC['op'],array('display','edit','save','delete','disable','deletegrant','productgrant','productverif','productverifexport'))?$_GPC['op']:'display';
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
    //var_dump($cate);
       //var_dump($cateid);
    
    if ($op=='edit'){
        $id=intval($_GPC['id']);
        
        if (!empty($id)){
            $product=pdo_fetch("select * from ".tablename('hxq_newywym_product')." where id={$id} and uniacid={$uniacid}");
        }else{
        include $this->template('productedit');
        exit;
    }elseif ($op=='save'){
            $product=$_GPC['product'];
            if (empty($product['bizid'])){
                message('请选择商家');
            }
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$product['bizid']}");
            $product['bizname']=$biz['name'];
            if (empty($product['name'])){
                message('请输入产品名称');
            }
            if (empty($product['thumb'])){
                message('请上传缩略图');
            }
            if (empty($product['description'])){
                message('请输入产品说明');
            }
            if (!empty($product['id'])){
            }else{
                $product['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_product',$product);
            }
            if ($result){
                message('更新成功',$this->createweburl('product',array('bizid'=>$product['bizid'])),'success');
            }
            else{
                message('更新失败','referer','error');
            }
        }elseif($op=='delete'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            if (pdo_delete('hxq_newywym_product',array('id'=>$id))){
                message('删除成功','referer','success');
            }else{
                message('奖品不存在！');
            }
        }
	    }elseif($op=='display'){
            if (!$_GPC['bizid']){
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
            if(strlen($_GPC['disabled'])>0){
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_product')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc");
            $products=pdo_fetchall("select * from ".tablename('hxq_newywym_product')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc limit $start,$pagesize");
            $pager = pagination($total, $page,30);
    	    include $this->template('product');
	    }
	    //
	
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
        if ($op=='disable'){            $id=intval($_GPC['id']);            pdo_query("update ".tablename('hxq_newywym_product')." set 	disabled=mod(disabled+1,2) where id={$id}");            message('操作成功！',referer(),'success');        }
    if ($op=='edit'){
        $id=intval($_GPC['id']);        if (empty($id)&&!$_GPC['bizid']){            message('请从商家管理页面进入产品管理');        }       
        
        if (!empty($id)){
            $product=pdo_fetch("select * from ".tablename('hxq_newywym_product')." where id={$id} and uniacid={$uniacid}");            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$product['bizid']}  order by id desc");            
        }else{            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");        }        //var_dump($product);        load()->func('tpl');
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
            if (!empty($product['id'])){                $result=pdo_update('hxq_newywym_product',$product,array('id'=>$product['id']));
            }else{                $product['addtime']=TIMESTAMP;
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
            if (!$_GPC['bizid']){                message('请从商家管理页面进入产品管理');            }
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
            if(strlen($_GPC['disabled'])>0){                $where=" and disabled=".$_GPC['disabled'];            }            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_product')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc");
            $products=pdo_fetchall("select * from ".tablename('hxq_newywym_product')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc limit $start,$pagesize");            foreach($products as &$row){                $row['description']=mb_substr(strip_tags(htmlspecialchars_decode($row['description'])),0,10);            }            unset($row);
            $pager = pagination($total, $page,30);
    	    include $this->template('product');
	    }
	    //
	
<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $op=in_array($_GPC['op'],array('display'))?$_GPC['op']:'display';
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");

	    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    $id=intval($_GPC['id']);
	    if (empty($id)){
	        message('请指定产品ID！');
	    }
	    $pdt=pdo_fetch('select * from '.tablename('hxq_newywym_product')." where uniacid={$_W['uniacid']} and id={$id}");
	    if (empty($pdt)){
	        message('找不到产品！');
	    }
	    pdo_update('hxq_newywym_product',array('click'=>$pdt['click']+1),array('id'=>$pdt['id']));
	    $pdt['addtime']=date('Y-m-d',$pdt['addtime']);
	    $pdt['description']=htmlspecialchars_decode($pdt['description']);
	    include $this->template('product');

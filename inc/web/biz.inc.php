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
	        uni_account_switch($uniacid,url('site/entry/biz',array('m'=>$this->modulename)),'success');
	    }
	$op=in_array($_GPC['op'],array('display','edit','save','delete','disable','getcoupon','getproduct','savecoupons','saveproducts','verifer','delvfer'))?$_GPC['op']:'display';
        //var_dump($cate);
       //var_dump($cateid);
 // echo $op;
   if ($op=='getcoupon'){
       $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['bizid']}");
       $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and  bizid={$biz['id']} and endtime>unix_timestamp() order by id desc");
       if (empty($coupons)){
           $msg=error(1,'参数错误') ;
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
   if ($op=='getproduct'){
       $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['bizid']}");
       $products=pdo_fetchall("select * from ".tablename('hxq_newywym_product')." where uniacid={$_W['uniacid']} and bizid={$biz['id']} order by id desc");
       if (empty($products)){
           $msg=error(1,'参数错误') ;
           die(json_encode($msg));
       }
       unset($row);
       $msg=array('errno'=>0,'message'=>'成功','products'=>$products) ;
       die(json_encode($msg));
       exit;
   }
   if ($op=='savecoupons'){
       $coupon=$_GPC['coupon'];
       if (empty($coupon)){
           $msg=error(1,'参数错误') ;
           die(json_encode($msg));
       }
       $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$_GPC['bizid']} and uniacid={$uniacid} and endtime>unix_timestamp()");
       foreach($coupons as $row){
           if (in_array($row['id'],$coupon)){
               pdo_update('hxq_newywym_coupon',array('isshow'=>1),array('id'=>$row['id']));
           }else{
               pdo_update('hxq_newywym_coupon',array('isshow'=>0),array('id'=>$row['id']));
           }
       }
       $msg=array('errno'=>0,'message'=>'成功') ;
       die(json_encode($msg));
       exit;
   }
   if ($op=='saveproducts'){
       $product=$_GPC['product'];
       if (empty($product)){
           $msg=error(1,'参数错误') ;
           die(json_encode($msg));
       }
       $products=pdo_fetchall("select * from ".tablename('hxq_newywym_product')." where bizid={$_GPC['bizid']} and uniacid={$uniacid}");
      // echo "select * from ".tablename('hxq_newywym_product')." where bizid={$_GPC['bizid']} and uniacid={$uniacid}";
       foreach($products as $row){
           if (in_array($row['id'],$product)){
               pdo_update('hxq_newywym_product',array('isshow'=>1),array('id'=>$row['id']));
           }else{
               pdo_update('hxq_newywym_product',array('isshow'=>0),array('id'=>$row['id']));
           }
       }
       $msg=array('errno'=>0,'message'=>'成功') ;
       die(json_encode($msg));
       exit;
   }
   if ($op=='edit'){
        //var_dump($act);
        $id=intval($_GPC['id']);
        if (!empty($id)){
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$id} and uniacid={$uniacid}");

            $biz['thumbs']=iunserializer($biz['thumbs']);
        }
        //var_dump($award);
        load()->func('tpl');
        include $this->template('bizedit');
        exit;
    }elseif ($op=='save'){
            $biz=$_GPC['biz'];
            if (empty($biz['indstp'])){
                message('请选大类');
            }
            if (empty($biz['indstc'])){
                message('请选小类');
            }
            if (empty($biz['name'])){
                message('请输入商家名称');
            }
            if (empty($biz['thumb'])){
                message('请上传缩略图');
            }
            if (empty($biz['biz_hours'])){
                message('请输入营业时间');
            }
            if (empty($biz['phone'])){
                message('请输入联系电话');
            }
            if (empty($biz['address'])){
                message('请输入商家地址');
            }
            if (empty($biz['description'])){
                message('请填写描述');
            }
            $biz['thumbs']=iserializer($biz['thumbs']);
            if (!empty($biz['id'])){
                $result=pdo_update('hxq_newywym_biz',$biz,array('id'=>$biz['id']));
            }else{
                $biz['addtime']=TIMESTAMP;
                $biz['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_biz',$biz);
            }
            if ($result){
                message('更新成功',$this->createweburl('biz'),'success');
            }else{
                message('更新失败','referer','error');
            }
    }elseif($op=='delete'){
        $id=intval($_GPC['id']);
        if (!empty($id)){
            if (pdo_delete('hxq_newywym_biz',array('id'=>$id))){
                message('删除成功','referer','success');
            }else{
                message('奖品不存在！');
            }
        }
	}elseif($op=='display'){
        $page=$_GPC['page']>0?$_GPC['page']:1;
        $start=($page-1)*30;
        $pagesize=30;
        $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id asc");
        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id asc limit $start,$pagesize");
        foreach($bizs as &$row){
            $row['totalpdt']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_product')." where bizid={$row['id']} and uniacid={$uniacid} order by id asc");
            $row['totalcoupon']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupon')." where bizid={$row['id']} and uniacid={$uniacid} and enable=1 order by id asc");
            $row['totalcouponon']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupon')." where bizid={$row['id']} and uniacid={$uniacid} and enable=1 and isshow=1 order by id asc");
        }
        unset($row);
        $pager = pagination($total, $page,30);
	    include $this->template('biz');
	}

	if ($op=='delvfer'){
	    $id=intval($_GPC['id']);
	        if (pdo_delete('hxq_newywym_verifer',array('id'=>$id))){
	            message('删除成功','referer','success');
	        }else{
	            message('核销员！');
	        }
	}
	if ($op=='verifer'){
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start=($page-1)*30;
	    $pagesize=30;
	    $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_verifer')." where  uniacid={$uniacid} and bizid={$_GPC['bizid']} order by id asc");
	    //echo "select count(*) from ".tablename('hxq_newywym_verifer')." where  uniacid={$uniacid} and bizid={$_GPC['bizid']} order by id asc";
	    
	    $verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and bizid={$_GPC['bizid']} order by id asc limit $start,$pagesize");
	    foreach($verifers as &$row){
	        $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where bizid={$_GPC['bizid']} and uniacid={$uniacid} and veriferid={$row['id']} order by id asc");
	    }
	    unset($row);
	    $pager = pagination($total,$page,30);
	    include $this->template('verifer');
	}
	    //
	
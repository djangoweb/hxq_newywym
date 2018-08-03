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
	$op=in_array($_GPC['op'],array('display','edit','save','delete','enable','isshow','deletegrant','coupongrant','couponverif','couponverifexport'))?$_GPC['op']:'display';
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
    //var_dump($cate);
       //var_dump($cateid);
    if ($op=='enable'){
        $id=intval($_GPC['id']);
        pdo_query("update ".tablename('hxq_newywym_coupon')." set 	enable=mod(enable+1,2) where id={$id}");
        message('操作成功！',referer(),'success');
    }elseif ($op=='isshow'){        $id=intval($_GPC['id']);        pdo_query("update ".tablename('hxq_newywym_coupon')." set 	isshow=mod(isshow+1,2) where id={$id}");        message('操作成功！',referer(),'success');    }elseif ($op=='edit'){
        $id=intval($_GPC['id']);
        if (empty($id)&&empty($_GPC['bizid'])){
           message('请从商家管理页面进入优惠券管理');
        }        if (!empty($id)){            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$id}");            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']}");        }else{            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}");        }
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
            if (empty($coupon['name'])){
                message('请输入优惠券名称');
            }
            if (empty($coupon['requirement'])){
                message('请输入优惠条件');
            }            
            if (empty($coupon['type'])){
                message('请输入优惠券类型');
            }
            if (empty($coupon['value'])){
                message('请输入电话');
            }
            if (empty($coupon['style'])){
                message('请输入颜色');
            }
            if (empty($coupon['daterange']['start'])){
                message('请选择开始时间');
            }
            if (empty($coupon['daterange']['end'])){
                message('请选择结束时间');
            }
            $coupon['starttime']=strtotime($coupon['daterange']['start']);
            $coupon['endtime']=strtotime($coupon['daterange']['end']);
            if ($coupon['starttime']>=$coupon['endtime']){
                message('优惠券有效期结束时间必须大于开始时间');
            }
            unset($coupon['daterange']);
            if (!empty($coupon['id'])){
                $result=pdo_update('hxq_newywym_coupon',$coupon,array('id'=>$coupon['id']));
            }else{
                
                $coupon['addtime']=TIMESTAMP;
                $coupon['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_coupon',$coupon);
            }
            if ($result){
                message('更新成功',$this->createweburl('coupon',array('bizid'=>$coupon['bizid'])),'success');
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
	    } elseif($op=='deletegrant'){        $id=intval($_GPC['id']);        if (!empty($id)){            if (pdo_delete('hxq_newywym_coupongrant',array('id'=>$id))){                message('删除成功','referer','success');            }else{                message('奖品不存在！');            }        }	    }elseif($op=='display'){
            if (!$_GPC['bizid']){
                message('请从商家管理页面进入优惠券管理');
            }            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");            //var_dump($_GPC['disable']);            if(strlen($_GPC['disabled'])>0){                $where=" and disabled=".($_GPC['disabled']);            }
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc");            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc limit $start,$pagesize");            ///echo "select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} $where order by id asc limit $start,$pagesize";            //            foreach ($coupons as &$row){                $row['totalgrant']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where couponid={$row['id']} and uniacid={$uniacid} order by id asc");                $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where couponid={$row['id']} and uniacid={$uniacid} and veriftime>0 order by id asc");            }            unset($row);
            $pager = pagination($total, $page,30);
    	    include $this->template('coupon');
	    }elseif($op=='coupongrant'){	        if (!$_GPC['bizid']){	            message('请从商家管理页面进入优惠券管理');	        }
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");           
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid}  order by id asc",array(),'id');
            if ($_GPC['cid']){                $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} and id={$_GPC['cid']} order by id asc",array(),'id');                $where=" and couponid={$_GPC['cid']}";
	        }
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']} {$where}  order by id desc ");
	        $coupongrants=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']} $where  order by id desc limit $start,$pagesize");
	        $pager = pagination($total, $page, 30);
	        include $this->template('coupongrant');
	    }elseif($op=='couponverif'){	        if (!$_GPC['bizid']){	             	            message('请从商家管理页面进入优惠券管理');	             	        }
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc",array(),'id');
	        $verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and bizid={$_GPC['bizid']}  order by id desc",array(),'id');
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} order by id asc",array(),'id');
	        if ($_GPC['cid']){                $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where bizid={$biz['id']} and uniacid={$uniacid} and id={$_GPC['cid']} order by id asc",array(),'id');                $where=" and couponid={$_GPC['cid']}";	        }
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0 $where order by id desc");
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $couponverifs=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize");
	        //echo "select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize";
	        $pager = pagination($total, $page, 30);
	        include $this->template('couponverif');
	    }elseif($op=='couponverifexport'){	        if (!$_GPC['bizid']){	             	            message('请从商家管理页面进入优惠券管理');	             	        }
	        $where.=" and bizid={$_GPC['bizid']}";
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} order by id desc");
            if ($_GPC['cid']){
            $where.=" and couponid={$_GPC['couponid']}";            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$_GPC['couponid']} order by id desc");
            }
	        $name='优惠券核销记录';
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".$name.".xls");
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid=$uniacid ",array(),'id');
	        $sql="select * from ".tablename('hxq_newywym_coupongrant')." where  uniacid={$uniacid} $where and veriferid>0 order by id asc";
	        $awardeds=pdo_fetchall($sql);
	        $strexport="微信名\topenid\t优惠券\t优惠内容\t领取时间\t核销时间\r";
	        foreach ($awardeds as $row){
	            $strexport.=$row['nickname']?$row['nickname']:'匿名';	            $strexport.="\t".$row['oauth_openid'];
	            $strexport.="\t".$coupons[$row['couponid']]['name'];	            $strexport.="\t".$coupons[$row['couponid']]['value'];
	            $strexport.="\t(".date('Y-m-d H:i',$row['addtime']).')';
	            $strexport.="\t(".date('Y-m-d H:i',$row['veriftime']).')';//要改
	            $strexport.="\r";	        
	        }
	            $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
	            exit($strexport);
	    }
	    //
	
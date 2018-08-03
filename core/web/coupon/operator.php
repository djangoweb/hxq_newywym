<?php

class coupon extends page{
	  
	  public function main($op='display'){
	      global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
    if ($op=='disable'){
        $id=intval($_GPC['id']);
        $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$id} and uniacid={$uniacid}");
        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$coupon['bizid']} and uniacid={$uniacid}");
        if ($_W['uid']!=$biz['uid']){
            message('没有权限');
        }
        pdo_query("update ".tablename('hxq_newywym_coupon')." set disabled=mod(disabled+1,2) where id={$id}");
        message('操作成功！',referer(),'success');
    }elseif ($op=='edit'){
        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
        
        $id=intval($_GPC['id']);
        if (!empty($id)){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$id} and uniacid={$uniacid}");
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$coupon['bizid']} and uniacid={$uniacid}");
            if ($_W['uid']!=$biz['uid']){
                message('没有权限');
            }
        }
        load()->func('tpl');
        include $this->template('coupon/couponedit');
        exit;
    }elseif ($op=='save'){
            $coupon=$_GPC['coupon'];
            if (empty($coupon['bizid'])){
                message('请选择商家');
            }
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']}");
            if ($_W['uid']!=$biz['uid']){
                message('没有权限');
            }
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
            $coupon['starttime']=strtotime($coupon['daterange']['start']);
            $coupon['endtime']=strtotime($coupon['daterange']['end']);
            if ($coupon['starttime']>=$coupon['endtime']){
                message('优惠券有效期结束时间必须大于开始时间');
            }
            if (empty($coupon['description'])){
                message('请输入使用说明');
            }
            if (empty($coupon['isshow'])){
                $coupon['isshow']=0;
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
                message('更新成功',weburl('coupon',array('cateid'=>$cate['id'])),'success');
            }
            else{
                message('更新失败',referer(),'error');
            }
        }elseif($op=='delete'){
            $id=intval($_GPC['id']);
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$id} and uniacid={$uniacid}");
            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where id={$coupon['bizid']} and uniacid={$uniacid}");
            if ($_W['uid']!=$biz['uid']){
                message('没有权限');
            }
            if (!empty($id)){
                if (pdo_delete('hxq_newywym_coupon',array('id'=>$id))){
                    message('删除成功',referer(),'success');
                }else{
                    message('奖品不存在！');
                }
            }
	    }elseif($op=='display'){
	        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	        $bizids=array_keys($bizs);
	        $strbizids=join(',',$bizids);
	        $where=" and bizid in ($strbizids)";
	        if ($_GPC['bizid']){
	            $where.=" and bizid={$_GPC['bizid']}";

	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} order by id desc");
	        }
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupon')." where  uniacid={$uniacid} $where order by id asc");
            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} $where order by id asc limit $start,$pagesize");
            foreach($coupons as &$row){
                $row['totalgrant']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where  uniacid={$uniacid} and couponid={$row['id']} order by id asc");
                $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where  uniacid={$uniacid} and couponid={$row['id']} and veriftime>0 order by id asc");
                $row['totalgrant']=number_format($row['totalgrant'],0);
                $row['totalverif']=number_format($row['totalverif'],0);
            }
            unset($row);
            $pager = pagination($total, $page,30);
    	    include $this->template('coupon/coupon');
	    }elseif($op=='coupongrant'){
	        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	        $bizids=array_keys($bizs);
	        $strbizids=join(',',$bizids);
	        $where=" and bizid in ($strbizids)";
	        if ($_GPC['couponid']){
	            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$_GPC['couponid']} order by id desc");
	            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$coupon['bizid']} order by id desc",array(),'id');
	            $where.=" and couponid={$_GPC['couponid']} ";
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']} order by id desc");
	             
	        }elseif ($_GPC['bizid']){ 
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} order by id desc");
	            $where.=" and bizid={$_GPC['bizid']} ";
	            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$_GPC['bizid']} order by id desc",array(),'id');
	            
	        }
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} {$where}  order by id desc ");
	        $coupongrants=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid}  $where  order by id desc limit $start,$pagesize");
	        $pager = pagination($total, $page, 30);
	        include $this->template('coupon/coupongrant');
	    }elseif($op=='couponverif'){
	        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	        $bizids=array_keys($bizs);
	        $strbizids=join(',',$bizids);
	        $where=" and bizid in ($strbizids)";
	        if ($_GPC['couponid']){
	            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$_GPC['couponid']} order by id desc");
	            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$coupon['bizid']} order by id desc",array(),'id');
	            $where.=" and couponid={$_GPC['couponid']} ";
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']} order by id desc");
	             
	        }elseif ($_GPC['bizid']){ 
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} order by id desc");
	            $where.=" and bizid={$_GPC['bizid']} ";
	            $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$_GPC['bizid']} order by id desc",array(),'id');
	            
	        }
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0 $where order by id desc");
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $couponverifs=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize");
	        //echo "select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and veriftime>0  $where order by id desc limit $start,$pagesize";
	        $pager = pagination($total, $page, 30);
	        include $this->template('coupon/couponverif');
	    }elseif($op=='couponverifexport'){
	        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
	        if ($_GPC['couponid']){
	            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$_GPC['couponid']}  order by id desc");
	            $where.=" and couponid={$_GPC['couponid']}";
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$coupon['bizid']}  order by id desc");
	            $coupons=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$coupon['bizid']} order by id desc",array(),'id');
	        }elseif ($_GPC['bizid']){
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
	            $where.=" and bizid={$_GPC['bizid']}";
	            $coupons=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	        }else{
	            $coupons=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} order by id desc",array(),'id');
	        }
	        $name=$biz?$biz['name']:'所有商家';
	        $name.=$coupon?'-'.$coupon['name']:'-所有优惠券';
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".$name.".xls");
	        $sql="select * from ".tablename('hxq_newywym_coupongrant')." where  uniacid={$uniacid} $where  order by id asc";
	        $awardeds=pdo_fetchall($sql);
	        $strexport="微信名\t优惠券\t领取时间\t核销时间\r";
	        foreach ($awardeds as $row){
	            $strexport.=$row['nickname']?$row['nickname']:'匿名';
	            $strexport.="\t".$coupons[$row['couponid']]['name'];
	            $strexport.="\t".date('Y-m-d H:i',$row['addtime']);
	            $strexport.="\t".(empty($row['veriftime'])?'未核销':date('Y-m-d H:i',$row['veriftime']));//要改
	             
	            $strexport.="\r";	        
	        }
            $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
            exit($strexport);
	    }elseif ($op=='getcoupon'){
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	        $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$biz['id']}");
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
	    //}
}}

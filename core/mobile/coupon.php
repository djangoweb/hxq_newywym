<?php

class coupon extends page{
    public function main ($op='display'){
	    global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
	    $op=in_array($op,array('display','get','check','getwxcoupon','verif'))?$op:'display';
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $act['faninfoarr']=iunserializer($act['faninfoarr']);
	    $act['mycloselinks']=iunserializer($act['mycloselinks']);
	    $act['page']=iunserializer($act['page']);	    
	    parent::$act=$act;
	    $this->dooauth();
	    if ($op=='get'){
	        $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if (empty($fan)){
	            $params=array('bizid'=>$act['bizid'],'actid'=>$act['id'],'cateid'=>$cate['id']);
	            $result=$this->regfan(array(),$params);
	            if (is_error($result)){
	                $msg=$result;
	                if ($act['page']['template']=='activity_v1'){
	                    include $this->template('lottery');
	                }else{
	                    include $this->template('lottery1');
	                }
	                exit;
	            }
	            $fan=$result['fan'];
	        }
	        
    $result=$this->func('regactfan',$fan['id']);
    	    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and id={$_GPC['id']}");
    	    if (empty($coupon)){
    	        die(json_encode(error(1,'优惠券不存在'.'select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and id={$_GPC['id']}")));
    	    }
	        $total=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']} and couponid={$_GPC['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if ($total){
	            die(json_encode(error(2,'您已经领取过该优惠券')));
	        }
    	    $data=array(
    	        'openid'=>$_SESSION['openid'],
    	        'oauth_openid'=>$_SESSION['oauth_openid'],
    	        'uniacid'=>$uniacid,
    	        'actid'=>$act['id'],
    	        'bizid'=>$act['bizid'],
    	        'couponid'=>$_GPC['id'],
    	        'come'=>2,
    	        'name'=>$coupon['name'],
    	        'type'=>$coupon['type'],
    	        'addtime'=>TIMESTAMP,
    	        'nickname'=>$_W['fans']['nickname'],
    	        'avatar'=>$_W['fans']['avatar'],
    	        'addip'=>getip(),
    	    );
    	    pdo_insert('hxq_newywym_coupongrant',$data);
    	    $grantid=pdo_insertid();
    	    $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
    	    die(json_encode(array('errno'=>0,'message'=>'领取成功','name'=>$coupon['name'],'coupontype'=>$coupon['coupontype'],'description'=>$coupon['description'],'id'=>$grantid)));
	    }elseif ($op=='check'){
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and actid={$_GPC['actid']} and id={$_GPC['id']}");
	        if (empty($coupon)){
	            die(json_encode(error(1,'优惠券不存在')));
	        }
	        if ($coupon['perfan']){
	            $total=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and cid={$_GPC['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
	            if ($coupon['perfan']<=$total){
	                die(json_encode(error(2,'您已经领取了优惠券,请到个人中心查看')));
	            }
	        }
	        if ($coupon['total']){
	            $total=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and cid={$_GPC['id']}");
	            if ($coupon['total']<=$total){
	                die(json_encode(error(3,'优惠券已经被领完')));
	            }
	        }
	        $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
	        die(json_encode(array('errno'=>0,'message'=>'可以领取','description'=>$coupon['description'])));
	    } elseif ($op=='getwxcoupon'){ 
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and actid={$_GPC['actid']} and id={$_GPC['id']}");
	        if (empty($coupon)){
	            die(json_encode(error(1,'优惠券不存在')));
	        }/*
	        $data=array(
	            'openid'=>$_SESSION['openid'],
	            'oauth_openid'=>$_SESSION['oauth_openid'],
	            'uniacid'=>$_W['uniacid'],
	            'actid'=>$act['id'],
	            'cid'=>$_GPC['id'],
	            'couponid'=>$coupon['couponid'],
	            'addtime'=>TIMESTAMP,
	            'nickname'=>$_W['fans']['nickname'],
	            'avatar'=>$_W['fans']['tag']['avatar'],
	            'addip'=>getip(),
	        );
	        pdo_insert('hxq_newywym_coupongrant',$data);*/
	        $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
	        die(json_encode(array('errno'=>0,'message'=>'领取成功','description'=>$coupon['description']))); 
	    }elseif($op=='veriflayer'){
	        
	    }elseif($op=='verif'){
	        $password=$_GPC['password'];
	        $id=$_GPC['id'];
	        if (empty($id)){
	            die(json_encode(error(1,'未指定')));
	        }
	        $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and id=$id");
	        if ($grant['veriftime']){
	            die(json_encode(error(1,'已核销')));
	        }
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and id={$grant['couponid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$coupon['bizid']}");
	        
	        if ($biz['verifpwd']!=$password){
	            die(json_encode(error(1,'核销密码不正确')));
	        }
	        $data=array(
	            'veriftime'=>TIMESTAMP
	        );
	        pdo_update('hxq_newywym_coupongrant',$data,array('id'=>$_GPC['id']));
	        exit(json_encode(error(0,'核销成功')));
	    }elseif ($op=='display'){
	        /* pdo_query('alter table '.tablename('hxq_newywym_coupongrant')." change oauthopenid oauth_openid varchar(50) not null default ''");
	        $a=pdo_fetch('show create  table '.tablename('hxq_newywym_coupongrant'));
	        var_dump($a); */
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$act['bizid']}",array(),'id');
	         
	        $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and id={$grant['couponid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$coupon['bizid']}");
	        $coupon['starttime']=date('Y-m-d',$coupon['starttime']);
	        $coupon['endtime']=date('Y-m-d',$coupon['endtime']);
	        /*
	        $data=array(
	        'openid'=>$_SESSION['openid'],
	        'oauth_openid'=>$_SESSION['oauth_openid'],
	        'uniacid'=>$_W['uniacid'],
	        'actid'=>$act['id'],
	        'cid'=>$_GPC['id'],
	        'couponid'=>$coupon['couponid'],
	        'addtime'=>TIMESTAMP,
	        'nickname'=>$_W['fans']['nickname'],
	        'avatar'=>$_W['fans']['tag']['avatar'],
	        'addip'=>getip(),
	        );
	        pdo_insert('hxq_newywym_coupongrant',$data);*/
	        $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
	        include $this->template('coupon');
	    }
	    
    }}
	


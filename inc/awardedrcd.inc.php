<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $this->checkact();
	    $op=in_array($_GPC['op'],array('display'))?$_GPC['op']:'display';
	    $type=in_array($_GPC['type'],array('grant','ungrant','all'))?$_GPC['type']:'all';
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $act['faninfoarr']=iunserializer($act['faninfoarr']);
	    $act['mycloselinks']=iunserializer($act['mycloselinks']);
	    $act['page']=iunserializer($act['page']);
	    $_M['act']=$act;	
	    $this->dooauth();
	    if (empty($_SESSION['oauth_openid'])){
	        $msg=array('errno'=>1,'message'=>'请使用微信扫码，参与活动！');
	        include $this->template('error');
	        exit();
	    }
	    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid} and oauth_openid='{$_SESSION['oauth_openid']}'");	    
	    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid}",array(),'id');
	    $type=$_GPC['type'];
	    $type_=$_GPC['type_'];
	    if($type=='1'){
	        $where.=" and (type=1 or type=2)";
	        $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$_M['act']['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' {$where} order by addtime desc");
        }elseif($type=='3'){
	       $myawardeds=pdo_fetchall("(select credit1,isgrant,addtime,'token' as tb from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$_M['act']['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=3) union (select credit1,isgrant,addtime,'give' as tb from ".tablename('hxq_newywym_give')." where uniacid={$uniacid} and actid={$_M['act']['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=3) order by addtime desc");
	       //echo ("(select credit1,isgrant,addtime,1 as table from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}') union (select credit1,isgrant,addtime,2 as table from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}') order by addtime desc"); 
	    }elseif($type=='5'){
	       load()->classs('coupon');
	       $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$_M['act']['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=5 order by addtime desc");
	       foreach($myawardeds as &$row){
	        $clscoupon = new coupon($_W['acid']);
            $ticket=$clscoupon->getCardTicket();
            $params=array($row['weixincouponid'],TIMESTAMP);
            $signature = $clscoupon->SignatureCard($params);
            $cardext=array('timestamp'=>TIMESTAMP,'signature'=>$signature);
            $cardext=json_encode($cardext);
            $row['cardext']=$cardext;
    	    }
    	   unset($row);
	    }elseif($type=='4'){
	        $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and actid={$_M['act']['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and type=4 order by addtime desc");
	        $verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and  actid={$act['id']} order by addtime asc",array(),'id');
	    }elseif($type=='coupon'){
	        if ($_GPC['type_']==''){
	            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']}",array(),'id');
	            $mycoupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and actid={$act['id']} and  bizid={$act['bizid']} and oauth_openid='{$_SESSION['oauth_openid']}' and veriftime=0  order by addtime desc",array(),'addtime');
	            foreach($mycoupons as $key=>$row){
	                if ($coupons[$row['couponid']]['endtime']>TIMESTAMP){
	                    
	                }else{
	                    unset($mycoupons[$key]);
	                }
	            }
	            $total=count($mycoupons);
	        }else{
	            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']}",array(),'id');
	            $mycoupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and actid={$act['id']} and  bizid={$act['bizid']} and oauth_openid='{$_SESSION['oauth_openid']}' and deleted=0 order by addtime desc",array(),'addtime');
	            foreach($mycoupons as $key=>$row){
	                if($row['veriftime']==0){
	                    if (($coupons[$row['couponid']]['endtime']>TIMESTAMP)){
	                        unset($mycoupons[$key]);
	                    }
	                }else{
	                    
	                }
	            }
	        }
	    }
	    //echo "select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}' {$where} order by addtime desc";
	    if($type=='1'){
	        include $this->template('awardedrcd_1');
	    }elseif($type=='2'){
	        include $this->template('awardedrcd_2');
	    }elseif($type=='3'){
	        include $this->template('awardedrcd_3');
	    }elseif($type=='4'){
	        include $this->template('awardedrcd_4');
	    }elseif($type=='5'){
	        include $this->template('awardedrcd_5');
	    }elseif ($type=='coupon'){
	        if ($_GPC['type_']==''){
	            include $this->template('awardedrcd_coupon');
	        }else{

	            include $this->template('awardedrcd_coupon_unuse');
	        }
	    }
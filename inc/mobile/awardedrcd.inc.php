<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $op=in_array($_GPC['op'],array('display'))?$_GPC['op']:'display';
	    $type=in_array($_GPC['type'],array('grant','ungrant','all'))?$_GPC['type']:'all';

	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    if ($act['getfaninfo']){
	        $this->dooauth();
	    }
	    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    
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
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$biz['id']} ",array(),'id');
	        $where.=" and type=1";
	        $totfanfisnrp=pdo_fetchcolumn('select sum(redpack) from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
	        $totalkeeprp=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_shtmline')." where uniacid={$uniacid} and  actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}'  and isgrant=1 order by addtime desc");
	        $totalrp=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and  actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' {$where} order by addtime desc");
	        $totalrp+=$totfanfisnrp+$totalkeeprp;
	        $totalrp=floatval($totalrp);
	        $myawardeds=pdo_fetchall("select addtime,nickname,redpack,'1' as rptype,id,isgrant,scanid from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' {$where} order by addtime desc");
	        foreach($myawardeds as $key=>&$row){
	            $row['child']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1 order by id desc limit 0,10");
	            $row['child_']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1");
	        }
	        unset($row);
	        $myawardeds_=pdo_fetchall("select addtime,nickname,redpack,'2' as rptype,id,isgrant,scanid  from ".tablename('hxq_newywym_shtmline')." where uniacid={$uniacid} and actid={$act['id']} and enable=1  and oauth_openid='{$_SESSION['oauth_openid']}' order by addtime desc");
	        foreach($myawardeds_ as $key=>&$row){
	            $row['child']=pdo_fetchall('select * from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$row['id']} order by id desc limit 0,10");
	            $row['child_']=pdo_fetchall('select * from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$row['id']} ");
	            $row['keepclicktimes']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$row['id']}");
	            $row['keepclicktimes']=intval($row['keepclicktimes']);
	        }
	        unset($row);
	        $myawardeds__=pdo_fetchall("select addtime,nickname,redpack,'3' as rptype,id,isgrant,scanid,pid  from ".tablename('hxq_newywym_fission')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}' order by addtime desc");
	        foreach($myawardeds__ as $key=>&$row){
	            $row['parent']=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$row['pid']} order by id desc ");
	        }
	        unset($row);
	        $myawardeds=array_merge($myawardeds,$myawardeds_,$myawardeds__);
	        foreach($myawardeds as $key=>$row){
	            $times[$key]=$row['addtime'];
	        }
	        array_multisort($times ,SORT_DESC,$myawardeds);
	    }elseif($type=='3'){
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$biz['id']} ",array(),'id');
	         
            if ($_W['fans']['uid']){
                $credit1arr=pdo_get('mc_members',array('uid'=>$_W['fans']['uid']),array('credit1'));
                if ($credit1arr){
                    $credit1=$credit1arr['credit1'];
                }
            }else{
                if ($_W['account']['level']==4&&$_W['oauth_account']['acid']==$_W['account']['acid']){
                    $uidarr=pdo_get('mc_mapping_fans',array('openid'=>$_SESSION['oauth_openid']),array('uid'));
                    if ($uidarr){
                        $uid=$uidarr['uid'];
                    }
                    if ($uid){
                        $credit1arr=pdo_get('mc_members',array('uid'=>$uid),array('credit1'));
                    }
                    if ($credit1arr){
                        $credit1=$credit1arr['credit1'];
                    }
                }
            }
           $credit1=intval($credit1);
	       $myawardeds=pdo_fetchall("(select credit1,isgrant,addtime,'token' as tb from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=3) union (select credit1,isgrant,addtime,'give' as tb from ".tablename('hxq_newywym_give')." where uniacid={$uniacid} and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=3) order by addtime desc");
	       //echo ("(select credit1,isgrant,addtime,1 as table from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}') union (select credit1,isgrant,addtime,2 as table from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}') order by addtime desc"); 
	    }elseif($type=='5'){
	       load()->classs('coupon');
	       $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=5 order by addtime desc");
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
	        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and bizid={$biz['id']}  order by id desc");
            $cates_=array_map('array_shift',$cates);
            $cates_=array_values($cates_);
            $str_cates=join(',',$cates_);
            $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ({$str_cates}) order by id desc",array(),'id');
	        if ($_GPC['type_']==''){
	        $totalsw=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=4 and veriftime=0 order by addtime desc");
	        $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=4 and veriftime=0 order by addtime desc");
	        }else{
	        $totalsw=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=4 and veriftime>0 order by addtime desc");
	        $myawardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and type=4 and veriftime>0 order by addtime desc");
	        }	    
	    }elseif($type=='coupon'){
	        if ($_GPC['type_']==''){
	            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$biz['id']} ",array(),'id');
	            $mycoupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid}  and  bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and veriftime=0  order by addtime desc",array(),'addtime');
	            foreach($mycoupons as $key=>&$row){
	                $mycoupons[$key]['coupon']=$coupons[$row['couponid']];
	                if ($coupons[$row['couponid']]['endtime']>TIMESTAMP){
	                    if ($mycoupons[$key]['come']==1){
	                        $mycoupons[$key]['child']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1 order by id desc limit 0,10");
	                        $mycoupons[$key]['child_']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1");
	                       //var_dump($mycoupons[$key]['child']);
	                    }elseif ($row['come']==3){
	                        $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$row['fid']} and isgrant=1 order by id desc limit 0,10");
	                        $mycoupons[$key]['parent']=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$fission['pid']}");
	                    }
	                }else{
	                    unset($mycoupons[$key]);
	                }
	            }
	            unset($row);
	            $total=$mycoupons?count($mycoupons):0;
	        }else{
	            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$biz['id']}",array(),'id');
	            $mycoupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and veriftime>0 order by addtime desc",array(),'addtime');
	            //var_dump($mycoupons);
	            //echo "select * from ".tablename('hxq_newywym_coupongrant')." where uniacid={$uniacid} and bizid={$biz['id']}  and oauth_openid='{$_SESSION['oauth_openid']}' and veriftime>0 order by addtime desc";
	            //var_dump($mycoupons);
	            
	            foreach($mycoupons as $key=>$row){
	                $mycoupons[$key]['coupon']=$coupons[$row['couponid']];
	                if ($mycoupons[$key]['come']==1){
	                    $mycoupons[$key]['child']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1 order by id desc limit 0,10");
	                    $mycoupons[$key]['child_']=pdo_fetchall('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$row['scanid']} and pid!=0 and isgrant=1");
	                }elseif ($row['come']==3){
	                    $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$row['fid']} and isgrant=1 order by id desc limit 0,10");
	                    $mycoupons[$key]['parent']=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$fission['pid']}");
	                }
	            }
	            $total=$mycoupons?count($mycoupons):0;
	            
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
	        if ($_GPC['type_']==''){
	            include $this->template('awardedrcd_4');
	        }else{
	            include $this->template('awardedrcd_4_unuse');
	        }
	    }elseif($type=='5'){
	        include $this->template('awardedrcd_5');
	    }elseif ($type=='coupon'){
	        if ($_GPC['type_']==''){
	            include $this->template('awardedrcd_coupon');
	        }else{

	            include $this->template('awardedrcd_coupon_unuse');
	        }
	    }
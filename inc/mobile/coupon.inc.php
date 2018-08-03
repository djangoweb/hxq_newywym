<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $op=in_array($_GPC['op'],array('display','get','check','verif','displayverif','scanverif','bindverifer'))?$_GPC['op']:'display';
	    $type=$_GPC['type'];
	    $type_=$_GPC['type_'];
        if ($act['getfaninfo']){
            $this->dooauth();
        }
	    if ($op=='get'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	        $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if (empty($fan)){
	            $params=array('actid'=>$act['id'],'cateid'=>$cate['id']);
	            $result=$this->regfan(array(),$params);
	            if (is_error($result)){
	                $msg=$result;
	                response($msg);
	                exit;
	            }
	            $fan=$result['fan'];
	        }
    	    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and id={$_GPC['id']} and enable=1 and isshow=1");
    	    if (empty($coupon)){
    	        die(json_encode(error(1,'优惠券不存在')));
    	    }
	        if ($coupon['endtime']<TIMESTAMP){
	            die(json_encode(error(2,'已过期'.$coupon['endtime'])));
	        }
	        $total=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and couponid={$_GPC['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if ($total){
	            die(json_encode(error(2,'您已经领取过该优惠券')));
	        }
    	    $data=array(
    	        'uniacid'=>$uniacid,
    	        'cateid'=>$cate['id'],
    	        'actid'=>$act['id'],
    	        'bizid'=>$act['bizid'],
    	        'couponid'=>$_GPC['id'],
    	        'come'=>2,
    	        'name'=>$coupon['name'],
    	        'type'=>$coupon['type'],
    	        'addtime'=>TIMESTAMP,
    	        'nickname'=>$_W['fans']['nickname'],
    	        'oauth_openid'=>$_SESSION['oauth_openid'],
    	        'avatar'=>$_W['fans']['avatar'],
    	        'addip'=>getip(),
    	    );
    	    pdo_insert('hxq_newywym_coupongrant',$data);
    	    $grantid=pdo_insertid();
    	    $umessage=$coupon['requirement'];
    	    $hmessage='请携此微信到<span class="text-red">'.$biz['name'].'</span>兑换';
    	    $account=weaccount::create($_W['account']);
    	    $url=$_W['siteroot'].'app/'.$this->createmobileurl('coupon',array('id'=>$grantid,'cateid'=>$cate['id']));
            $postdata=array("touser"=>$_SESSION['openid'],"msgtype"=>"text","text"=>array('content'=>urlencode('['.$biz['name'].'] 您在'.$act['name'].'活动中领取了优惠券'.$coupon['name'].' '.$coupon['value'].' '.$_M['coupontypes'][$coupon['type']].$coupon['requirement'].'截止 '.date('Y-m-d',$coupon['endtime']).'。请于活动截止前，前往'.$biz['name']."兑奖。<a href='".$url."'>点击查看</a>")));
            $rst=$account->sendCustomNotice($postdata);
            if (is_error($rst)){
        	    $postdata=array(
        	        'first'=>array('value'=>'['.$biz['name'].'] 您领取了优惠券','color'=>'#FF0000'),
        	        'keyword1'=>array('value'=>$act['name'],'color'=>'#FF0000'),
        	        'keyword2'=>array('value'=>$coupon['name'].' '.$coupon['value'].' '.$_M['coupontypes'][$coupon['type']],'color'=>'#FF0000'),
        	        'keyword3'=>array('value'=>date('Y-m-d H:i:s',$data['addtime']),'color'=>'#FF0000'),
        	        'keyword4'=>array('value'=>'截止 '.date('Y-m-d',$coupon['endtime']),'color'=>'#FF0000'),
        	        'remark'=>array('value'=>$coupon['requirement'].'。请于活动截止前，前往'.$biz['name'].'兑奖','color'=>'#FF0000'),
        	    );
        	    $rst=$account->sendTplNotice($_SESSION['openid'],$this->module['config']['notice']['coupontplid'],$postdata,$url,'');
            }
    	    //echo $this->module['config']['notice']['coupontplid'];
    	    if (!is_error($rst)){
    	        $data=array(
    	            'uniacid'=>$_W['uniacid'],
    	            'cateid'=>$cate['id'],
    	            'actid'=>$act['id'],
    	            'bizid'=>$biz['id'],
    	            'gid'=>$coupongrantid,
    	            'openid'=>$_SESSION['openid'],
    	            'oauth_openid'=>$_SESSION['oauth_openid'],
    	            'content'=>$_W['fans']['nickname'].'获得了'.$coupon['name'].' '.$coupon['value'].' '.$_M['coupontypes'][$coupon['type']],
    	            'nickname'=>$_W['fans']['nickname'],
    	            'addtime'=>TIMESTAMP,
    	        );
    	        pdo_insert('hxq_newywym_noticelog',$data);
    	    }else{
    	        file_put_contents('888.txt',$rst['message']);
    	    }
    	    die(json_encode(array('errno'=>0,'message'=>'领取成功','hmessage'=>$hmessage,'umessage'=>$umessage,'coupon'=>$coupon,'id'=>$grantid)));
	    }elseif ($op=='scanverif'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	        
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
            print_r($act,$cate,$biz);if ($type=='sw'){
               $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']}  and id={$_GPC['id']}");
                if (empty($token)){
                    $msg=error(1,'记录不存在');
                    include $this->template('error');
                    exit;
                }
                if ($token['veriftime']){
                    $msg=error(1,'已核销');
                    include $this->template('error');
                    exit;
                }
                $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where oauth_openid='{$_SESSION['oauth_openid']}' and uniacid={$_W['uniacid']} and bizid={$token['bizid']}");
                if (empty($verifer)){
                    $msg=error(1,'核销员不存在');
                    include $this->template('error');
                    exit;
                }
                $data=array(
                    'veriftime'=>TIMESTAMP,
                    'veriferid'=>$verifer['id'],
                );
                pdo_update('hxq_newywym_token',$data,array('id'=>$_GPC['id']));
                $msg=error(0,'核销成功');
                include $this->template('scanverif');
                    exit;
            }elseif($type=='coupon'){
                $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                if (empty($grant)){
                    $msg=error(1,'记录不存在');
                    include $this->template('error');
                    exit;
                }
                if ($grant['veriftime']){
                    $msg=error(1,'已核销');
                    include $this->template('error');
                    exit;
                }
                $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where oauth_openid='{$_SESSION['oauth_openid']}' and uniacid={$_W['uniacid']} and bizid={$grant['bizid']}");
                if (empty($verifer)){
                    $msg=error(1,'核销员不存在');
                    include $this->template('error');
                    exit;
                }
                $data=array(
                    'veriftime'=>TIMESTAMP,
                    'veriferid'=>$verifer['id'],
                );
                pdo_update('hxq_newywym_coupongrant',$data,array('id'=>$_GPC['id']));
                $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$biz['id']}",array(),'id');
                
                $coupon=$coupons[$grant['couponid']];
                $msg=error(0,'核销成功');
                include $this->template('scanverif');
                  exit;
            }
        }elseif ($op=='check'){
                if ($type=='sw'){
                $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                if (empty($token)){
                    $msg=error(1,'记录不存在');
                    response($msg);
                }
                if ($token['veriftime']){
                    $msg=error(0,'已核销');response($msg);
                }else{
                    $msg=error(1,'未核销');response($msg);
                }
            }elseif ($type=='coupon'){
                $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                if ($coupon['veriftime']){
                    $msg=error(0,'已核销');response($msg);
                }else{
                    $msg=error(1,'未核销');response($msg);
                }
            }
	    }elseif ($op=='display'){
	        /* pdo_query('alter table '.tablename('hxq_newywym_coupongrant')." change oauthopenid oauth_openid varchar(50) not null default ''");
	        $a=pdo_fetch('show create  table '.tablename('hxq_newywym_coupongrant'));
	        var_dump($a); */

	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$biz['id']}",array(),'id');
	        $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");

	        pdo_update('hxq_newywym_noticelog',array('isread'=>1),array('gid'=>$_GPC['id']));
	        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and id={$grant['couponid']}");
	        //var_dump($coupons);
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
	    }elseif($op=='displayverif'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	        $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$biz['id']}",array(),'id');
	        pdo_update('hxq_newywym_noticelog',array('isread'=>1),array('gid'=>$_GPC['id']));
	        $coupon=$coupons[$grant['couponid']];
	        //var_dump($grant,$biz,$coupons);
	        include $this->template('coupon');
	    }
	    if ($op=='bindverifer'){
	        $this->dooauth(true);
	        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['bizid']}");
	        $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid={$_W['uniacid']} and bizid={$_GPC['bizid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if ($verifer){
	            $error=error(1,'您已经绑定成为'.$biz['name'].'的核销员，无需重复绑定');
	            include $this->template('bindverifer');
	            exit;
	        }
	        $data=array(
	            'uniacid'=>$_W['uniacid'],
	            'bizid'=>$_GPC['bizid'],
	            'nickname'=>$_W['fans']['nickname'],
	            'oauth_openid'=>$_SESSION['oauth_openid'],
	            'addtime'=>TIMESTAMP,
	        );
	        pdo_insert('hxq_newywym_verifer',$data);
	        $error=error(0,'您已经成功绑定成为'.$biz['name'].'的核销员');
	        include $this->template('bindverifer');
	    }
	    
	    
	


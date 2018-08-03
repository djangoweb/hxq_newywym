<?php
    require_once MODULE_ROOT.'/inc/mobileinit.php';
    if (empty($_SESSION['oauth_openid'])){
        $msg=array('errno'=>2,'message'=>'系统设置有误');
        include $this->template('error');
        exit;
    }
    if (!empty($_GPC['actid'])){
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['actid']}");
        if (empty($act)){
            $msg=error(1,'出错了！');
            include $this->template('error');
            exit;
        }
        if ($_GPC['from']=='timeline'){
            $isclick=pdo_fetch('select * from '.tablename('hxq_newywym_click')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
               if ($isclick){
                $isclick['total']++;
    	        pdo_update('hxq_newywym_click',array('total'=>$isclick['total']),array('id'=>$isclick['id']));
    	       }else{
    	        $data=array(
    	            'uniacid'=>$_W['uniacid'],
    	            'actid'=>$act['id'],
    	            'cateid'=>0,
    	            'oauth_openid'=>$_SESSION['oauth_openid'],
    	            'nickname'=>$_W['fans']['nickname'],
    	            'total'=>1,
    	            'addtime'=>TIMESTAMP,
    	            'addip'=>getip()
    	        );
    	        pdo_insert('hxq_newywym_click',$data);
	           }
        }
        $act['page']=iunserializer($act['page']);
        $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
        $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
        if (!empty($act['district'])){
            if (strpos($act['district'],',')!==false){
                $act['districtarr']=explode(',',$act['district']);
                $act['districtarr']=array_filter($act['districtarr']);
            }else{
                $act['districtarr'][]=$act['district'];
            }
        }
        $page=$act['page']['pagesetting'];
        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and actid={$act['id']}",array(),'id');
        load()->classs('coupon');
        $clscoupon = new coupon($_W['acid']);
        foreach($coupons as $key=>&$row){
            $row['grantfantotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and cid={$row['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            $row['granttotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and cid={$row['id']}");
            $row['starttime']=date('Y-m-d');
            $row['endtime']=date('Y-m-d');
            if ($row['type']==2){
                $params=array($row['couponid'],TIMESTAMP);
                $signature = $clscoupon->SignatureCard($params);
                $cardext=array('timestamp'=>TIMESTAMP,'signature'=>$signature);
                $cardext=json_encode($cardext);
                $row['cardext']=$cardext;
            }
        }
        unset($row);
        $mycoupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
        $mytokens=pdo_fetchall('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }else{
    $tk=intval($_GPC['tk']);
    $vtk=addslashes($_GPC['vtk']);
    $msg=$this->checktoken($_M['slat'],$tk,$vtk);
    if (is_error($msg)&&$msg['errno']!=6&&$msg['errno']!=7&&$msg['errno']!=8){
        include $this->template('error') ;
        exit;
    }
    $cate=$msg['cate'];
    $act=$msg['act'];
    $act['page']=iunserializer($act['page']);
    $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
    $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
    if (!empty($act['district'])){
        if (strpos($act['district'],',')!==false){
            $act['districtarr']=explode(',',$act['district']);
            $act['districtarr']=array_filter($act['districtarr']);
        }else{
            $act['districtarr'][]=$act['district'];
        }
    }
    $page=$act['page']['pagesetting'];
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and actid={$act['id']}",array(),'id');
    load()->classs('coupon');
    $clscoupon = new coupon($_W['acid']);
        foreach($coupons as $key=>&$row){
            $row['grantfantotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']}  and cid={$row['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            $row['granttotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']}  and cid={$row['id']}");
            $row['starttime']=date('Y-m-d');
            $row['endtime']=date('Y-m-d');
            if ($row['type']==2){
                $params=array($row['couponid'],TIMESTAMP);
                $signature = $clscoupon->SignatureCard($params);
                $cardext=array('timestamp'=>TIMESTAMP,'signature'=>$signature);
                $cardext=json_encode($cardext);
                $row['cardext']=$cardext;
            }
        }
        unset($row);
        $mycoupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
        $mytokens=pdo_fetchall('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
        
    $_M['act']=$act;
    $this->dooauth();
    if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
        $msg=error(2,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
        if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }else{
           include $this->template('lottery1');
        }
        exit;
    }
    if ($msg['errno']==6||$msg['errno']==7){
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }
    if($msg['errno']==8){
        $tokendata=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and tk='{$tk}' and oauth_openid='{$_SESSION['oauth_openid']}'");
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }
    if($act['lnum']>0){
        if ($act['ltype']==0){
            $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and  oauth_openid='{$_SESSION['oauth_openid']}'");
            if($totallottery>=$act['lnum']){
                $msg=error(5,'每活动限扫码抽奖'.$act['lnum'].'次！');
              if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }
        }else{
            $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and  oauth_openid='{$_SESSION['oauth_openid']}' and addtime<".strtotime(date('Y-m-d 23:59:59'))." and addtime>".strtotime(date('Y-m-d 00:00:00')));
            if($totallottery>=$act['lnum']){
                $msg=error(5,'每天限扫码抽奖'.$act['lnum'].'次！');
                if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }
        }
    }
    $wxzfparams=$this->module['config']['pay'];
    if (empty($wxzfparams)){
        $msg=error(5,'微信支付参数未配置，请在模块设置里配置！');
       if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
    }else{
        foreach ($wxzfparams as $key=>$item){
            if (empty($item)){
                $msg=error(5,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
                if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }
        }
    }
    
    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
    if (empty($fan)){
        $params=array('actid'=>$act['id'],'cateid'=>$cate['id']);
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
    if (($fan['nickname']||!$fan['avatar'])&&$act['getfaninfo']){
        pdo_update('hxq_newywym_fan',array('nickname'=>$_W['fans']['nickname'],'avatar'=>$_W['fans']['tag']['avatar']),array('oauth_openid'=>$_SESSION['oauth_openid']));
    }
    if ($act['blacklist']&&!empty($fan)){
        $member=mc_fetch($fan['uid'],array('groupid'));
        if (in_array($member['groupid'],$act['blacklistgroups'])){
            $msg=array('errno'=>5,'message'=>"无效扫码！");//黑名单限制，为了好听用尚未开始的字眼
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
        }
    }
    if (empty($tokendata)){
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0  order by sort asc",array(),'id');
        foreach($awards as $key=>&$row){
            $row['thumb']=$_W['attachurl'].$row['thumb'];
        }
        unset($row);
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and awardid={$row['id']} order by id desc");
            if (empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total'])){
                unset($awards[$key]);
            }elseif (!empty($awards[$key]['tokenids'])){
                $awards[$key]['tokenids']=explode(',',$awards[$key]['tokenids']);
                $awards[$key]['tokenids']=array_filter($awards[$key]['tokenids']);
        
            }
        }
        $actawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and addtime<".strtotime(date('Y-m-d 23:59:59'))." and addtime>".strtotime(date('Y-m-d 00:00:00')));
        if ($act['lnum']>0&&$actawarded>=$act['lnum']){
                $msg=error(5,'今天的奖励已经送完，请明天继续');
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }else{
                    include $this->template('lottery1');
                }
                exit;
        }
        $fanawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}' and isgrant=1 order by id desc");
        if ($act['maxawarded']>0&&$fanawarded>=$act['maxawarded']){
            $msg=error(5,'您已达到本次活动扫码次数上限');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
        }
        $fandayawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'  and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
        if ($act['daymaxawarded']>0&&$fandayawarded>$act['daymaxawarded']){
            $msg=error(5,'您已达到今日扫码次数上限，明日可继续扫码抽奖！');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
        }
        load()->model('mc');
        load()->model('activity');
        foreach($awards as $key=>$row){
            if ($row['tokenids']&&in_array($tk,$row['tokenids'])){
                $l_rst=$awards[$key];
                break;
            }
        }
        //var_dump($awards);
        if (empty($l_rst)){
            require_once MODULE_ROOT .'/inc/lottery.class.php';
            $lottery=new lottery();
            $l_rst=$lottery->saveaward($awards);
        }
        $tokendata=array(
            'uniacid'=>$uniacid,
            'actid'=>$act['id'],
            'cateid'=>$cate['id'],
            'tk'=>$_GPC['tk'],
            'openid'=>$fan['openid'],
            'nickname'=>$fan['nickname'],
            'avatar'=>$fan['avatar'],
            'oauth_openid'=>$_SESSION['oauth_openid'],
            'awardid' =>$l_rst['id'],
            'type' =>$l_rst['type'],
            'title' =>$l_rst['title'],
            'weixincouponid' =>$l_rst['weixincouponid'],
            'redpack' =>$l_rst['redpack'],
            'credit1' =>$l_rst['credit1'],
            'total_num' =>$l_rst['total_num'],
            'isgrant'=>0,
            'sex'=>$_W['fans']['tag']['sex'],
            'province' =>js_unescape($_COOKIE['province']),
            'city' =>js_unescape($_COOKIE['city']),
            'district' =>js_unescape($_COOKIE['district']),
            'street'=>js_unescape($_COOKIE['street']),
            'swprice'=>$l_rst['swprice'],
            'addip'=>getip(),
            'addtime'=>TIMESTAMP,
            'enable'=>1,
        );
        pdo_insert('hxq_newywym_token',$tokendata);
        $tokenid=pdo_insertid();
        $tokendata['id']=$tokenid;
        $tokendata['thumb']=$l_rst['thumb'];
    }else{
        $tokendata['thumb']=$_W['attachurl'].$tokendata['thumb'];
    }

    pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('id'=>$_GPC['scanid']));
    /* 
    if ($_M['act']['granttype']==2){
        if ($tokendata['type']!=100){
           header('location:'.$this->createmobileurl('share',array('cateid'=>$cateid,'id'=>$tokenid,'scanid'=>$_GPC['scanid'])));
           exit;
        }
    } */
    //echo 'f';
    if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
    }
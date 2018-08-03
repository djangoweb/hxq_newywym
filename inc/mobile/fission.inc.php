<?php
    require_once MODULE_ROOT.'/inc/mobileinit.php';
    if (empty($_SESSION['oauth_openid'])){
        $msg=array('errno'=>1,'message'=>'系统设置有误');
        include $this->template('error');
        exit;
    }
    $op=in_array($_GPC['op'],array('default','enable'))?$_GPC['op']:'display';
    if ($op=='enable'){
        pdo_update('hxq_newywym_fission',array('enable'=>1),array('id'=>$_GPC['fid']));
        $msg=array('errno'=>0,'message'=>'成功');
        echo json_encode($msg);
        exit;
    }
    if ($op=='display'){
    $urls=parse_url($_W['siteroot']);
    $scheme=$urls['scheme'];
    if (empty($_GPC['fid'])){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    $pfission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and idstr='{$_GPC['fid']}'");
    if (empty($pfission)){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
    if ($act['getfaninfo']){
        $this->dooauth();
    }
    $act['page']=iunserializer($act['page']);
    $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
    $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
    if (empty($act['fisnarea'])){
        $act['fisnarea']=array();
    }else{
        if (strexists($act['fisnarea'],',')){
            $act['fisnarea']=explode(',',$act['fisnarea']);
        }else{
            $act['fisnarea']=array($act['fisnarea']);
        }
    }
    if ($_SESSION['openid']){
        pdo_update('hxq_newywym_fission',array('openid'=>$_SESSION['openid']),array('oauth_openid'=>$_SESSION['oauth_openid'],'actid'=>$act['id']));
    }
    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
    $page=$act['page']['pagesetting'];
    function wrapspace($v){
        return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
    }
    $page['desc']=array_map('wrapspace',$page['desc']);
    if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
        $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
        include $this->template('error');
        exit;
    }
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and enable=1  and endtime>unix_timestamp()",array(),'id');
    foreach($coupons as &$row){
        $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    }
    unset($row);
    if (empty($pfission['enable'])){
        $msg=array('errno'=>2,'message'=>'参数错误','umessage'=>'无效的领奖链接','redirect'=>$act['shareoutlink']);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fission');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fission1');
        }
        exit;
    }
    if ($pfission['oauth_openid']==$_SESSION['oauth_openid']){
        if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
            $this->census($_W['siteurl']);
            header('location:'.$act['shareoutlink']);
            exit;
        }
        $msg=array('errno'=>1,'message'=>'openid','umessage'=>'您不能点击自己的分享');
        if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
        exit;
    }
//原先有参与并中奖，显示中奖
   //有参与没中奖或奖品已发完 则提示 谢谢参与    
   if ($act['fisnawardtype']){
     $isscanawarded=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and actid={$pfission['actid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
        if ($isscanawarded){
            if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
                $this->census($_W['siteurl']);
                header('location:'.$act['shareoutlink']);
                exit;
            }
            $msg=array('errno'=>1,'message'=>'已扫码领奖');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
            exit;
        }
   }
    $isawarded=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$pfission['actid']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
    if ($isawarded){
        if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
            $this->census($_W['siteurl']);
            header('location:'.$act['shareoutlink']);
            exit;
        }
        $msg=array('errno'=>1,'message'=>'已领裂变奖');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fission');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fission1');
        }
        exit;
    }
    $ptotalgrant=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$pfission['actid']} and pid={$pfission['id']} and isgrant=1");
    if ($ptotalgrant>=$act['fissionnum']){
        $msg=array('errno'=>2,'message'=>'来迟了一步，奖品已经被人领走。','umessage'=>'来迟了一步，奖品已经被人领走。','redirect'=>$act['shareoutlink']);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fission');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fission1');
        }
        exit;
    }
    $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and pid={$pfission['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    if (empty($fission)){
        $fission=array(
            'uniacid'=>$_W['uniacid'],
            'actid'=>$pfission['actid'],
            'cateid'=>$pfission['cateid'],
            'bizid'=>$pfission['bizid'],
            'openid'=>$_SESSION['openid'],
            'oauth_openid'=>$_SESSION['oauth_openid'],
            'avatar'=>$_W['fans']['tag']['avatar'],
            'nickname'=>$_W['fans']['nickname'],
            'addtime'=>TIMESTAMP,
            'depth'=>1,
            'pid'=>$pfission['id'],
            'awardid'=>0,
            'isgrant'=>0,
            'token'=>$pfission['token'],
            'scanid'=>$pfission['scanid'],
            'checkarea'=>0
        );
        pdo_insert('hxq_newywym_fission',$fission);
        $fission['id']=pdo_insertid();
    }
    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
    if (empty($fan)){
        $params=array('actid'=>$act['id'],'cateid'=>$cate['id']);
        $result=$this->regfan(array(),$params);
        if (is_error($result)){
            $msg=$result;
            if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
            exit;
        }
        $fan=$result['fan'];
    }
    if (($fan['nickname']||!$fan['avatar'])&&$act['getfaninfo']){
        pdo_update('hxq_newywym_fan',array('nickname'=>$_W['fans']['nickname'],'avatar'=>$_W['fans']['tag']['avatar']),array('oauth_openid'=>$_SESSION['oauth_openid']));
    }
    if (!empty($fission['awardid'])){
        $fisnaward=pdo_fetch("select * from ".tablename('hxq_newywym_fisnaward')." where  id={$fission['awardid']}");
        $fisnawarded=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_fission')." where actid={$act['id']} and isgrant=1 and awardid={$fission['awardid']}");
    }
    if (empty($fission['awardid'])){
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_fisnaward')." where uniacid={$uniacid} and actid={$act['id']} and disable=0  order by id desc",array(),'id');
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_fission')." where uniacid={$uniacid} and isgrant=1 and awardid={$row['id']} order by id desc");
            if ((empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total']))){
                unset($awards[$key]);
            }
        }
        if (empty($awards)){
            $msg=array('errno'=>5,'message'=>"所有裂变奖品已被抽完！");
            if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
            exit;
        }
        load()->model('mc');
        load()->model('activity');
        //var_dump($awards);
        require_once MODULE_ROOT .'/inc/lottery.class.php';
        $lottery=new lottery($awards);
        $l_rst=$lottery->saveaward($awards);
        if ($l_rst['type']==5){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']} and enable=1");
            if (empty($coupon)){
                $msg=array('errno'=>5,'message'=>"优惠券不存在！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('fission');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('fission1');
                }
                exit;
            }
            if ($coupon['endtime']<TIMESTAMP){
                $msg=array('errno'=>5,'message'=>"优惠券已过期！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('fission');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('fission1');
                }
                exit;
            }
        }
        $data=array(
            'awardid'=>$l_rst['id'],
            'title'=>$l_rst['title'],
            'couponid'=>$l_rst['couponid'],
            'credit1'=>$l_rst['credit1'],
            'redpack'=>$l_rst['redpack'],
            'type'=>$l_rst['type'],
        );
        pdo_update('hxq_newywym_fission',$data,array('id'=>$fission['id']));
        $fission=array_merge($fission,$data);
    }elseif (!empty($fisnaward)&&$fisnawarded>=$fisnaward['total']){
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and actid={$act['id']} and disable=0 and id!={$fisnaward['id']} order by id desc",array(),'id');
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_fission')." where uniacid={$uniacid} and isgrant=1 and awardid={$row['id']} order by id desc");
            if ((empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total']))){
                unset($awards[$key]);
            }
        }
        if (empty($awards)){
            $msg=array('errno'=>5,'message'=>"所有裂变奖品已被抽完！");
            if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
            exit;
        }
        load()->model('mc');
        load()->model('activity');
        //var_dump($awards);
        if (empty($l_rst)){
            require_once MODULE_ROOT .'/inc/lottery.class.php';
            $lottery=new lottery($awards);
            $l_rst=$lottery->saveaward($awards);
        }
        if ($l_rst['type']==5){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']} and enable=1");
            if (empty($coupon)){
                $msg=array('errno'=>5,'message'=>"优惠券不存在！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('fission');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('fission1');
                }
                exit;
            }
            if ($coupon['endtime']<TIMESTAMP){
                $msg=array('errno'=>5,'message'=>"优惠券已过期！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('fission');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('fission1');
                }
                exit;
            }
        }
        $data=array(
            'awardid'=>$l_rst['id'],
            'couponid'=>$l_rst['couponid'],
            'credit1'=>$l_rst['credit1'],
            'redpack'=>$l_rst['redpack'],
            'type'=>$l_rst['type'],
            'title'=>$l_rst['title']
        );
        pdo_update('hxq_newywym_fission',$data,array('id'=>$fission['id']));
        $fission=array_merge($fission,$data);
    }
    if ($fission['type']==100){
        pdo_update('hxq_newywym_fission',array('isgrant'=>1),array('id'=>$fission['id']));
        $fission['isgrant']=1;
    }
    $msg=error(0,'可以发奖');
    if ($act['page']['template']=='activity_v1'){
        include $this->template('fission');
    }elseif ($act['page']['template']=='activity_v2'){
        include $this->template('fission1');
    }
    exit();
    }
    
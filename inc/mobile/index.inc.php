<?php
    require_once MODULE_ROOT.'/inc/mobileinit.php';
    //var_dump($_SESSION);
    if (empty($_SESSION['oauth_openid'])){
        $msg=array('errno'=>2,'message'=>'系统设置有误');
        include $this->template('error');
        exit;
    }
    if (!empty($_GPC['cateid'])||!empty($_GPC['actid'])){
        if (!empty($_GPC['cateid'])){
            $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
            $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
        }
        if (empty($act)&&!empty($_GPC['actid'])){
            $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['actid']}");
        }
        if (empty($act)){
            $msg=error(1,'活动不存在或已删除');
            include $this->template('lottery');
            exit;
        }
        //$cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$cate['cateid']}");
        if ($act['getfaninfo']){
            $this->dooauth();
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
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
        if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
            $this->census($_W['siteurl']);
            header('location:'.$act['shareoutlink']);
            exit;
        }
        $page=$act['page']['pagesetting'];
        function wrapspace($v){
            return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
        }
    $page['desc']=array_map('wrapspace',$page['desc']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and enable=1 and isshow=1 and endtime>unix_timestamp()",array(),'id');
    foreach($coupons as &$row){
    $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    }
    unset($row);
    if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
        exit;
    }else{
    $tk=intval($_GPC['tk']);
    $vtk=addslashes($_GPC['vtk']);
    $msg=$this->checktoken($_M['slat'],$tk,$vtk);
    if (is_error($msg)&&$msg['errno']!=6&&$msg['errno']!=7&&$msg['errno']!=8){
        include $this->template('error');
        exit;
    }
    $cate=$msg['cate'];
    $act=$msg['act'];
    if ($act['getfaninfo']){
        $this->dooauth();
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
    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
    function wrapspace($v){
        return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
    }
    $page['desc']=array_map('wrapspace',$page['desc']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and endtime>unix_timestamp() and  enable=1 and bizid={$act['bizid']}",array(),'id');
    foreach($coupons as &$row){
    $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2  and oauth_openid='{$_SESSION['oauth_openid']}'");
    }
    unset($row);
    
    if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
        $msg=error(2,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
           include $this->template('error');
        exit;
    }
    if ($msg['errno']==6||$msg['errno']==7){
        $tokendata=$msg['token'];
        $scandata=$msg['scan'];
        if ($msg['errno']==7){
          $wxzfparams=$this->module['config']['pay'];
        if (empty($wxzfparams)){
            $msg=error(1,'微信支付参数未配置，请在模块设置里配置！');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('lottery1');
            }elseif ($act['page']['template']=='activity_v3'){
                include $this->template('lottery2');
            }elseif ($act['page']['template']=='activity_v4'){
                include $this->template('lottery3');
            }
            exit;
        }else{
            foreach ($wxzfparams as $key=>$item){
                if (empty($item)){
                    $msg=error(1,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
                    if ($act['page']['template']=='activity_v1'){
                        include $this->template('lottery');
                    }elseif ($act['page']['template']=='activity_v2'){
                        include $this->template('lottery1');
                    }elseif ($act['page']['template']=='activity_v3'){
                        include $this->template('lottery');
                    }elseif ($act['page']['template']=='activity_v4'){
                        include $this->template('lottery3');
                    }
                    exit;
                }
            }
        }

        if ($act['plusability']==1){
            $keep=pdo_fetch('select * from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and  scanid={$scandata['id']}");
        }elseif($act['plusability']==2){
            $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$scandata['id']}");
        }
        if ($tokendata['type']==1||$tokendata['type']==2){
            if ($wxzfparams['type']!=2){
                $hmessage='请及时领取红包';
            }
            else{
                $hmessage='红包已存入您的零钱';
            }
        }elseif($tokendata['type']==3){
        }elseif($tokendata['type']==4){
            $hmessage='请携此微信到<span class="text-red">'.$biz['name'].'</span>兑换';
        }elseif($tokendata['type']==5){
            $coupongrant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where tokenid=".$tokendata['id']);
            $umessage=$coupons[$tokendata['couponid']]['requirement'];
            $hmessage='请携此微信到<span class="text-red">'.$biz['name'].'</span>兑换';
        }
        }else{
            $message='奖品已经被人领取，请扫描其他二维码';
        }
        $msg=array('errno'=>$msg['errno'],'message'=>$message,'hmessage'=>$hmessage,'umessage'=>$umessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
        exit;
    }elseif($msg['errno']==8){
        $scandata=$msg['scan'];
    }
    $wxzfparams=$this->module['config']['pay'];
    if (empty($wxzfparams)){
        $msg=error(5,'微信支付参数未配置，请在模块设置里配置！');
    if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
            exit;
    }else{
        foreach ($wxzfparams as $key=>$item){
            if (empty($item)){
                $msg=error(5,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
            if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
            exit;
            }
        }
    }
    if($act['lnum']>0){
        $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and  addtime<".strtotime(date('Y-m-d 23:59:59'))." and addtime>".strtotime(date('Y-m-d 00:00:00')));
        if($totallottery>=$act['lnum']){
            $msg=error(5,'今天的扫码抽奖机会已没有了！');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('lottery1');
            }elseif ($act['page']['template']=='activity_v3'){
                include $this->template('lottery2');
            }elseif ($act['page']['template']=='activity_v4'){
                include $this->template('lottery3');
            }
            exit;
        }
    }
    $fanawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'  order by id desc");
    //echo $fanawarded,$act['maxawarded'];
    if ($act['maxawarded']>0&&$fanawarded>=$act['maxawarded']){
        $msg=error(5,'您已达到本次活动扫码奖励次数上限');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
            include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
            include $this->template('lottery3');
        }
        exit;
    }
    $fandayawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'  and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
    if ($act['daymaxawarded']>0&&$fandayawarded>=$act['daymaxawarded']){
        $msg=error(5,'您已达到今日扫码次数上限！');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
            include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
            include $this->template('lottery3');
        }
        exit;
    }
    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
    if (empty($fan)){
        $params=array('actid'=>$act['id'],'cateid'=>$cate['id']);
        $result=$this->regfan(array(),$params);
        if (is_error($result)){
            $msg=$result;
        if ($act['page']['template']=='activity_v1'){
           include $this->template('lottery');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
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
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
            exit;
        }
    }
    if (!empty($scandata)){
        $scanaward=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where  id={$scandata['awardid']}");
        $scanawarded=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where isgrant=1 and awardid={$scandata['awardid']}");
    }
    if (empty($scandata)){
        if ($act['maxperfanscan']){
            $totalscan=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_scan')." where uniacid={$uniacid} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            if ($totalscan>$act['maxperfanscan']){
                $msg=array('errno'=>5,'message'=>"您的扫码次数过多，已无法领奖！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('lottery1');
                }elseif ($act['page']['template']=='activity_v3'){
                    include $this->template('lottery2');
                }elseif ($act['page']['template']=='activity_v4'){
                    include $this->template('lottery3');
                }
                exit;
            }
        }
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0  order by id desc",array(),'id');
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and awardid={$row['id']} order by id desc");
            if ((empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total']))){
                $tmprate+=$awards[$key]['rate'];
                unset($awards[$key]);
            }elseif (!empty($awards[$key]['tokenids'])){
                $awards[$key]['tokenids']=explode(',',$awards[$key]['tokenids']);
                $awards[$key]['tokenids']=array_filter($awards[$key]['tokenids']);
            }
        }
        if (empty($awards)){
            $msg=array('errno'=>5,'message'=>"所有奖品已被抽完！");
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('lottery1');
            }elseif ($act['page']['template']=='activity_v3'){
                include $this->template('lottery2');
            }elseif ($act['page']['template']=='activity_v4'){
                include $this->template('lottery3');
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
            $lottery=new lottery($awards);
            $l_rst=$lottery->saveaward($awards);
        }
        if ($l_rst['type']==5){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']} and enable=1");
            if (empty($coupon)){
                $msg=array('errno'=>5,'message'=>"优惠券不存在！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('lottery1');
                }elseif ($act['page']['template']=='activity_v3'){
                    include $this->template('lottery2');
                }elseif ($act['page']['template']=='activity_v4'){
                    include $this->template('lottery3');
                }
                exit;
            }
            if ($coupon['endtime']<TIMESTAMP){
                $msg=array('errno'=>5,'message'=>"优惠券已过期！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('lottery1');
                }elseif ($act['page']['template']=='activity_v3'){
                    include $this->template('lottery2');
                }elseif ($act['page']['template']=='activity_v4'){
                    include $this->template('lottery3');
                }
                exit;
            }
        }
        $scandata=array(
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
            'thumb'=>$l_rst['thumb'],
            'couponid' =>$l_rst['couponid'],
            'title' =>$l_rst['title'],
            'redpack' =>$l_rst['redpack'],
            'credit1' =>$l_rst['credit1'],
            'total_num' =>$l_rst['total_num'],
            'thumb' =>$l_rst['thumb'],
            'isgrant'=>0,
            'bizid'=>$act['bizid'],
            'addip'=>getip(),
            'updatetime'=>TIMESTAMP,
            'addtime'=>TIMESTAMP
        );
        pdo_insert('hxq_newywym_scan',$scandata);
        $scanid=pdo_insertid();
        $scandata['id']=$scanid;
    }elseif (!empty($scandata)&&$scanawarded>=$scanaward['total']){
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0 and id<>{$scandata['awardid']} order by id desc",array(),'id');
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and awardid={$row['id']} order by id desc");
            if ((empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total']))){
                $tmprate+=$awards[$key]['rate'];
                unset($awards[$key]);
            }elseif (!empty($awards[$key]['tokenids'])){
                $awards[$key]['tokenids']=explode(',',$awards[$key]['tokenids']);
                $awards[$key]['tokenids']=array_filter($awards[$key]['tokenids']);
            }
        }
        if (empty($awards)){
            $msg=array('errno'=>5,'message'=>"所有奖品已被抽完！");
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('lottery1');
            }elseif ($act['page']['template']=='activity_v3'){
                include $this->template('lottery2');
            }elseif ($act['page']['template']=='activity_v4'){
                include $this->template('lottery3');
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
            $lottery=new lottery($awards);
            $l_rst=$lottery->saveaward($awards);
        }
        $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']}");
        if ($l_rst['type']==5){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']} and enable=1");
            if (empty($coupon)){
                $msg=array('errno'=>5,'message'=>"优惠券不存在！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('lottery1');
                }elseif ($act['page']['template']=='activity_v3'){
                    include $this->template('lottery2');
                }elseif ($act['page']['template']=='activity_v4'){
                    include $this->template('lottery3');
                }
                exit;
            }
            if ($coupon['endtime']<TIMESTAMP){
                $msg=array('errno'=>5,'message'=>"优惠券已过期！");
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }elseif ($act['page']['template']=='activity_v2'){
                    include $this->template('lottery1');
                }elseif ($act['page']['template']=='activity_v3'){
                    include $this->template('lottery2');
                }elseif ($act['page']['template']=='activity_v4'){
                    include $this->template('lottery3');
                }
                exit;
            }
        }
        $scandata_=array(
            'awardid' =>$l_rst['id'],
            'type' =>$l_rst['type'],
            'thumb'=>$l_rst['thumb'],
            'couponid' =>$l_rst['couponid'],
            'title' =>$l_rst['title'],
            'redpack' =>$l_rst['redpack'],
            'credit1' =>$l_rst['credit1'],
            'total_num' =>$l_rst['total_num'],
            'thumb' =>$l_rst['thumb'],
            'isgrant'=>0,
            'addip'=>getip(),
            'updatetime'=>TIMESTAMP,
        );
        pdo_update('hxq_newywym_scan',$scandata_,$scandata['id']);
        $scandata=array_merge($scandata,$scandata_);
    }
    if ($scandata['type']==100){
        $tokendata=array(
            'uniacid'=>$uniacid,
            'actid'=>$scandata['actid'],
            'cateid'=>$scandata['cateid'],
            'tk'=>$scandata['tk'],
            'openid'=>$scandata['openid'],
            'nickname'=>$scandata['nickname'],
            'avatar'=>$scandata['avatar'],
            'oauth_openid'=>$scandata['oauth_openid'],
            'awardid' =>$scandata['awardid'],
            'type' =>$scandata['type'],
            'thumb'=>$scandata['thumb'],
            'couponid' =>$scandata['couponid'],
            'title' =>$scandata['title'],
            'redpack' =>$scandata['redpack'],
            'credit1' =>$scandata['credit1'],
            'total_num' =>$scandata['total_num'],
            'thumb' =>$scandata['thumb'],
            'isgrant'=>1,
            'bizid'=>$act['bizid'],
            'addip'=>getip(),
            'addtime'=>TIMESTAMP,
            'scanid'=>$scandata['id'],
        );
        pdo_insert('hxq_newywym_token',$tokendata);
        $tokendata['id']=pdo_insertid();
        pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
    }

    if ($scandata['type']!=100){
        if ($act['plusability']==1){
            $keep=pdo_fetch('select * from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and  scanid={$scandata['id']}");
            if (empty($keep)){
                $arr=explode('-',$act['keepprice']);
                $arr[0]=number_format($arr[0],1);
                $arr[0]=intval($arr[0]*10);
                $arr[1]=number_format($arr[1],1);
                $arr[1]=intval($arr[1]*10);
                $redpack=mt_rand($arr[0],$arr[1]);
                $redpack=$redpack/10;
                $redpack=$redpack/(1+$act['keepclicktimes']);
                $keep=array(
                    'uniacid'=>$_W['uniacid'],
                    'actid'=>$cate['actid'],
                    'cateid'=>$cate['id'],
                    'bizid'=>$act['bizid'],
                    'openid'=>$_SESSION['openid'],
                    'oauth_openid'=>$_SESSION['oauth_openid'],
                    'addtime'=>TIMESTAMP,
                    'scanid'=>$scandata['id'],
                    'redpack'=>$redpack,
                    'avatar'=>$_W['fans']['tag']['avatar'],
                    'nickname'=>$_W['fans']['nickname'],
                    'enable'=>0,
                    'isgrant'=>0,
                );
                pdo_insert('hxq_newywym_shtmline',$keep);
                $keep['id']=pdo_insertid();
            }
        }elseif($act['plusability']==2){
            $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and scanid={$scandata['id']}");
            if (empty($fission)){
                $fission=array(
                    'uniacid'=>$_W['uniacid'],
                    'actid'=>$cate['actid'],
                    'cateid'=>$cate['id'],
                    'bizid'=>$act['bizid'],
                    'openid'=>$_SESSION['openid'],
                    'oauth_openid'=>$_SESSION['oauth_openid'],
                    'avatar'=>$_W['fans']['tag']['avatar'],
                    'nickname'=>$_W['fans']['nickname'],
                    'addtime'=>TIMESTAMP,
                    'depth'=>0,
                    'pid'=>0,
                    'awardid'=>0,
                    'isgrant'=>0,
                    'enable'=>0,
                    'token'=>$tk,
                    'scanid'=>$scandata['id'],
                    'idstr'=>$scandata['id'].'-'.random(4),
                );
                pdo_insert('hxq_newywym_fission',$fission);
                $fission['id']=pdo_insertid();
            }
        }
    }
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
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
        exit;
    }
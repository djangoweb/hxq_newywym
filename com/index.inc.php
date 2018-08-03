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
        if ($_GPC['comefrom']=='timeline'&&$act['shareoutlink']){
            $this->census($_W['siteurl']);
            header('location:'.$act['shareoutlink']);
            exit;
        }
        $page=$act['page']['pagesetting'];
        function wrapspace($v){
            return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
        }
        $page['desc']=array_map('wrapspace',$page['desc']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and endtime>unix_timestamp()",array(),'id');
    foreach($coupons as &$row){
    $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}'");
    
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
        include $this->template('error') ;
        exit;
    }
    //var_dump($msg['act']);
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
    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
    function wrapspace($v){
        return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
    }
    $page['desc']=array_map('wrapspace',$page['desc']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and endtime>unix_timestamp() and bizid={$act['bizid']}",array(),'id');
    foreach($coupons as &$row){
    $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}'");
    
    }
    unset($row);
    $_M['act']=$act;
    $this->dooauth();
    if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
        $msg=error(2,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
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
    if ($msg['errno']==6||$msg['errno']==7){
        if ($msg['errno']==7){
            $tokendata=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and tk={$_GPC['tk']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
            $thumb=pdo_fetchcolumn("select thumb from ".tablename('hxq_newywym_award')." where id={$tokendata['awardid']}");
            $tokendata['thumb']=$_W['attachurl'].$thumb;
        
        $wxzfparams=$this->module['config']['pay'];;
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
        if ($tokendata['type']==1||$tokendata['type']==2){
            if ($wxzfparams['type']!=2){
                if ($_W['account']['oauth']['acid']!=$_W['acid']){
                    $hmessage='请返回微信聊天主界面及时领取红包';
                }else{
                    if ($_W['fans']['follow']){
                        $hmessage='请到'.'<span class="text-red">'.$_W['account']['name'].'公众号</span>及时领取红包';
                    }
                    else{
                        $hmessage='请到'.'<span class="text-red">服务通知公众号</span>'.'及时领取红包';
                    }
                }
            }
            else{
                $hmessage='红包已存入您的零钱';
            }
        }elseif($tokendata['type']==3){
        }elseif($tokendata['type']==4){
            $hmessage='请凭此微信到<span class="text-red">'.$biz['name'].'</span>兑换奖品';
        }elseif($tokendata['type']==5){
            $hmessage='请凭此微信到<span class="text-red">'.$biz['name'].'</span>享受优惠';
        }
        }else{
            $message='奖品已经被人领取，请扫描其他二维码';
        }
        $msg=array('errno'=>$msg['errno'],'message'=>$message,'hmessage'=>$hmessage);
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
        $tokendata=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and tk='{$tk}' and oauth_openid='{$_SESSION['oauth_openid']}'");
        $thumb=pdo_fetchcolumn("select thumb from ".tablename('hxq_newywym_award')." where id={$tokendata['awardid']}");
        $tokendata['thumb']=$_W['attachurl'].$thumb;
    }
    if($act['lnum']>0){
            $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and  addtime<".strtotime(date('Y-m-d 23:59:59'))." and addtime>".strtotime(date('Y-m-d 00:00:00')));
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
    if (empty($tokendata)){
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0  order by rate desc",array(),'id');
        foreach($awards as $key=>&$row){
            $row['thumb']=$_W['attachurl'].$row['thumb'];
        }
        $minaward=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0  order by rate desc");
        $tmprate=0;
        unset($row);
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 and awardid={$row['id']} order by id desc");
            if ($row['id']!=$minaward['id']&&(empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total']))){
                $tmprate+=$awards[$key]['rate'];
                unset($awards[$key]);
            }elseif (!empty($awards[$key]['tokenids'])){
                $awards[$key]['tokenids']=explode(',',$awards[$key]['tokenids']);
                $awards[$key]['tokenids']=array_filter($awards[$key]['tokenids']);
            }
        }
        $awards[$minaward['id']]['rate']+=$tmprate;
        $fanawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'  order by id desc");
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
        $fandayawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'  and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
        if ($act['daymaxawarded']>0&&$fandayawarded>$act['daymaxawarded']){
            $msg=error(5,'您已达到今日扫码次数上限，明日可继续扫码抽奖！');
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
            $lottery=new lottery();
            $l_rst=$lottery->saveaward($awards);
        }
        if ($l_rst['type']==5){
            $coupon=pdo_fetch("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$uniacid} and id={$l_rst['couponid']}");
            $l_rst['weixincouponid']=$coupon['couponid'];
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
            'couponid' =>$l_rst['couponid'],
            'title' =>$l_rst['title'],
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
        $thumb=pdo_fetchcolumn("select thumb from ".tablename('hxq_newywym_award')." where id={$tokendata['awardid']}");
        $tokendata['thumb']=$_W['attachurl'].$thumb;
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
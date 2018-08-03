<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';
if (empty($_SESSION['oauth_openid'])){
    $msg=array('errno'=>1,'message'=>'系统设置有误');
    include $this->template('grant');
    exit;
}
if (!$_SESSION['token'][$_GPC['token']]){
    $msg=array('errno'=>1,'message'=>'出错拉！');
    include $this->template('error');
    exit;
}
unset($_SESSION['token'][$_GPC['token']]);
$tokendata=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
if (empty($tokendata)){
    $msg=array('errno'=>1,'message'=>'出错拉！');
    include $this->template('error');
    exit;
}
$act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$tokendata['actid']}");
$act['thumbs']=$_W['attachurl'].$act['thumbs'];
$act['faninfoarr']=iunserializer($act['faninfoarr']);
$act['page']=iunserializer($act['page']);
$act['blacklistgroups']=iunserializer($act['blacklistgroups']);
$page=$act['page']['pagesetting'];
$coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and actid={$act['id']}",array(),'id');
foreach($coupons as $key=>&$row){
    $row['grantfantotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    $row['granttotal']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']}");
    $row['starttime']=date('Y-m-d');
    $row['endtime']=date('Y-m-d');
}
$mycoupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
$mytokens=pdo_fetchall('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');

$_M['act']=$act;

$this->dooauth();
if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
    $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
                exit;
}
if (!empty($tokendata['isgrant'])){
    if ($tokendata['oauth_openid']!=$_SESSION['oauth_openid']){
        $msg=array('errno'=>6,'message'=>"奖品已被领取");
    }else{
        $msg=array('errno'=>7,'message'=>"奖品已经被您领取");
    }
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
                exit;
}

if (!empty($act['focusfollow'])&&empty($_W['fans']['follow'])){
    if ($_W['account']['level']==4){
        $account=Weaccount::create($_W['account']);
        $scene_base=100000000;
        $scene_id=$_GPC['tk'];
        $qrcid=$scene_base+$scene_id;
        pdo_query('delete from '.tablename('qrcode')." where uniacid=$uniacid and model=1 and qrcid={$qrcid}");
        $data=array("expire_seconds"=>604800,
            "action_name"=>"QR_SCENE",
            "action_info"=>array("scene"=>array("scene_id"=>$qrcid))
        );
        $qrcode=$account->barCodeCreateDisposable($data);
        if (!is_error($qrcode)){
            $record=array(
                'uniacid'=>$uniacid,
                'acid'=>$uniacid,
                'type'=>'scene',
                'extra'=>0,
                'qrcid'=>$qrcid,
                'scene_str'=>'',
                'name'=>$this->modulename.$scene_id,
                'keyword'=>$this->modulename.$scene_id,
                'model'=>1,
                'ticket'=>$qrcode['ticket'],
                'url'=>$qrcode['url'],
                'expire'=>1200,
                'createtime'=>TIMESTAMP,
                'status'=>1
            );
            pdo_insert('qrcode',$record);
            $qrurl='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket'];
            $message='长按关注公众号即可领奖。';
        }else{
            $qrurl=$_W['account']['qrcode'];
            $message='长按关注公众号即可领奖。';
        }
        $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }else{
        $qrurl=$_W['account']['qrcode'];
        $message='长按关注公众号，发送口令即可领奖！';
        $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }
}
$wxzfparams=$this->module['config']['pay'];
if (empty($wxzfparams)){
    $msg=error(1,'微信支付参数未配置，请在模块设置里配置！');
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
                exit;
}else{
    foreach ($wxzfparams as $key=>$item){
        if (empty($item)){
            $msg=error(1,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('grant');
            }else{
                include $this->template('grant1');
            }
            exit;
        }
    }
}
if ($tokendata['type']==1){
    require_once MODULE_ROOT .'/inc/qyfk.php';
    $redpack=new redpack();
    $params=array(
        'mch_appid'=>$wxzfparams['appid'],
        'mchid'=>$wxzfparams['mchid'],
        'apikey'=>$wxzfparams['apikey'],
        'certdir'=>$wxzfparams['certdir'],
        'sslcert'=>$wxzfparams['sslcert'],
        'sslkey'=>$wxzfparams['sslkey'],
        'rootca'=>$wxzfparams['rootca'],
        'openid'=>$tokendata['oauth_openid'],
        'amount'=>$tokendata['redpack']*100,
        'spbill_create_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
        'desc'=>$wxzfparams['remark']
    );
$rp_rst=$redpack->send($params);
if (is_error($rp_rst)){
    $message='系统错误 '.$rp_rst['message'];
    $hmessage='请稍后扫码重试';
    pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$message,'err_code_des'=>$message),array('id'=>$tokendata['id']));
    $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
    exit;
}else{
        if($rp_rst['result_code']!='SUCCESS'){
            $message='系统错误 '.$rp_rst['err_code_des'];
            $hmessage='请稍后扫码重试';
            if ($this->module['config']['notice']['tplid']&&$this->module['config']['notice']['tplopenids']){
                if ($rp_rst['err_code']=='NOTENOUGH'){
                    $remark='需要登录微信支付商户平台充值';
                }elseif ($rp_rst['err_code']=='NO_AUTH'){
                    $remark='粉丝需要向微信客服解锁账户';
                }else{
                    $remark='请按照警告名称进行解决';
                }
                $postdata=array(
                    'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$tokendata['nickname'].' 中奖金额 '.$tokendata['redpack']),
                    'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                    'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                    'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                );
                sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
        }
        pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$tokendata['id']));
        ///pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('grant');
        }else{
            include $this->template('grant1');
        }
                    exit;
    }else{
        $message='领取成功';
        //pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk']));
        pdo_update('hxq_newywym_token',array('isgrant'=>1,'send_listid'=>$rp_rst['send_listid']),array('id'=>$tokendata['id']));
        pdo_query('delete from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and tk='{$tokendata['tk']}' and id!=".$tokendata['id']);
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('grant');
        }else{
            include $this->template('grant1');
        }
                    exit;
    }
}
}elseif($tokendata['type']==2){
    require_once MODULE_ROOT .'/inc/redpack.php';
    $redpack=new redpack();
    $params=array(
        'type'=>2,
        'appid'=>$wxzfparams['appid'],
        'mchid'=>$wxzfparams['mchid'],
        'send_name'=>$wxzfparams['send_name'],
        'act_name'=>$wxzfparams['act_name'],
        'apikey'=>$wxzfparams['apikey'],
        'certdir'=>$wxzfparams['certdir'],
        'sslcert'=>$wxzfparams['sslcert'],
        'sslkey'=>$wxzfparams['sslkey'],
        'rootca'=>$wxzfparams['rootca'],
        're_openid'=>$tokendata['oauth_openid'],
        'total_amount'=>$tokendata['redpack']*100,
        'total_num'=>$tokendata['total_num'],
        'amt_type'=>'ALL_RAND',
        'wishing'=>'恭喜您在'.$act['act_name'].'中抽中了红包',
        'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
        'remark'=>$wxzfparams['remark']
    );
    $rp_rst=$redpack->send($params);
    if (is_error($rp_rst)){
        $message='系统错误 '.$rp_rst['message'];
        $hmessage='请稍后扫码重试';
        pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$message,'err_code_des'=>$message),array('id'=>$tokendata['id']));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('grant');
        }else{
            include $this->template('grant1');
        }
                    exit;
    }else{
        if($rp_rst['result_code']!='SUCCESS'){
                $message='系统错误 '.$rp_rst['err_code_des'];
                $hmessage='请稍后扫码重试';
                if ($this->module['config']['notice']['tplid']&&$this->module['config']['notice']['tplopenids']){
                    if ($rp_rst['err_code']=='NOTENOUGH'){
                        $remark='需要登录微信支付商户平台充值';
                    }elseif ($rp_rst['err_code']=='NO_AUTH'){
                        $remark='粉丝需要向微信客服解锁账户';
                    }else{
                        $remark='请按照警告名称进行解决';
                    }
                    $postdata=array(
                        'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$tokendata['nickname'].' 中奖金额 '.$tokendata['redpack']),
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
            pdo_update('hxq_newywym_token',array('err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$tokendata['id']));
            //pdo_delete('hxq_newywym_token',array('id'=>$tokendata['id']));
            //pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
            $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
                include $this->template('grant');
            }else{
                include $this->template('grant1');
            }
                        exit;
        }else{
            $message='领取成功';
            pdo_update('hxq_newywym_token',array('isgrant'=>1,'send_listid'=>$rp_rst['send_listid']),array('id'=>$tokendata['id']));
            pdo_query('delete from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and tk='{$tokendata['tk']}' and id!=".$tokendata['id']);
             
            $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
                include $this->template('grant');
            }else{
                include $this->template('grant1');
            }
                        exit;
        }
    }  
}elseif($tokendata['type']==3){
    load()->model('mc');
    $tokendata['credit1']=intval($tokendata['credit1']);
    $c_rst=mc_credit_update($fan['uid'],'credit1',$tokendata['credit1'],array($fan['uid'],$act['name'].'中奖'.$tokendata['credit1'].'会员积分',$this->modulename));
    if (!empty($c_rst)){
        $tokendata['err_code']=$c_rst['message'];
        $message='领取成功';
        pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokendata['id']));
        pdo_query('delete from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and tk='{$tokendata['tk']}' and id!=".$tokendata['id']);
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
    }else{
        $message='领取失败';
        $hmessage='请稍后扫码重试';
        pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>''),array('id'=>$tokendata['id']));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('grant');
        }else{
            include $this->template('grant1');
        }
                    exit;
    }
}elseif($tokendata['type']==4){
   // pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk'],'oauth_openid'=>$_SESSION['oauth_openid']));
    pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokendata['id']));
    pdo_query('delete from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and tk='{$tokendata['tk']}' and id!=".$tokendata['id']);
    $message='领取成功';
    $hmessage='打开个人中心>礼品栏目 查看或兑换奖品';
    $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
                exit;
}elseif($tokendata['type']==5){
    load()->classs('coupon');
    $clscoupon = new coupon($_W['acid']);
    $time=TIMESTAMP;
    $ticket=$clscoupon->getCardTicket();
    $params=array($tokendata['weixincouponid'],$time);
    $signature = $clscoupon->SignatureCard($params);
    $cardext=array('timestamp'=>$time,'signature'=>$signature);
    $cardext=json_encode($cardext);
    //$result['awardurl']=$this->createmobileurl('my',array('actid'=>$activity['id'],'id'=>pdo_insertid()));
    pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokendata['id']));
    pdo_query('delete from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and tk='{$tokendata['tk']}' and id!=".$tokendata['id']);
    $message='领取成功';
    $hmessage='请在弹出的卡券页面领取';
    $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }else{
        include $this->template('grant1');
    }
                exit;
}
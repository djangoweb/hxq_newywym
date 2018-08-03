<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';
$this->checkact();
$act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
$act['thumbs']=$_W['attachurl'].$act['thumbs'];
$act['page']=iunserializer($act['page']);
$page=$act['page']['pagesetting'];
function wrapspace($v){
    return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
}
$page['desc']=array_map('wrapspace',$page['desc']);

$_M['act']=$act;
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
        $this->dooauth();
if (empty($_SESSION['oauth_openid'])){
    $msg=array('errno'=>1,'message'=>'请使用微信扫码，参与活动！');
    include $this->template('cash');
    exit();
}
if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
    $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
    include $this->template('cash') ;
    exit;
}
$wxzfparams=$this->module['config']['pay'];
foreach ($wxzfparams as $key=>$item){
    if (empty($item)){
        $msg=error(1,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
        include $this->template('cash') ;
        exit();
    }
}
$fan=pdo_fetch('select * from '.tablename('hxq_newywym_fan')." where uniacid={$_W['uniacid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
if (empty($fan)){
    $msg=error(1,'您还未参与活动，无法提现！');
    include $this->template('cash') ;
    exit;
}
if ($act['blacklist']&&!empty($fan)){
    $member=mc_fetch($fan['uid'],array('groupid'));
    if (in_array($member['groupid'],$act['blacklistgroups'])){
        $msg=array('errno'=>8,'message'=>"无效扫码！");//黑名单限制，为了好听用尚未开始的字眼
        include $this->template('index_new');
        exit;
    }
}
$fanrps=pdo_fetchall('select redpack,id from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and actid={$act['id']} and type=1 and isgrant=1 and istx=0 and oauth_openid='{$_SESSION['oauth_openid']}'",array(),'id');
$fanrps=array_map('array_shift',$fanrps);
$totalrp=array_sum($fanrps);
$rpjkeys=array_keys($fanrps);
/* if (!empty($act['getcashleast'])){
    if ($totalrp<$act['getcashleast']){
        $message="您当前积累的红包金额{$totalrp}元";
        $rpmessage="积累满{$act['getcashleast']}元才可提现";
        $hmessage='请再接再厉';
        $msg=array('errno'=>1,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        include $this->template('cash') ;
        exit();
    }
}else{ */
    if ($totalrp<1){
        $message="当前积累金额{$totalrp}元";
        $hmessage="积累满1.00元可提现,请再接再励";
        $msg=array('errno'=>1,'message'=>$message,'hmessage'=>$hmessage);
        include $this->template('cash') ;
        exit();
    }
/* } */
    foreach ($rpjkeys as $item){
        pdo_update('hxq_newywym_token',array('istx'=>1),array('id'=>$item));
    }
    if ($wxzfparams['paytype']==1){
        require_once MODULE_ROOT .'/inc/redpack.php';
        $redpack=new redpack();
        $params=array(
            'type'=>1,
            'appid'=>$wxzfparams['appid'],
            'mchid'=>$wxzfparams['mchid'],
            'send_name'=>$wxzfparams['send_name'],
            'act_name'=>$wxzfparams['act_name'],
            'apikey'=>$wxzfparams['apikey'],
            'certdir'=>$wxzfparams['certdir'],
            'sslcert'=>$wxzfparams['sslcert'],
            'sslkey'=>$wxzfparams['sslkey'],
            'rootca'=>$wxzfparams['rootca'],
            're_openid'=>$fan['oauth_openid'],
            'total_amount'=>$totalrp*100,
            'total_num'=>1,
            'wishing'=>$act['name'].'红包提现！',
            'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'remark'=>$wxzfparams['remark']
        );
    }else{
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
            'openid'=>$fan['oauth_openid'],
            'amount'=>$totalrp*100,
            'spbill_create_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'desc'=>$wxzfparams['remark']
        );
    }
    $rp_rst=$redpack->send($params);
    if (is_error($rp_rst)){
        $rpmessage='系统错误 '.$rp_rst['message'];
        $rpmessage=$rp_rst['message'];
        $hmessage='已通知管理员，请稍后在个人中心提现红包';
        foreach ($rpjkeys as $item){
            pdo_update('hxq_newywym_token',array('istx'=>0),array('id'=>$item));
        }
        $msg=array('errno'=>1,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        include $this->template('cash');
        exit;
    }else{
            if($rp_rst['result_code']!='SUCCESS'){
                $message='系统错误,红包提现失败';
                $rpmessage=$rp_rst['err_code_des'];
                $hmessage='已通知管理员，请稍后在个人中心提现红包';
                if ($this->module['config']['notice']['tplid']&&$this->module['config']['notice']['tplopenids']){
                    if ($rp_rst['err_code']=='NOTENOUGH'){
                        $remark='您需要登录微信支付商户平台充值';
                    }elseif ($rp_rst['err_code']=='NO_AUTH'){
                        $remark='需要粉丝通过阅读活动说明自行解决';
                    }else{
                        $remark='请按照警告原因进行解决';
                    }
                    $postdata=array(
                        'first'=>array('value'=>$act['name'].'['.$message.'] 提现人 '.$fan['nickname'].' 提现金额 '.$totalrp),
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
            foreach ($rpjkeys as $item){
                pdo_update('hxq_newywym_token',array('istx'=>0),array('id'=>$item));
            }
            $msg=array('errno'=>1,'message'=>$message,'hmessage'=>$hmessage);
            include $this->template('cash');
            exit();
            }
    foreach ($rpjkeys as $item){
        pdo_update('hxq_newywym_token',array('send_listid'=>$rp_rst['send_listid'],'cashtime'=>TIMESTAMP),array('id'=>$item));
    }
    $data=ARRAY(
        'uniacid'=>$uniacid,
        'actid'=>$act['actid'],
        'cateid'=>$act['cateid'],
        'nickname'=>$_W['fans']['nickname'],
        'uid'=>$_W['fans']['uid'],
        'openid'=>$_SESSION['openid'],
        'oauth_openid'=>$_SESSION['oauth_openid'],
        'avatar'=>$_W['fans']['tag']['avatar'],
        'awardedids'=>iserializer($rpjkeys),
        'totalredpack'=>$totalrp,
        'addtime'=>TIMESTAMP,
        'addip'=>gethostbyname($_SERVER["SERVER_NAME"]),
        );
    pdo_insert('hxq_newywym_cash',$data);
    $totalrp=format_number($totalrp);
    $message='您一共提现红包'.$totalrp.'元';
    $hmessage='提现成功';
    $msg=array('errno'=>1,'message'=>$message,'hmessage'=>$hmessage);
    include $this->template('cash') ;
    exit();
    }
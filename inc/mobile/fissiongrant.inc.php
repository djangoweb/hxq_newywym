<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';
if (empty($_SESSION['oauth_openid'])){
    $msg=array('errno'=>1,'message'=>'系统设置有误');
    include $this->template('error');
    exit;
}
    if (empty($_GPC['fid'])){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$_GPC['fid']}");
    if (empty($fission)){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    if (!empty($act['fisnarea'])&&empty($fission['checkarea'])){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    if ($fission['oauth_openid']!=$_SESSION['oauth_openid']){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$fission['cateid']}");
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$fission['actid']}");
    if ($act['getfaninfo']){
        $this->dooauth();
    }
    
    $act['page']=iunserializer($act['page']);
    $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
    $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
    $page=$act['page']['pagesetting'];
    function wrapspace($v){
        return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
    }
    $page['desc']=array_map('wrapspace',$page['desc']);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and enable=1  and endtime>unix_timestamp()",array(),'id');
    foreach($coupons as &$row){
        $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    }
    unset($row);
    if ($act['fisnawardtype']){
        $isscanawarded=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and actid={$act['actid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
        if ($isscanawarded){
            $msg=array('errno'=>2,'message'=>'已扫码领奖');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('fission');
            }elseif ($act['page']['template']=='activity_v2'){
                include $this->template('fission1');
            }
            exit;
        }
    }
    $isawarded=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$act['id']} and isgrant=1 and oauth_openid='{$_SESSION['oauth_openid']}'");
    if ($isawarded){
        $msg=array('errno'=>2,'message'=>'已领裂变奖');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fissiongrant1');
        }
        exit;
    }
    $ptotalgrant=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and pid={$fission['pid']} and isgrant=1");
    if ($ptotalgrant>=$act['fissionnum']){
        $msg=array('errno'=>2,'message'=>'来迟了一步，奖品已经被人领走。');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fissiongrant1');
            
        }
        exit;
    }
    $pfission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$fission['pid']}");
    $award=pdo_fetch("select * from ".tablename('hxq_newywym_fisnaward')." where id={$fission['awardid']}");
    $awarded=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_fission')." where isgrant=1 and awardid={$fission['awardid']}");
    if ($award['total']<=$awarded){
        $msg=array('errno'=>2,'message'=>"奖品已经被领完");
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fissiongrant1');
        }
        exit;
    }
if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
    $msg=error(2,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
    include $this->template('error');
    exit;
}
//查看一下运行状态
$fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
if (empty($fan)){
    $msg=array('errno'=>2,'message'=>'参数错误');
 if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
}
$wxzfparams=$this->module['config']['pay'];
if (empty($wxzfparams)){
    $msg=error(2,'微信支付参数未配置，请在模块设置里配置！');
 if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
        exit;
}
$wxzfparams['act_name']=cutstr($act['name'],8);
$wxzfparams['send_name']=cutstr($biz['name'],8);
$wxzfparams['wishing']=str_replace('#act_name#',$act['name'],$wxzfparams['wishing']);
$wxzfparams['remark']=cutstr($act['name'].'备注',8);
//var_dump($wxzfparams);
/* 
if (!$_SESSION['token'][$_GPC['token']]){
    $msg=array('errno'=>1,'message'=>'出错拉！');
    include $this->template('error');
    exit;
}
unset($_SESSION['token'][$_GPC['token']]); */
if ($fission['type']==1){
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
            'sslcert'=>$wxzfparams['sslcert'],
            'sslkey'=>$wxzfparams['sslkey'],
            'rootca'=>$wxzfparams['rootca'],
            're_openid'=>$fission['oauth_openid'],
            'total_amount'=>$fission['redpack']*100,
            'total_num'=>1,
            'wishing'=>$wxzfparams['wishing'],
            'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'remark'=>$wxzfparams['remark']
        );
        if ($wxzfparams['scene_id']){
            $params['scene_id']=$wxzfparams['scene_id'];
        }
    }elseif ($wxzfparams['paytype']==2){
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
            'openid'=>$fission['oauth_openid'],
            'amount'=>$fission['redpack']*100,
            'spbill_create_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'desc'=>$wxzfparams['remark']
        );
    }elseif($wxzfparams['paytype']==3){
        require_once MODULE_ROOT .'/inc/mch_redpack.php';
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
            're_openid'=>$fission['oauth_openid'],
            'total_amount'=>$fission['redpack']*100,
            'total_num'=>1,
            'wishing'=>$wxzfparams['wishing'],
            'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'remark'=>$wxzfparams['remark']
        );

    }
$rp_rst=$redpack->send($params);
if (is_error($rp_rst)){
    $message='系统错误 '.$rp_rst['message'].'<br>请稍后扫码重试';
    $msg=array('errno'=>5,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
}else{
        if($rp_rst['result_code']!='SUCCESS'){
            $message='系统错误 '.$rp_rst['err_code_des'].'<br>请稍后扫码重试';
            if ($this->module['config']['notice']['tplid']&&$this->module['config']['notice']['tplopenids']){
                if ($rp_rst['err_code']=='NOTENOUGH'){
                    $remark='需要登录微信支付商户平台充值';
                }elseif ($rp_rst['err_code']=='NO_AUTH'){
                    $remark='粉丝需要向微信客服解锁账户';
                }else{
                    $remark='请按照警告名称进行解决';
                }
                $postdata=array(
                    'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$fission['nickname'].' 中奖金额 '.$fission['redpack']),
                    'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                    'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                    'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                );
                sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
            }
        ///pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
        $msg=array('errno'=>5,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
    }else{
            if ($wxzfparams['paytype']!=2){
            $hmessage='请及时领取红包';
            }
            else{
            $hmessage='红包已存入您的零钱';
            }
        //pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk']));
        pdo_update('hxq_newywym_fission',array('isgrant'=>1),array('id'=>$fission['id']));
        $account=weaccount::create($_W['account']);
        $postdata=array("touser"=>$pfission['openid'],"msgtype"=>"text","text"=>array('content'=>urlencode('['.$biz['name'].'] 好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'在'.$act['name'].'活动中领取了您的分享红包'.$fission['redpack']."元。")));
        $rst=$account->sendCustomNotice($postdata);
        if (is_error($rst)){
        $postdata=array(
                    'first'=>array('value'=>'['.$biz['name'].'] 好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'领取了您的分享红包','color'=>'#FF0000'),
                    'keyword1'=>array('value'=>$act['name'],'color'=>'#FF0000'),
                    'keyword2'=>array('value'=>$fission['redpack'].'元','color'=>'#FF0000'),
                    'keyword3'=>array('value'=>date('Y-m-d H:i:s',$fission['addtime']),'color'=>'#FF0000'),
                    'keyword4'=>array('value'=>'已兑奖','color'=>'#FF0000'),
                    'remark'=>array('value'=>'','color'=>'#FF0000'),
                );
        $rst=$account->sendTplNotice($pfission['openid'],$this->module['config']['notice']['dokeeptplid'],$postdata,'','');
        }
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
         if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
    }
}
}elseif($fission['type']==3){
    load()->model('mc');
    $fission['credit1']=intval($fission['credit1']);
    $c_rst=mc_credit_update($fan['uid'],'credit1',$fission['credit1'],array($fan['uid'],$act['name'].'中奖'.$fission['credit1'].'会员积分',$this->modulename));
    
    if (!empty($c_rst)){
        $message=$fission['credit1'].'积分';
        $hmessage="";
        pdo_update('hxq_newywym_fission',array('isgrant'=>1),array('id'=>$fission['id']));
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
    }else{
        $message='领取失败'.'<br>请稍后扫码重试';
        $msg=array('errno'=>5,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
    }
}elseif($fission['type']==5){
    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and enable=1 and id={$fission['couponid']} and endtime>unix_timestamp()");
    if (empty($coupon)){
        $message='领取失败'.'优惠券不存在';
        $msg=array('errno'=>5,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
            include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('fissiongrant1');
        }
    }else{
    $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
    $data=array(
        'openid'=>$_SESSION['openid'],
        'oauth_openid'=>$_SESSION['oauth_openid'],
        'uniacid'=>$uniacid,
        'actid'=>$fission['actid'],
        'cateid'=>$fission['cateid'],
        'come'=>'3',
        'couponid'=>$coupon['id'],
        'name'=>$coupon['name'],
        'type'=>$coupon['type'],
        'addtime'=>TIMESTAMP,
        'nickname'=>$_W['fans']['nickname'],
        'avatar'=>$_W['fans']['avatar'],
        'addip'=>getip(),
        'bizid'=>$fission['bizid'],
        'fid'=>$fission['id'],
       'scanid'=> $fission['scanid'],
    );
    pdo_insert('hxq_newywym_coupongrant',$data);$coupongrantid=pdo_insertid();
    pdo_update('hxq_newywym_fission',array('isgrant'=>1),array('id'=>$fission['id']));
    
        $message=$coupon['description'];
        $umessage=$coupons[$tokendata['couponid']]['requirement'];
        $hmessage='请凭此微信到<span class="text-red">'.$biz['name'].'</span>兑换';
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage,'umessage'=>$umessage);
        $account=weaccount::create($_W['account']);
        $url=$_W['siteroot'].'app/'.$this->createmobileurl('coupon',array('id'=>$coupongrantid,'cateid'=>$cate['id']));
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
                'actid'=>$act['id'],
                'cateid'=>$cate['id'],
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
        $postdata=array("touser"=>$pfission['openid'],"msgtype"=>"text","text"=>array('content'=>urlencode('['.$biz['name'].'] 好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'在'.$act['name'].'活动中领取了您的分享 '.$coupon['name'].' '.$coupon['value'].' '.$_M['coupontypes'][$coupon['type']])));
        $rst=$account->sendCustomNotice($postdata);
        if (is_error($rst)){
            $postdata=array(
                        'first'=>array('value'=>'['.$biz['name'].'] 好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'领取了您的分享红包','color'=>'#FF0000'),
                        'keyword1'=>array('value'=>$act['name'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>$coupon['name'].' '.$coupon['value'].' '.$_M['coupontypes'][$coupon['type']],'color'=>'#FF0000'),
                        'keyword3'=>array('value'=>date('Y-m-d H:i:s',$fission['addtime']),'color'=>'#FF0000'),
                        'keyword4'=>array('value'=>'截止 '.date('Y-m-d',$coupon['endtime']),'color'=>'#FF0000'),
                        'remark'=>array('value'=>$coupon['requirement'],'color'=>'#FF0000'),
                    );
            $rst=$account->sendTplNotice($pfission['openid'],$this->module['config']['notice']['dofissiontplid'],$postdata,'','');
        }
        if ($act['page']['template']=='activity_v1'){
           include $this->template('fissiongrant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('fissiongrant1');
        }
    }
}
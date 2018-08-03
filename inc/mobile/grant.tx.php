<?php
    if($tokendata['type']==1){
        $fanrps=pdo_fetchall('select redpack,id from '.tablename('hxq_ywym_token')." where uniacid=$uniacid and actid={$_M['act']['id']} and type=1 and enable=1 and isgrant=0 and oauth_openid='{$_SESSION['oauth_openid']}' ",array(),'id');
        $fanrps=array_map('array_shift',$fanrps);
        $totalrp=array_sum($fanrps);
        $rpjkeys=array_keys($fanrps);
        if ($totalrp<1){
            $msg=array('errno'=>0,'message'=>"恭喜您领取了红包！",'hmessage'=>'待累计红包大于1元后，可在我的红包提现。');
        }else{
            $msg=array('errno'=>0,'message'=>'恭喜您领取了红包！','hmessage'=>'累计红包'.$totalrp.'元，您可在我的红包提现。');
        }
     if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            $rec=array(
                'uniacid'=>$uniacid,
                'actid'=>$cate['actid'],
                'pdtid'=>$cate['pdtid'],
                'cateid'=>$cate['id'],
                'redpack'=>0,
                'credit1'=>$act['credit1'],
                'openid'=>$fan['openid'],
                'oauth_openid'=>$fan['oauth_openid'],
                'nickname'=>$fan['nickname'],
                'avatar'=>$fan['avatar'],
                'sex'=>$fan['sex'],
                'tk'=>$_GPC['tk'],
                'isgrant'=>0,
                'addtime'=>TIMESTAMP,
            );
            if (empty($c_rst)){
                pdo_insert('hxq_ywym_give',$rec);
                $cmsg=array('errno'=>1,'message'=>'赠送'.$act['credit1'].'个积分失败，请先注册会员再在 我的红包>积分中奖记录 领取！');
                include $this->template('lottery.tx');
                exit();
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_ywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送您'.$act['credit1'].'个积分！');
        } 
        include $this->template('lottery.tx');
        exit();
    }elseif($tokendata['type']==2){
        pdo_update('hxq_ywym_token',array('isgrant'=>1),array('id'=>$tokenid));
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
        if($rp_rst['result_code']!='SUCCESS'){
                $message='红包发送失败';
                if ($rp_rst['err_code']=='NO_AUTH'){
                    $rpmessage='微信帐号异常，发放有风险';
                    $hmessage='金额已为您保存。请按活动说明中的方法解除帐号异常后，在个人中心里领取红包。';
                }else{
                    $rpmessage='系统出错了';
                    $hmessage='已通知管理员，金额已为您保存。待系统恢复后，请在个人中心里领取红包。';
                }

                if ($this->module['config']['notice']['tplid']&&$this->module['config']['notice']['tplopenids']){
                    if ($rp_rst['err_code']=='NOTENOUGH'){
                        $remark='您需要登录微信支付商户平台充值。';
                    }elseif ($rp_rst['err_code']=='NO_AUTH'){
                        $remark='需要粉丝通过阅读活动说明自行解决。';
                    }else{
                        $remark='请按照警告名称进行解决';
                    }
                    $postdata=array(
                        'first'=>array('value'=>$_M['act']['name'].'['.$message.'] 中奖人 '.$tokendata['nickname'].' 中奖金额 '.$tokendata['redpack']),
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
            pdo_update('hxq_ywym_token',array('isgrant'=>0,'err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$tokenid));
            
            $message=cutstr($message,80);
            //pdo_delete('hxq_ywym_token',array('id'=>$tokenid));
               // pdo_delete('hxq_ywym_token',array('id'=>$tokenid));
            $msg=array('errno'=>1,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
            include $this->template('lottery.tx');
            exit();
        }
        pdo_update('hxq_ywym_token',array('send_listid'=>$rp_rst['send_listid']),array('id'=>$tokenid));
            if (!empty($_W['fans']['follow'])){
                $msg=array('errno'=>0,'message'=>"红包领取成功。");
            
                }else{$msg=array('errno'=>0,'message'=>"红包领取成功。",'qrurl'=>$_W['account']['qrcode']);
            
                }
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            $rec=array(
                'uniacid'=>$uniacid,
                'actid'=>$cate['actid'],
                'pdtid'=>$cate['pdtid'],
                'cateid'=>$cate['id'],
                'redpack'=>0,
                'credit1'=>$act['credit1'],
                'openid'=>$fan['openid'],
                'oauth_openid'=>$fan['oauth_openid'],
                'nickname'=>$fan['nickname'],
                'avatar'=>$fan['avatar'],
                'sex'=>$fan['sex'],
                'tk'=>$_GPC['tk'],
                'isgrant'=>0,
                'addtime'=>TIMESTAMP,
            );
            if (empty($c_rst)){
                pdo_insert('hxq_ywym_give',$rec);
                $cmsg=array('errno'=>1,'message'=>'赠送'.$act['credit1'].'个积分失败，请先注册会员再在 我的红包>积分中奖记录 领取！');
                include $this->template('lottery.tx');
                exit();
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_ywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送您'.$act['credit1'].'个积分！');
        } 
        include $this->template('lottery.tx');
        exit();
    }elseif($tokendata['type']==3){
	    load()->model('mc');
	    $tokendata['credit1']=intval($tokendata['credit1']);
        $c_rst=mc_credit_update($fan['uid'],'credit1',$tokendata['credit1'],array($fan['uid'],$act['name'].'中奖'.$tokendata['credit1'].'会员积分',$this->modulename));
        if (!empty($c_rst)){
            $tokendata['err_code']=$c_rst['message'];
            pdo_update('hxq_ywym_token',array('isgrant'=>1),array('id'=>$tokenid));
            $msg=array('errno'=>0,'message'=>'积分已发放到您的个人帐号。');
        }else{
            $msg=array('errno'=>1,'message'=>'积分发放失败，请先注册会员再在 我的红包>积分中奖记录 领取！');
            include $this->template('lottery.tx');
            exit;
        }
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            $rec=array(
                'uniacid'=>$uniacid,
                'actid'=>$cate['actid'],
                'pdtid'=>$cate['pdtid'],
                'cateid'=>$cate['id'],
                'redpack'=>0,
                'credit1'=>$act['credit1'],
                'openid'=>$fan['openid'],
                'oauth_openid'=>$fan['oauth_openid'],
                'nickname'=>$fan['nickname'],
                'avatar'=>$fan['avatar'],
                'sex'=>$fan['sex'],
                'tk'=>$_GPC['tk'],
                'isgrant'=>0,
                'addtime'=>TIMESTAMP,
            );
            if (empty($c_rst)){
                pdo_insert('hxq_ywym_give',$rec);
                $cmsg=array('errno'=>1,'message'=>'赠送'.$act['credit1'].'个积分失败，请先注册会员再在 我的红包>积分中奖记录 领取！');
                include $this->template('lottery.tx');
                exit();
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_ywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送您'.$act['credit1'].'个积分！');
        } 
        include $this->template('lottery.tx');
        exit();
    }elseif($tokendata['type']==4){
        pdo_update('hxq_ywym_token',array('isgrant'=>1),array('id'=>$tokenid));
        //pdo_update('hxq_ywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk'],'oauth_openid'=>$_SESSION['oauth_openid']));
        $message='优惠金额：'.$tokendata['swprice'];
        $rpmessage='';
        $hmessage='在个人中心点击代驾券二维码给代驾人员扫码核销即可享受优惠';
        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            $rec=array(
                'uniacid'=>$uniacid,
                'actid'=>$cate['actid'],
                'pdtid'=>$cate['pdtid'],
                'cateid'=>$cate['id'],
                'redpack'=>0,
                'credit1'=>$act['credit1'],
                'openid'=>$fan['openid'],
                'oauth_openid'=>$fan['oauth_openid'],
                'nickname'=>$fan['nickname'],
                'avatar'=>$fan['avatar'],
                'sex'=>$fan['sex'],
                'tk'=>$_GPC['tk'],
                'isgrant'=>0,
                'addtime'=>TIMESTAMP,
            );
            if (empty($c_rst)){
                pdo_insert('hxq_ywym_give',$rec);
                $cmsg=array('errno'=>1,'message'=>'赠送'.$act['credit1'].'个积分失败，请先注册会员再在 我的红包>积分中奖记录 领取！');
                include $this->template('lottery.tx');
                exit();
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_ywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送您'.$act['credit1'].'个积分！');
        } 
        include $this->template('lottery.tx');
        exit();
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
        $msg=array('errno'=>0,'message'=>'恭喜您获得了微信卡券');
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            if (empty($c_rst)){
                $cmsg=array('errno'=>1,'message'=>'赠送'.$act['credit1'].'个积分失败，请先注册会员再在 我的红包>积分中奖记录 领取。！');
                include $this->template('lottery');
                exit();
            }else{
                $rec=array(
                    'uniacid'=>$uniacid,
                    'actid'=>$cate['actid'],
                    'pdtid'=>$cate['pdtid'],
                    'cateid'=>$cate['id'],
                    'type'=>3,
                    'redpack'=>0,
                    'credit1'=>$act['credit1'],
                    'openid'=>$fan['openid'],
                    'oauth_openid'=>$fan['oauth_openid'],
                    'nickname'=>$fan['nickname'],
                    'avatar'=>$fan['avatar'],
                    'sex'=>$fan['sex'],
                    'tk'=>$_GPC['tk'],
                    'isgrant'=>1,
                    'addtime'=>TIMESTAMP,
                );
                pdo_insert('hxq_ywym_give',$rec);
                $cmsg=array('errno'=>0,'message'=>'系统赠送您'.$act['credit1'].'个积分！');
            }
        }
        include $this->template('lottery');
        exit();
    }
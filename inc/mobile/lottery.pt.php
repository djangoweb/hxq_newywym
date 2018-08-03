<?php 
    if ($tokendata['type']==1){
    pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokenid));
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
            're_openid'=>$tokendata['oauth_openid'],
            'total_amount'=>$tokendata['redpack']*100,
            'total_num'=>1,
            'wishing'=>'恭喜您在'.$act['name'].'中抽中了红包',
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
            'openid'=>$tokendata['oauth_openid'],
            'amount'=>$tokendata['redpack']*100,
            'spbill_create_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'desc'=>$wxzfparams['remark']
        );
    }
        $rp_rst=$redpack->send($params);
        if (is_error($rp_rst)){
            $rpmessage='系统错误 '.$rp_rst['message'];
            $hmessage='金额已保存，稍后请在个人中心领取。';
            pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$rp_rst['message'],'err_code_des'=>$rp_rst['message']),array('id'=>$tokenid));
            $msg=array('errno'=>9,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
        }else{
            //var_dump($rp_rst);
            if($rp_rst['result_code']!='SUCCESS'){
                $rpmessage='系统错误 '.$rp_rst['err_code_des'];
                $hmessage='金额已保存，稍后请在个人中心里领取';
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
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rpmessage,'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
                pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$tokenid));
                $msg=array('errno'=>9,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }else{
                $rpmessage='';
                $hmessage='红包发送成功';
                pdo_update('hxq_newywym_token',array('send_listid'=>$rp_rst['send_listid']),array('id'=>$tokenid));
                if (!empty($_W['fans']['follow'])){
                    $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                }else{
                    $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                }
            }
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
                pdo_insert('hxq_newywym_give',$rec);
                $cmsg=array('errno'=>9,'message'=>'赠送积分失败，请注册会员后在个人中心领取');
               if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_newywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送 '.$act['credit1'].'个积分');
        } 
        if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
    }elseif($tokendata['type']==2){
        pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokenid));
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
                're_openid'=>$tokendata['oauth_openid'],
                'total_amount'=>$tokendata['redpack']*100,
                'total_num'=>1,
                'wishing'=>'恭喜您在'.$act['act_name'].'中抽中了红包',
                'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
                'remark'=>$wxzfparams['remark']
            );
        $rp_rst=$redpack->send($params);
        if (is_error($rp_rst)){
            $rpmessage='系统错误 '.$rp_rst['message'];
            $hmessage='金额已保存，稍后请在个人中心领取';
            pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$rpmessage,'err_code_des'=>$rpmessage),array('id'=>$tokenid));
            $msg=array('errno'=>9,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
        }else{
            if($rp_rst['result_code']!='SUCCESS'){
                    $rpmessage='系统错误 '.$rp_rst['err_code_des'];
                    $hmessage='金额已保存，稍后请在个人中心里领取';
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
                            'keyword1'=>array('value'=>'微信服务器返回:'.$rpmessage,'color'=>'#FF0000'),
                            'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                            'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                        );
                        sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                    }
                pdo_update('hxq_newywym_token',array('isgrant'=>0,'err_code'=>$rp_rst['err_code'],'err_code_des'=>$rpmessage),array('id'=>$tokenid));
                // pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
                $msg=array('errno'=>9,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
            }else{
                $rpmessage='';
                $hmessage='红包发送成功。';
                pdo_update('hxq_newywym_token',array('send_listid'=>$rp_rst['send_listid']),array('id'=>$tokenid));
                if (!empty($_W['fans']['follow'])){
                        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                    }else{ 
                        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
                
                    }
            }
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
                pdo_insert('hxq_newywym_give',$rec);
                $cmsg=array('errno'=>9,'message'=>'赠送积分失败，请注册会员后在个人中心领取');
                if ($act['page']['template']=='activity_v1'){
                    include $this->template('lottery');
                }else{
                    include $this->template('lottery1');
                }
                exit;
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_newywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送 '.$act['credit1'].'积分');
        } 
        if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
    }elseif($tokendata['type']==3){
	    load()->model('mc');
	    $tokendata['credit1']=intval($tokendata['credit1']);
        $c_rst=mc_credit_update($fan['uid'],'credit1',$tokendata['credit1'],array($fan['uid'],$act['name'].'中奖'.$tokendata['credit1'].'会员积分',$this->modulename));
        if (!empty($c_rst)){
            $tokendata['err_code']=$c_rst['message'];
            pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokenid));
            $rpmessage='';
            $hmessage='积分领取成功';
            $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        }else{
            $rpmessage='';
            $hmessage='积分领取失败,请注册会员后在个人中心领取';
            $msg=array('errno'=>9,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
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
                pdo_insert('hxq_newywym_give',$rec);
                $cmsg=array('errno'=>9,'message'=>'赠送积分失败，请注册会员后在个人中心领取');
                if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
                }else{
                    include $this->template('lottery1');
                }
                exit;
            }
            $rec['isgrant']=1;
            pdo_insert('hxq_newywym_give',$rec);
            $cmsg=array('errno'=>0,'message'=>'系统赠送 '.$act['credit1'].'积分');
        }
        if ($act['page']['template']=='activity_v1'){
            include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }elseif($tokendata['type']==4){
        pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$tokenid));
        $rpmessage='';
        $hmessage='个人中心点击礼品，出示给工作人员即可兑换';
        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            if (empty($c_rst)){
                $cmsg=array('errno'=>9,'message'=>'赠送积分失败，请注册会员后在个人中心领取');
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
                pdo_insert('hxq_newywym_give',$rec);
                $cmsg=array('errno'=>0,'message'=>'系统赠送 '.$act['credit1'].'积分');
            }
        }
        if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
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
        $rpmessage='';
        $hmessage='';
        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        if ($act['gcredit1']){
            $c_rst=mc_credit_update($fan['uid'],'credit1',$act['credit1'],array($fan['uid'],$act['name'].'附赠+'.$act['credit1'].'会员积分',$this->modulename));
            if (empty($c_rst)){
                $cmsg=array('errno'=>9,'message'=>'赠送积分失败，请注册会员后在个人中心领取');
            if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
            }else{
                include $this->template('lottery1');
            }
            exit;
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
                pdo_insert('hxq_newywym_give',$rec);
                $cmsg=array('errno'=>0,'message'=>'系统赠送 '.$act['credit1'].'积分');
            }
        }
        if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }elseif($tokendata['type']==100){
        $message='谢谢参与';
        $rpmessage='';
        $hmessage='很遗憾，请再接再厉';
        $msg=array('errno'=>0,'message'=>$message,'rpmessage'=>$rpmessage,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
                include $this->template('lottery');
        }else{
            include $this->template('lottery1');
        }
        exit;
    }
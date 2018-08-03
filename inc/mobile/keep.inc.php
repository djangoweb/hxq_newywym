<?php
    require_once MODULE_ROOT.'/inc/mobileinit.php';
    if (empty($_SESSION['oauth_openid'])){
        $msg=array('errno'=>1,'message'=>'系统设置有误');
        include $this->template('error');
        exit;
    }
    $op=in_array($_GPC['op'],array('default','dokeep'))?$_GPC['op']:'display';
    function dokeep($cate,$act,$config){
    global $_W,$_GPC,$_M;
    $uniacid=$_W['uniacid'];
    $keep=pdo_fetch('select * from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and id={$_GPC['stlid']}");
    $keep['totalclick']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$_GPC['stlid']}");
    $arr=explode('-',$act['keepprice']);
    $arr[0]=number_format($arr[0],1);
    $arr[0]=intval($arr[0]*10);
    $arr[1]=number_format($arr[1],1);
    $arr[1]=intval($arr[1]*10);
    $redpack=mt_rand($arr[0],$arr[1]);
    $redpack=$redpack/10;
    $redpack=$redpack/(1+$act['keepclicktimes']);
        $keepclick=array(
            'uniacid'=>$_W['uniacid'],
            'actid'=>$cate['actid'],
            'cateid'=>$cate['id'],
            'bizid'=>$act['bizid'],
            'openid'=>$_SESSION['openid'],
            'nickname'=>$_W['fans']['nickname'],
            'addtime'=>TIMESTAMP,
            'avatar'=>$_W['fans']['tag']['avatar'],
            'oauth_openid'=>$_SESSION['oauth_openid'],
            'sid'=>$_GPC['stlid'],
            'redpack'=>$redpack,
            'addip'=>getip(),
        );
        pdo_insert('hxq_newywym_shtmlineclick',$keepclick);
        $account=weaccount::create($_W['account']);
        pdo_update('hxq_newywym_shtmline',array('redpack'=>$keep['redpack']+$redpack),array('id'=>$_GPC['stlid']));
        $wxzfparams=$config['pay'];
        $wxzfparam=true;
        if (empty($wxzfparams)){
            $wxzfparam=false;
        }else{
            foreach ($wxzfparams as $key=>$item){
                if (empty($item)){
                    $wxzfparam=false;
                }
            }
        }
        if ($wxzfparam!==false&&$keep['isgrant']==0&&$keep['totalclick']+1>=$act['keepclicktimes']){
            $redpack_=$redpack;
            $wxzfparams['act_name']=cutstr($act['name'],8);
            $wxzfparams['send_name']=cutstr($biz['name'],8);
            $wxzfparams['wishing']=str_replace('#act_name#',$act['name'],$wxzfparams['wishing']);
            $wxzfparams['remark']=cutstr($act['name'].'备注',8);
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
                    're_openid'=>$keep['oauth_openid'],
                    'total_amount'=>($keep['redpack']+$redpack_)*100,
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
                    'openid'=>$keep['oauth_openid'],
                    'amount'=>($keep['redpack']+$redpack_)*100,
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
                    're_openid'=>$keep['oauth_openid'],
                    'total_amount'=>($keep['redpack']+$redpack_)*100,
                    'total_num'=>1,
                    'wishing'=>$wxzfparams['wishing'],
                    'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
                    'remark'=>$wxzfparams['remark']
                );
        
            }
            $rp_rst=$redpack->send($params);
            if (is_error($rp_rst)){
                $message='系统错误 '.$rp_rst['message'].'<br>请稍后扫码重试';
                //echo $message;
                pdo_update('hxq_newywym_shtmline',array('err_code'=>$message,'err_code_des'=>$message),array('id'=>$keep['id']));
                $msg=error(1,"发红包失败");
            }else{
                //echo 'af';
                if($rp_rst['result_code']!='SUCCESS'){
        
                    //echo 'af1';
                    $message='系统错误 '.$rp_rst['err_code_des'].'<br>请稍后扫码重试';
                    //echo $message;
                    if ($config['notice']['tplid']&&$config['notice']['tplopenids']){
                        if ($rp_rst['err_code']=='NOTENOUGH'){
                            $remark='需要登录微信支付商户平台充值';
                        }elseif ($rp_rst['err_code']=='NO_AUTH'){
                            $remark='粉丝需要向微信客服解锁账户';
                        }else{
                            $remark='请按照警告名称进行解决';
                        }
                        $postdata=array(
                            'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$scandata['nickname'].' 中奖金额 '.$scandata['redpack']),
                            'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                            'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                            'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                        );
                        sendtplmessage($postdata,$config['notice']['tplid'],$config['notice']['tplopenids']);
                    }
                    pdo_update('hxq_newywym_shtmline',array('err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$keep['id']));
                    ///pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
        
                    $msg=error(1,"发红包失败");
                }else{
                    if ($wxzfparams['paytype']!=2){
                        $hmessage='请及时领取红包';
                    }
                    else{
                        $hmessage='红包已存入您的零钱';
                    }
                    /*     echo $hmessage;
                     print_r($keep); */
                    pdo_update('hxq_newywym_shtmline',array('isgrant'=>1),array('id'=>$keep['id']));
                    $msg=error(0,"发红包成功");
                }
        
            }
            $url=$_W['siteroot'].'app/'.murl('entry',array('do'=>'awardedrcd','cateid'=>$cate['id'],'type'=>1,'m'=>'hxq_qrcode'));
            $postdata=array("touser"=>$keep['openid'],"msgtype"=>"text","text"=>array('content'=>urlencode('您的好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'为您的红包增值了'.$redpack_."元".($msg['errno']==0?'红包已发放':'红包发放失败')."<a href='".$url."'>点击查看</a>")));
            $rst=$account->sendCustomNotice($postdata);
            if (is_error($rst)){
                //$ff=file_put_contents('ab.txt',var_export($postdate,true).var_export($rst,true).$keep['openid']);
                $postdata=array(
                    'first'=>array('value'=>'您的好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'为您的红包增值,'.($msg['errno']==0?'红包已发放':'红包发放失败'),'color'=>'#FF0000'),
                    'keyword1'=>array('value'=>'增值了'.$redpack_.'元','color'=>'#FF0000'),
                    'keyword2'=>array('value'=>$act['name'],'color'=>'#FF0000'),
                    'remark'=>array('value'=>'点击查看增值红包','color'=>'#FF0000'),
                );
                $rst=$account->sendTplNotice($keep['openid'],$config['notice']['dokeeptplid'],$postdata,$url,'');
            }
            return $msg;
        }else{
            $url=$_W['siteroot'].'app/'.murl('entry',array('do'=>'awardedrcd','cateid'=>$cate['id'],'type'=>1,'m'=>'hxq_qrcode'));
            $postdata=array("touser"=>$keep['openid'],"msgtype"=>"text","text"=>array('content'=>urlencode('您的好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'为您的红包增值了'.$redpack."元<a href='".$url."'>点击查看</a>")));
            $rst=$account->sendCustomNotice($postdata);
            if (is_error($rst)){
                //$ff=file_put_contents('ab.txt',var_export($postdate,true).var_export($rst,true).$keep['openid']);
                $postdata=array(
                    'first'=>array('value'=>'您的好友'.($_W['fans']['nickname']?$_W['fans']['nickname']:cutstr($_SESSION['oauth_openid'],5)).'为您的红包增值','color'=>'#FF0000'),
                    'keyword1'=>array('value'=>'增值了'.$redpack.'元','color'=>'#FF0000'),
                    'keyword2'=>array('value'=>$act['name'],'color'=>'#FF0000'),
                    'remark'=>array('value'=>'点击查看增值红包','color'=>'#FF0000'),
                );
                $rst=$account->sendTplNotice($keep['openid'],$config['dokeeptplid'],$postdata,$url,'');
            }
            return error(0,"增值成功");
        }
    }
    
    if ($op=='dokeep'){
        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
        if ($act['getfaninfo']){
            $this->dooauth();
        }
        $act['page']=iunserializer($act['page']);
        $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
        $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
        if (empty($act['keeparea'])){
            $act['keeparea']=array();
        }else{
        if (strexists($act['keeparea'],',')){
            $act['keeparea']=explode(',',$act['keeparea']);
        }else{
            $act['keeparea']=array($act['keeparea']);
        }
        }
        if ($_SESSION['openid']){
            pdo_update('hxq_newywym_shtmline',array('openid'=>$_SESSION['openid']),array('oauth_openid'=>$_SESSION['oauth_openid'],'actid'=>$act['id']));
        }
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
        if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
            $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
            die(json_encode($msg));
        }
        $keep=pdo_fetch('select * from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and id={$_GPC['stlid']}");
        if (empty($keep['enable'])){
            $msg=array('errno'=>1,'message'=>'参数错误','umessage'=>'无效的领奖链接');
            die(json_encode($msg));
        }
        if ($keep['oauth_openid']==$_SESSION['oauth_openid']){
            $msg=error(1,"验证失败");
            die(json_encode($msg));
        }
        $keep['totalclick']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$_GPC['stlid']}");
        if ($keep['totalclick']>=$act['keepclicktimes']){
            $msg=error(1,"验证失败");
            die(json_encode($msg));
        }
        $keepclick=pdo_fetch('select * from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$_GPC['stlid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
        if (!empty($keepclick)){
            $msg=error(1,"验证失败");
            die(json_encode($msg));
        }
        if ($this->cfg['pay']['payby']==1){
            $wxzfparams=$this->cfg['pay'];
            $wxzfparams['certfile']=MODULE_ROOT.'/cert/'.$_W['uniacid'].'.'.'apiclient_cert.pem';
            $wxzfparams['keyfile']=MODULE_ROOT.'/cert/'.$_W['uniacid'].'.'.'apiclient_key.pem';
            if ($this->cfg['pay']['rootca']){
                $wxzfparams['rootfile']=MODULE_ROOT.'/cert/'.$_W['uniacid'].'.'.'rootca.pem';
            }
        }elseif ($this->cfg['pay']['payby']==2){
            $wxzfparams=$this->globalcfg['pay'];
            $wxzfparams['certfile']=MODULE_ROOT.'/cert/global/apiclient_cert.pem';
            $wxzfparams['keyfile']=MODULE_ROOT.'/cert/global/apiclient_key.pem';
            if ($this->globalcfg['pay']['rootca']){
                $wxzfparams['rootfile']=MODULE_ROOT.'/cert/global/rootca.pem';
            }
        }
        $msg=dokeep($cate,$act,$this->module['config']);
        die(json_encode($msg));
    }
    
    if ($op=='display'){
    $urls=parse_url($_W['siteroot']);
    $scheme=$urls['scheme'];
    if (empty($_GPC['stlid'])){
        $msg=array('errno'=>1,'message'=>'参数错误');
        include $this->template('error');
        exit;
    }
    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
    if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
        $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
        include $this->template('error');
        exit;
    }
    $keep=pdo_fetch('select * from '.tablename('hxq_newywym_shtmline')." where uniacid={$_W['uniacid']} and id='{$_GPC['stlid']}'");
    $act['page']=iunserializer($act['page']);
    $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
    $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
    if (empty($act['keeparea'])){
        $act['keeparea']=array();
    }else{
        if (strexists($act['keeparea'],',')){
            $act['keeparea']=explode(',',$act['keeparea']);
        }else{
            $act['keeparea']=array($act['keeparea']);
        }
    }
    $page=$act['page']['pagesetting'];
    function wrapspace($v){
        return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
    }
    $page['desc']=array_map('wrapspace',$page['desc']);
    if ($act['getfaninfo']){
        $this->dooauth();
    }
    if ($_SESSION['openid']){
        pdo_update('hxq_newywym_shtmline',array('openid'=>$_SESSION['openid']),array('oauth_openid'=>$_SESSION['oauth_openid'],'actid'=>$act['id']));
    }
        if (empty($keep['enable'])){
        $msg=array('errno'=>1,'message'=>'参数错误','umessage'=>'无效的领奖链接');
        if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
            $this->census($_W['siteurl']);
            header('location:'.$act['shareoutlink']);
            exit;
        }
        }
        if ($_SESSION['oauth_openid']==$keep['oauth_openid']){
            $msg=array('errno'=>1,'message'=>'自己点自己');
            if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
                $this->census($_W['siteurl']);
                header('location:'.$act['shareoutlink']);
                exit;
            }
        }
        $keepclick=pdo_fetch('select * from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$_GPC['stlid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
        if (!empty($keepclick)){
            $msg=array('errno'=>1,'message'=>'已增值');
            if ($_GPC['sharefrom']>1&&$act['shareoutlink']){
                $this->census($_W['siteurl']);
                header('location:'.$act['shareoutlink']);
                exit;
            }
        }
        $keep['totalclick']=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_shtmlineclick')." where uniacid={$_W['uniacid']} and sid={$_GPC['stlid']}");
        if ($keep['totalclick']>=$act['keepclicktimes']){
            $msg=array('errno'=>1,'message'=>'已增值完成');
            if ($act['shareoutlink']){
                $this->census($_W['siteurl']);
                header('location:'.$act['shareoutlink']);
                exit;
            }
        }
    if (!$act['keeparea']&&!is_error($msg)){
            $msg=dokeep($cate,$act,$this->module['config']);
            if ($act['shareoutlink']){
                $this->census($_W['siteurl']);
                header('location:'.$act['shareoutlink']);
                exit;
            }
    }
    //print_r($msg);
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and enable=1  and endtime>unix_timestamp()",array(),'id');
    foreach($coupons as &$row){
        $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
    }
    unset($row);
    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
    //原先有参与并中奖，显示中奖
   //有参与没中奖或奖品已发完 则提示 谢谢参与    
    include $this->template('keep');
    exit();
    }
    
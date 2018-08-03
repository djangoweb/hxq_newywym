<?php
class grant extends page{
public function main($op='display'){
global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
if (empty($_SESSION['oauth_openid'])){
    $msg=array('errno'=>1,'message'=>'系统设置有误');
    include $this->template('error');
    exit;
}
$scandata=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
if (empty($scandata)){
    $msg=array('errno'=>1,'message'=>'出错了！');
    include $this->template('error');
    exit;
}
if ($scandata['oauth_openid']!=$_SESSION['oauth_openid']){
    $msg=array('errno'=>1,'message'=>'出错了！');
    include $this->template('error');
    exit;
}
$this->dojump();
$act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$scandata['actid']}");
//$award=pdo_fetch('select * from '.tablename('hxq_newywym_award')." where uniacid={$_W['uniacid']} and id={$scandata['awardid']}");
$act['thumbs']=$_W['attachurl'].$act['thumbs'];
$act['faninfoarr']=iunserializer($act['faninfoarr']);
$act['page']=iunserializer($act['page']);
$act['blacklistgroups']=iunserializer($act['blacklistgroups']);
parent::$act=$act;
$page=$act['page']['pagesetting'];
$biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$scandata['bizid']}");
function wrapspace($v){
    return str_replace(array("\r\n","\r","\n",' '),array('<br>','<br>','<br>','&nbsp;'),$v);
}
$page['desc']=array_map('wrapspace',$page['desc']);
$coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and bizid={$act['bizid']} and endtime>unix_timestamp()",array(),'id');
foreach($coupons as &$row){
    $row['grant']=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and  couponid={$row['id']} and come=2 and oauth_openid='{$_SESSION['oauth_openid']}'");
}
$this->dooauth();
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
$fanawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}'  order by id desc");
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
$fandayawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']}  and oauth_openid='{$_SESSION['oauth_openid']}'  and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
if ($act['daymaxawarded']>0&&$fandayawarded>$act['daymaxawarded']){
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
$tokendata=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and tk={$scandata['tk']}");
if (!empty($tokendata['isgrant'])){
    if ($tokendata['oauth_openid']!=$_SESSION['oauth_openid']){
        $msg=array('errno'=>6,'message'=>"奖品已被领取");
    }else{
        $msg=array('errno'=>7,'message'=>"奖品已经被您领取");
    }
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }elseif ($act['page']['template']=='activity_v2'){
        include $this->template('grant1');
    }elseif ($act['page']['template']=='activity_v3'){
        include $this->template('grant2');
    }elseif ($act['page']['template']=='activity_v4'){
        include $this->template('grant3');
    }
    exit;
}
if ($scandata['type']==100){
    $msg=array('errno'=>1,'message'=>"出错了");
    include $this->template('error');
    exit;
}
$award=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where id={$scandata['awardid']}");
$awarded=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where isgrant=1 and awardid={$scandata['awardid']}");
if ($award['total']<=$awarded){
    $msg=array('errno'=>7,'message'=>"奖品已经被领完");
    if ($act['page']['template']=='activity_v1'){
        include $this->template('grant');
    }elseif ($act['page']['template']=='activity_v2'){
        include $this->template('grant1');
    }elseif ($act['page']['template']=='activity_v3'){
        include $this->template('grant2');
    }elseif ($act['page']['template']=='activity_v4'){
        include $this->template('grant3');
    }
    exit;
}
if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
    $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
                exit;
}
//查看一下运行状态
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
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
        exit;
    }else{
        $qrurl=$_W['account']['qrcode'];
        $message='长按关注公众号，发送口令即可领奖！';
        $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
        exit;
    }
}
$fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
if (empty($fan)){
    $params=array('bizid'=>$act['bizid'],'actid'=>$act['id'],'cateid'=>$scandata['cateid']);
    $result=$this->func('regfan',$params);
    if (is_error($result)){
        $msg=$result;
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
        exit;
    }
    $fan=$result['fan'];
}

    $result=$this->func('regactfan',$fan['id']);
$wxzfparams=$this->globalcfg['pay'];
if (empty($wxzfparams)){
    $msg=error(1,'微信支付参数未配置，请在模块设置里配置！');
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
                exit;
}else{
    foreach ($wxzfparams as $key=>$item){
        if (empty($item)){
            $msg=error(1,'微信支付参数错误，缺少['.$key.']字段，请在模块设置里完善！');
            if ($act['page']['template']=='activity_v1'){
               include $this->template('grant');
            }elseif ($act['page']['template']=='activity_v2'){
               include $this->template('grant1');
            }elseif ($act['page']['template']=='activity_v3'){
               include $this->template('grant2');
            }elseif ($act['page']['template']=='activity_v4'){
               include $this->template('grant3');
            }
            exit;
        }
    }
}
$wxzfparams['wishing']=str_replace('#act_name#',$act['name'],$wxzfparams['wishing']);
$lock=pdo_fetch("select * from ".tablename('hxq_newywym_lock')." where tk='{$scandata['tk']}'");
if  (!empty($lock)){
    if (!empty($lock['islock'])){
    $msg=array('errno'=>1,'message'=>"奖品已经被领取，请换一个二维码进行扫描！");
    include $this->template('error');
    exit;
    }else{
        pdo_update('hxq_newywym_lock',array('islock'=>'1'),array('tk'=>$scandata['tk']));
    }
}else{
    $data=array('tk'=>$scandata['tk'],'addtime'=>TIMESTAMP,'islock'=>1);
    pdo_insert('hxq_newywym_lock',$data);
}
if (class_exists('Memcached')){
    $mem=new Memcached();
    $mem->addServer($_W['setting']['memcache']['server'],$_W['setting']['memcache']['port']);
    if ($mem->get($scandata['tk'])=='lock'){
        $msg=array('errno'=>1,'message'=>'出错了！');
        include $this->template('error');
        exit;
    }
}
if (class_exists('Memcached')){
    $mem->set($scandata['tk'],'lock',120);
}
//var_dump($wxzfparams);
if ($scandata['type']==1){
    if ($biz['recharge']<$scandata['redpack']){
        $msg=array('errno'=>1,'message'=>'商户['.$biz['name'].']账户余额不足');
        if ($act['page']['template']=='activity_v1'){
               include $this->template('grant');
            }elseif ($act['page']['template']=='activity_v2'){
               include $this->template('grant1');
            }elseif ($act['page']['template']=='activity_v3'){
               include $this->template('grant2');
            }elseif ($act['page']['template']=='activity_v4'){
               include $this->template('grant3');
            }
    }else{
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
            're_openid'=>$scandata['oauth_openid'],
            'total_amount'=>$scandata['redpack']*100,
            'total_num'=>1,
            'wishing'=>$wxzfparams['wishing'],
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
            'openid'=>$scandata['oauth_openid'],
            'amount'=>$scandata['redpack']*100,
            'spbill_create_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
            'desc'=>$wxzfparams['wishing']
        );
    }
    $rp_rst=$redpack->send($params);
    if (is_error($rp_rst)){
        $message='系统错误 '.$rp_rst['message'].'<br>请稍后扫码重试';
        pdo_update('hxq_newywym_scan',array('err_code'=>$message,'err_code_des'=>$message),array('id'=>$scandata['id']));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
               include $this->template('grant');
            }elseif ($act['page']['template']=='activity_v2'){
               include $this->template('grant1');
            }elseif ($act['page']['template']=='activity_v3'){
               include $this->template('grant2');
            }elseif ($act['page']['template']=='activity_v4'){
               include $this->template('grant3');
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
                        'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$scandata['nickname'].' 中奖金额 '.$scandata['redpack']),
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
            pdo_update('hxq_newywym_scan',array('err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$scandata['id']));
            ///pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
            $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
               include $this->template('grant');
            }elseif ($act['page']['template']=='activity_v2'){
               include $this->template('grant1');
            }elseif ($act['page']['template']=='activity_v3'){
               include $this->template('grant2');
            }elseif ($act['page']['template']=='activity_v4'){
               include $this->template('grant3');
            }
        }else{
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
            //pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk']));
            $tokendata=array(
                    'uniacid'=>$uniacid,
                    'actid'=>$scandata['actid'],
                    'cateid'=>$scandata['cateid'],
                'bizid'=>$scandata['bizid'],
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
                    'addip'=>getip(),
                    'addtime'=>TIMESTAMP,
                    'scanid'=>$scandata['id'],
                    'send_listid'=>$rp_rst['send_listid']
            );
            pdo_insert('hxq_newywym_token',$tokendata);
            $tokendata['id']=pdo_insertid();
            pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
            $paylog=array(
                'uniacid'=>$uniacid,
                'actid'=>$act['id'],
                'bizid'=>$act['bizid'],
                'cateid'=>$scandata['cateid'],
                'oauth_openid'=>$_SESSION['oauth_openid'],
                'nickname'=>$tokendata['nickname'],
                'avatar'=>$tokendata['avatar'],
                'sendlistid'=>$tokendata['send_listid'],
                'money'=>$tokendata['redpack'],
                'abstract'=>"",
                'addtime'=>TIMESTAMP,
            );
            pdo_insert('hxq_newywym_paylog',$paylog);
	        pdo_update('hxq_newywym_biz',array('recharge'=>$biz['recharge']-$tokendata['redpack']),array('id'=>$biz['id']));
            if (class_exists('Memcached')){
            $mem->set($scandata['id'],'',60);
            }
            $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
            if ($act['page']['template']=='activity_v1'){
               include $this->template('grant');
            }elseif ($act['page']['template']=='activity_v2'){
               include $this->template('grant1');
            }elseif ($act['page']['template']=='activity_v3'){
               include $this->template('grant2');
            }elseif ($act['page']['template']=='activity_v4'){
               include $this->template('grant3');
            }
        }
    }}
}elseif($scandata['type']==2){
    if ($biz['recharge']<$scandata['redpack']){
        $msg=array('errno'=>1,'message'=>'商户['.$biz['name'].']账户余额不足');
        if ($act['page']['template']=='activity_v1'){
            include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
            include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
            include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
            include $this->template('grant3');
        }
    }else{
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
    're_openid'=>$scandata['oauth_openid'],
    'total_amount'=>$scandata['redpack']*100,
    'total_num'=>$scandata['total_num'],
    'amt_type'=>'ALL_RAND',
    'wishing'=>$wxzfparams['wishing'],
    'client_ip'=>gethostbyname($_SERVER["SERVER_NAME"]),
    'remark'=>$wxzfparams['remark']
    );
    $rp_rst=$redpack->send($params);
    if (is_error($rp_rst)){
        $message='系统错误 '.$rp_rst['message'].'<br>请稍后扫码重试';
        if (class_exists('Memcached')){
            $mem->set($_SESSION['oauth_openid'],'',60);
        }
        pdo_update('hxq_newywym_scan',array('err_code'=>$message,'err_code_des'=>$message),array('id'=>$scandata['id']));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
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
                        'first'=>array('value'=>$act['name'].'['.$message.'] 中奖人 '.$scandata['nickname'].' 中奖金额 '.$scandata['redpack']),
                        'keyword1'=>array('value'=>'微信服务器返回:'.$rp_rst['err_code_des'],'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
                        'remark'=>array('value'=>'解决办法:'.$remark,'color'=>'#FF0000'),
                    );
                    sendtplmessage($postdata,$this->module['config']['notice']['tplid'],$this->module['config']['notice']['tplopenids']);
                }
            pdo_update('hxq_newywym_scan',array('err_code'=>$rp_rst['err_code'],'err_code_des'=>$rp_rst['err_code_des']),array('id'=>$scandata['id']));
            //pdo_delete('hxq_newywym_token',array('id'=>$tokendata['id']));
            //pdo_delete('hxq_newywym_token',array('id'=>$tokenid));
            $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
        }else{
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
            if (class_exists('Memcached')){
                $mem->set($_SESSION['oauth_openid'],'',60);
            }
            $tokendata=array(
                'uniacid'=>$uniacid,
                 'actid'=>$scandata['actid'],
                'cateid'=>$scandata['cateid'],
                'bizid'=>$scandata['bizid'],
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
                'isgrant'=>1,
                'addip'=>getip(),
                'addtime'=>TIMESTAMP,
                'scanid'=>$scandata['id'],
                'send_listid'=>$rp_rst['send_listid']
            );
            pdo_insert('hxq_newywym_token',$tokendata);
            $tokendata['id']=pdo_insertid();
            pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
            $paylog=array(
                'uniacid'=>$uniacid,
                'actid'=>$act['id'],
                'bizid'=>$act['bizid'],
                'cateid'=>$scandata['cateid'],
                'oauth_openid'=>$_SESSION['oauth_openid'],
                'nickname'=>$tokendata['nickname'],
                'avatar'=>$tokendata['avatar'],
                'sendlistid'=>$tokendata['send_listid'],
                'money'=>$tokendata['redpack'],
                'addtime'=>TIMESTAMP,
            );
            pdo_insert('hxq_newywym_paylog',$paylog);
	        pdo_update('hxq_newywym_biz',array('recharge'=>$biz['recharge']-$tokendata['redpack']),array('id'=>$biz['id']));
            $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
        }
    }
    }
}elseif($scandata['type']==3){
    load()->model('mc');
    $scandata['credit1']=intval($scandata['credit1']);
    $c_rst=mc_credit_update($fan['uid'],'credit1',$scandata['credit1'],array($fan['uid'],$act['name'].'中奖'.$scandata['credit1'].'会员积分',$this->modulename));
    if (!empty($c_rst)){
        $scandata['err_code']=$c_rst['message'];
        $hmessage='';
        $tokendata=array(
            'uniacid'=>$uniacid,
             'actid'=>$scandata['actid'],
            'cateid'=>$scandata['cateid'],
            'bizid'=>$scandata['bizid'],
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
            'isgrant'=>1,
            'addip'=>getip(),
            'addtime'=>TIMESTAMP,
            'scanid'=>$scandata['id']
        );
        pdo_insert('hxq_newywym_token',$tokendata);
        $tokendata['id']=pdo_insertid();
        pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
    }else{
        $message='领取失败'.'<br>请稍后扫码重试';
        pdo_update('hxq_newywym_scan',array('isgrant'=>0,'err_code'=>''),array('id'=>$scandata['id']));
        $msg=array('errno'=>9,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
    }
}elseif($scandata['type']==4){
   // pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('tk'=>$_GPC['tk'],'oauth_openid'=>$_SESSION['oauth_openid']));
    $tokendata=array(
        'uniacid'=>$uniacid,
 'actid'=>$scandata['actid'],
        'cateid'=>$scandata['cateid'],
        'bizid'=>$scandata['bizid'],
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
        'isgrant'=>1,
        'addip'=>getip(),
        'addtime'=>TIMESTAMP,
        'scanid'=>$scandata['id']
    );
    pdo_insert('hxq_newywym_token',$tokendata);
    pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
    $hmessage='请凭此微信到<span class="text-red">'.$biz['name'].'</span>兑换奖品';
    $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
        if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
}elseif($scandata['type']==5){
    $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid",array(),'id');
    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and id={$scandata['couponid']}");
    $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
	        	$tokendata=array(
    	    'uniacid'=>$uniacid,
    	     'actid'=>$scandata['actid'],
                'cateid'=>$scandata['cateid'],
	        	    'bizid'=>$scandata['bizid'],
                'tk'=>$scandata['tk'],
                'openid'=>$scandata['openid'],
                'nickname'=>$scandata['nickname'],
                'avatar'=>$scandata['avatar'],
                'oauth_openid'=>$scandata['oauth_openid'],
                'awardid' =>$scandata['awardid'],
    	    'type' =>$scandata['type'],
    	    'couponid' =>$scandata['couponid'],
    	    'title' =>$scandata['title'],
    	    'redpack' =>$scandata['redpack'],
    	    'credit1' =>$scandata['credit1'],
    	    'total_num' =>$scandata['total_num'],
    	    'thumb' =>$scandata['thumb'],
    	    'isgrant'=>1,
    	    'addip'=>getip(),
    	    'addtime'=>TIMESTAMP,
    	    'scanid'=>$scandata['id']
    	);
    	pdo_insert('hxq_newywym_token',$tokendata);
    	$tokendata['id']=pdo_insertid();
    	$data=array(
	        'openid'=>$_SESSION['openid'],
	        'oauth_openid'=>$_SESSION['oauth_openid'],
	        'uniacid'=>$uniacid,
	        'actid'=>$act['id'],
	        'bizid'=>$coupon['bizid'],
	        'come'=>'1',
	        'couponid'=>$coupon['id'],
	        'name'=>$coupon['name'],
	        'type'=>$coupon['type'],
	        'addtime'=>TIMESTAMP,
	        'nickname'=>$_W['fans']['nickname'],
	        'avatar'=>$_W['fans']['avatar'],
	        'addip'=>getip(),
	        'tokenid'=>$tokendata['id']
	    );
    	pdo_insert('hxq_newywym_coupongrant',$data);
    	$coupongrantid=pdo_insertid();
    	pdo_update('hxq_newywym_scan',array('isgrant'=>1),array('id'=>$scandata['id']));
        $message=$coupon['description'];
        $hmessage='请凭此微信到<span class="text-red">'.$biz['name'].'</span>享受优惠';
        $msg=array('errno'=>0,'message'=>$message,'hmessage'=>$hmessage);
    if ($act['page']['template']=='activity_v1'){
           include $this->template('grant');
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('grant1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('grant2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('grant3');
        }
    }
    if (class_exists('Memcached')){
        $mem->set($scandata['tk'],'',600);
    }
    pdo_update('hxq_newywym_lock',array('islock'=>'0'),array('tk'=>$scandata['tk']));
    }
}
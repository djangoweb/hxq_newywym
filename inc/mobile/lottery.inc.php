<?php
    require_once MODULE_ROOT.'/inc/mobileinit.php';
    if (empty($_SESSION['oauth_openid'])){
        $msg=array('errno'=>2,'message'=>'系统设置有误');
        include $this->template('error');
        exit;
    }
    $tk=intval($_GPC['tk']);
    $vtk=addslashes($_GPC['vtk']);
    $msg=$this->checktoken($_M['slat'],$tk,$vtk);
    if (is_error($msg)&&$msg['errno']!=6&&$msg['errno']!=7&&$msg['errno']!=8){
        include $this->template('error') ;
        exit;
    }
    $cate=$msg['cate'];
    $act=$msg['act'];
    $act['page']=iunserializer($act['page']);
    $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
    $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
    $page=$act['page']['pagesetting'];
    $_M['act']=$act;
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
    if($act['lnum']>0){
        if ($act['ltype']==0){
            $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and  oauth_openid='{$_SESSION['oauth_openid']}'");
            if($totallottery>=$act['lnum']){
                $msg=error(5,'每活动限扫码抽奖'.$act['lnum'].'次！');
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
        }else{
            $totallottery=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$act['id']} and  oauth_openid='{$_SESSION['oauth_openid']}' and addtime<".strtotime(date('Y-m-d 23:59:59'))." and addtime>".strtotime(date('Y-m-d 00:00:00')));
            if($totallottery>=$act['lnum']){
                $msg=error(5,'每天限扫码抽奖'.$act['lnum'].'次！');
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
        }elseif ($act['page']['template']=='activity_v2'){
           include $this->template('lottery1');
        }elseif ($act['page']['template']=='activity_v3'){
           include $this->template('lottery2');
        }elseif ($act['page']['template']=='activity_v4'){
           include $this->template('lottery3');
        }
                exit;
            }else{
                $qrurl=$_W['account']['qrcode'];
                $message='长按关注公众号，发送口令即可领奖！';
                $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
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
$fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  and oauth_openid='{$_SESSION['oauth_openid']}'");
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
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid={$cate['id']} and disable=0  order by sort asc",array(),'id');
        foreach($awards as $key=>&$row){
            $row['thumb']=$_W['attachurl'].$row['thumb'];
        }
        unset($row);
        foreach ($awards as $key=>$row){
            $row['awarded']=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and awardid={$row['id']} order by id desc");
            if (empty($row['total'])||(!empty($row['total'])&&$row['awarded']>=$row['total'])){
                unset($awards[$key]);
            }elseif (!empty($awards[$key]['tokenids'])){
                $awards[$key]['tokenids']=explode(',',$awards[$key]['tokenids']);
                $awards[$key]['tokenids']=array_filter($awards[$key]['tokenids']);
        
            }
        }
        if ($act['lnum']>0&&$actawarded>$act['lnum']){
            $actawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$cate['actid']}  order by id desc");
            $msg=error(5,'今天的奖励已经送完，请明天继续！');
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
        $fanawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$cate['actid']} and oauth_openid='{$_SESSION['oauth_openid']}'  order by id desc");
        if ($act['maxawarded']>0&&$fanawarded>$act['maxawarded']){
            $msg=error(5,'您已达到本次活动扫码次数上限！');
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
        $fandayawarded=pdo_fetchcolumn('select count(*) from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$cate['actid']} and oauth_openid='{$_SESSION['oauth_openid']}'  and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
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
            'title' =>$l_rst['title'],
            'weixincouponid' =>$l_rst['weixincouponid'],
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
        $tokendata['thumb']=$_W['attachurl'].$tokendata['thumb'];
    }

    pdo_update('hxq_newywym_scan',array('status'=>1,'lotterytime'=>TIMESTAMP),array('id'=>$_GPC['scanid']));
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
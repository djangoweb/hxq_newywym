<?php
if (defined('IN_MOBILE')&&!in_array($_GPC['do'],array('checkposition','checkipposition'))){
        if (empty($_SESSION['oauth_openid'])){
            $chkrst=array('errno'=>1,'message'=>'模块设置有误');
            echo json_encode($chkrst);
            exit;
        }
        if ($act['area']){
            $addressencrypt=sha1('area'.$_SESSION['oauth_openid']);
            if($_COOKIE['addressencrypt']!=$addressencrypt){
                $chkrst=array('errno'=>1,'message'=>'该活动需要'.$act['area'].'粉丝才能参加');
                echo json_encode($chkrst);
                exit;
            }
        }
        if ($act['iparea']){
            $ipaddressencrypt=sha1('iparea'.$_SESSION['oauth_openid']);
            if($_COOKIE['ipaddressencrypt']!=$ipaddressencrypt){
                $chkrst=array('errno'=>1,'message'=>'该活动需要'.$act['iparea'].'粉丝才能参加');
                echo json_encode($chkrst);
                exit;
            }
        }
        if (!empty($act['ipblacklist'])){
            if (strexists($act['ipblacklist'],'|')){
                $arr_ipblack=explode('|',$act['ipblacklist']);
                foreach($arr_ipblack as $ipblack){
                    if ($ipblack==getip()){
                        $chkrst=array('errno'=>1,'message'=>'限制访问');
                        echo json_encode($chkrst);
                        exit;
                    }
                }
            }elseif ($act['ipblacklist']==getip()){
                $chkrst=array('errno'=>1,'message'=>'限制访问');
                echo json_encode($chkrst);
                exit;
            }
        }
        if ($act['starttime']>TIMESTAMP){
            $chkrst=array('errno'=>1,'message'=>'活动未开始:<br>'.date('Y-m-d H:i:s',$act['starttime']).'<br>至'.date('Y-m-d H:i:s',$act['endtime']));
            echo json_encode($chkrst);
            exit;
        }else{
            if($_GPC['do']=='award'){
                if ($act['endtime']>TIMESTAMP){
                    if (!$act['actexchange']){
                        $chkrst=array('errno'=>1,'message'=>'兑换奖品时间于'.date('Y-m-d H:i:s',$act['endtime']).'开始至'.date('Y-m-d H:i:s',$act['exendtime']).'，请在这个时间段内进行兑换。');
                        echo json_encode($chkrst);
                        exit;
                    }
                }elseif($act['exendtime']<TIMESTAMP){
                    $chkrst=array('errno'=>1,'message'=>'兑换奖品时间已于'.date('Y-m-d H:i:s',$act['exendtime']).'结束。');
                    echo json_encode($chkrst);
                    exit;
                }
                if ($act['exendtime']<TIMESTAMP){
                $chkrst=array('errno'=>1,'message'=>'集'.$act['cardname'].'已结束');
                echo json_encode($chkrst);
                exit;
                }
            }else{
                if($act['endtime']<TIMESTAMP){
                $chkrst=array('errno'=>1,'message'=>'集'.$act['cardname'].'已结束','redirect'=>$this->createmobileurl('award',array('actid'=>$act['id'])));
                echo json_encode($chkrst);
                exit;
                }
            }
        }
        if (!$_W['fans']['follow']){
            if ($act['focussubscribe']==1){
                if (in_array($_GPC['do'],array('index','award','grant','give'))){
                if ($_W['account']['level']>=4){
                    $account=Weaccount::create($_W['account']);
                    if (in_array($_GPC['do'],array('index','award'))){
                            $scene_id=1000000;
                            $keyword=$act['keyword'];
                        }elseif($_GPC['do']=='grant'){
                            $scene_id=2000000;
                            $keyword='hxq_clctcard_grant_'.$act['id'].'_2000000';
                        }elseif($_GPC['do']=='help'){
                            $scene_id=3000000+$_GPC['sid'];
                            $keyword='hxq_clctcard_help_'.$act['id'].'_'.$scene_id;
                        }elseif($_GPC['do']=='give'){
                            $scene_id=4000000+$_GPC['gid'];
                            $keyword='hxq_clctcard_give_'.$act['id'].'_'.$scene_id;
                    }
                    $qrcode=pdo_fetch('select * from '.tablename('qrcode')." where uniacid=$uniacid and keyword='{$keyword}' and qrcid=$scene_id");
                    if (!empty($qrcode)){
                        $chkrst=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket']);
                        echo json_encode($chkrst);
                        exit;
                    }else{
                        pdo_delete('qrcode',array('uniacid'=>$uniacid,'qrcid'=>$scene_id));
                        $data=array("expire_seconds"=>604800,
                        "action_name"=>"QR_SCENE",
                        "action_info"=>array("scene"=>array("scene_id"=>$scene_id))
                        );
                        $chkrst=$account->barCodeCreateDisposable($data);
                        if (!is_error($chkrst)){
                            $record=array(
                                'uniacid'=>$uniacid,
                                'acid'=>$uniacid,
                                'type'=>'scene',
                                'extra'=>0,
                                'qrcid'=>$scene_id,
                                'scene_str'=>'',
                                'name'=>$keyword,
                                'keyword'=>$keyword,
                                'model'=>1,
                                'ticket'=>$chkrst['ticket'],
                                'url'=>$chkrst['url'],
                                'expire'=>604800,
                                'createtime'=>TIMESTAMP,
                                'status'=>1
                            );
                            pdo_insert('qrcode',$record);
                            $chkrst=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$chkrst['ticket']);
                            echo json_encode($chkrst);
                            exit;
                        }
                    }
                }else{
                $chkrst=array('errno'=>3,'message'=>'未关注');
                echo json_encode($chkrst);
                exit;
                }
                }
            }elseif($act['focussubscribe']==2){
                if (in_array($_GPC['do'],array('index','award','help','grant','give'))){
                    if ($_W['account']['level']>=4){
                        $account=Weaccount::create($_W['account']);
                        if (in_array($_GPC['do'],array('index','award'))){
                            $scene_id=1000000;
                            $keyword=$act['keyword'];
                        }elseif($_GPC['do']=='grant'){
                            $scene_id=2000000;
                            $keyword='hxq_clctcard_grant_'.$act['id'].'_2000000';
                        }elseif($_GPC['do']=='help'){
                            $scene_id=3000000+$_GPC['sid'];
                            $keyword='hxq_clctcard_help_'.$act['id'].'_'.$scene_id;
                        }elseif($_GPC['do']=='give'){
                            $scene_id=4000000+$_GPC['gid'];
                            $keyword='hxq_clctcard_give_'.$act['id'].'_'.$scene_id;
                        }
                        $qrcode=pdo_fetch('select * from '.tablename('qrcode')." where uniacid=$uniacid and keyword='{$keyword}' and qrcid=$scene_id");
                        if (!empty($qrcode)){
                            $chkrst=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket']);
                            echo json_encode($chkrst);
                            exit;
                        }else{

                            pdo_delete('qrcode',array('uniacid'=>$uniacid,'qrcid'=>$scene_id));
                            $data=array("expire_seconds"=>604800,
                                "action_name"=>"QR_SCENE",
                                "action_info"=>array("scene"=>array("scene_id"=>$scene_id))
                            );
                            $chkrst=$account->barCodeCreateDisposable($data);
                            if (!is_error($chkrst)){
                                $record=array(
                                    'uniacid'=>$uniacid,
                                    'acid'=>$uniacid,
                                    'type'=>'scene',
                                    'extra'=>0,
                                    'qrcid'=>$scene_id,
                                    'scene_str'=>'',
                                    'name'=>$keyword,
                                    'keyword'=>$keyword,
                                    'model'=>1,
                                    'ticket'=>$chkrst['ticket'],
                                    'url'=>$chkrst['url'],
                                    'expire'=>604800,
                                    'createtime'=>TIMESTAMP,
                                    'status'=>1
                                );
                                pdo_insert('qrcode',$record);
                                $chkrst=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$chkrst['ticket']);
                                echo json_encode($chkrst);
                                exit;
                            }
                        }
                    }else{
                        $chkrst=array('errno'=>3,'message'=>'未关注');
                        echo json_encode($chkrst);
                        exit;
                    }
                }
            }
        }
}
<?php
if (defined('IN_MOBILE')&&!in_array($_GPC['do'],array('checkposition','checkipposition'))){
        if (empty($_SESSION['oauth_openid'])){
            $initdata[]=array('errno'=>1,'message'=>'模块设置有误');
        }
        if (!empty($act['ipblacklist'])){
            if (strexists($act['ipblacklist'],'|')){
                $arr_ipblack=explode('|',$act['ipblacklist']);
                foreach($arr_ipblack as $ipblack){
                    if ($ipblack==getip()){
                        $initdata[]=array('errno'=>1,'message'=>'限制访问');
                        break;
                    }
                }
            }elseif ($act['ipblacklist']==getip()){
                $initdata[]=array('errno'=>1,'message'=>'限制访问');
            }
        }
        if ($act['starttime']>TIMESTAMP){
            $initdata[]=array('errno'=>1,'message'=>'活动未开始:<br>'.date('Y-m-d H:i:s',$act['starttime']).'<br>至'.date('Y-m-d H:i:s',$act['endtime']));
        }else{
            if($_GPC['do']=='award'){
                if ($act['exendtime']<TIMESTAMP){
                $initdata[]=array('errno'=>1,'message'=>'集'.$act['cardname'].'已结束');
                }
            }else{
                if($act['endtime']<TIMESTAMP){
                $initdata[]=array('errno'=>1,'message'=>'集'.$act['cardname'].'已结束','redirect'=>$this->createmobileurl('award',array('actid'=>$act['id'])));
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
                            $initdata[]=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket']);
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
                                $initdata[]=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$chkrst['ticket']);
                            }
                        }
                    
                    }else{
                        $initdata[]=array('errno'=>3,'message'=>'未关注');
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
                            $initdata[]=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket']);
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
                                $initdata[]=array('errno'=>3,'message'=>"长按识别此二维码，再关注{$_W['account']['name']},即可参与活动!",'qrurl'=>'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$chkrst['ticket']);
                            }
                        }
                
                    }else{
                        $initdata[]=array('errno'=>3,'message'=>'未关注');
                    }
                }
            }
        }
}
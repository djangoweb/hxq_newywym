<?php
        function redirectaward(){
            Global $_W,$_GPC;
            $uniacid=$_W['uniacid'];
            $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
            if ($token['type']==1){
                header('location:'.mobileurl('awardedrcd',array('actid'=>$token['actid'],'type'=>'1')));
            }elseif($token['type']==2){
        
                header('location:'.mobileurl('awardedrcd',array('actid'=>$token['actid'],'type'=>'2')));
            }elseif($token['type']==3){
        
                header('location:'.mobileurl('awardedrcd',array('actid'=>$token['actid'],'type'=>'3')));
            }elseif($token['type']==4){
        
                header('location:'.mobileurl('awardedrcd',array('actid'=>$token['actid'],'type'=>'4')));
            }elseif($token['type']==5){
        
                header('location:'.mobileurl('awardedrcd',array('actid'=>$token['actid'],'type'=>'coupon')));
            }
        }
        
        function chkfollow(){
            global $_W,$_GPC,$_M;
            $uniacid=$_W['uniacid'];
            $act=page::$act;
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
                            'name'=>MODULE_NAME.$scene_id,
                            'keyword'=>MODULE_NAME.$scene_id,
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
                    exit(json_encode($msg));
                }else{
                    $qrurl=$_W['account']['qrcode'];
                    $message='长按关注公众号，发送口令即可领奖！';
                    $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
                    exit(json_encode($msg));
                }
            }else{
                die(json_encode(error(0,'验证通过')));
            }
        }
        
        function redirect(){
            global $_W,$_GPC,$_M;
            $act=page::$act;
            if (empty($act)){
                $msg=error(1,'参数错误') ;
                include $instance->template('error');
                exit();
            }
            $shareoutlink=$act['shareoutlink'];
            header('location:'.$shareoutlink);
            exit;
        }
        
        function census(){
            global $_W,$_GPC,$_M;
            $act=page::$act;
            dooauth();
            if (empty($_SESSION['oauth_openid'])){
                $msg=array('errno'=>2,'message'=>'系统设置有误');
                exit(json_encode($msg));
            }
            $guesturl=urldecode($_GPC['guesturl']);
            censusdata($guesturl);
        }
        
        function censusdata($guesturl=''){
            global $_W,$_GPC,$_M;
            //echo $guesturl;
            $act=page::$act;
            if (empty($act)){
                $msg=error(1,'出错了！');
                exit(json_encode($msg));
            }
            $arrurl=parse_url($guesturl);
            $querystring=$arrurl['query'];
            parse_str($querystring,$querys);
            if ($querys['comefrom']=='timeline'){
                $isclick=pdo_fetch('select * from '.tablename('hxq_newywym_click')." where type=2 and uniacid={$_W['uniacid']} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
                if ($isclick){
                    $isclick['total']++;
                    pdo_update('hxq_newywym_click',array('total'=>$isclick['total']),array('id'=>$isclick['id']));
                }else{
                    $data=array(
                        'uniacid'=>$_W['uniacid'],
                        'actid'=>$act['id'],
                        'type'=>2,
                        'cateid'=>0,
                        'oauth_openid'=>$_SESSION['oauth_openid'],
                        'nickname'=>$_W['fans']['nickname'],
                        'total'=>1,
                        'addtime'=>TIMESTAMP,
                        'addip'=>getip()
                    );
                    pdo_insert('hxq_newywym_click',$data);
                }
            }else{
                $isclick=pdo_fetch('select * from '.tablename('hxq_newywym_click')." where type=1 and uniacid={$_W['uniacid']} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
                if ($isclick){
                    $isclick['total']++;
                    pdo_update('hxq_newywym_click',array('total'=>$isclick['total']),array('id'=>$isclick['id']));
                }else{
                    $data=array(
                        'uniacid'=>$_W['uniacid'],
                        'actid'=>$act['id'],
                        'type'=>1,
                        'cateid'=>0,
                        'oauth_openid'=>$_SESSION['oauth_openid'],
                        'nickname'=>$_W['fans']['nickname'],
                        'total'=>1,
                        'addtime'=>TIMESTAMP,
                        'addip'=>getip()
                    );
                    pdo_insert('hxq_newywym_click',$data);
                }
            }
        }
        
        function qrcode($url){
            Global $_W,$_GPC;
            $act=page::$act;
            require MODULE_ROOT.'/inc/phpqrcode.php';
            $qrcode_rdir="images/{$_W['uniacid']}/".MODULE_NAME.'/images/'.date('Y').'/'.date('m').'/';
            $filename=random(30).'.png';
            load()->func('file');
            if (empty($url)){
                $url=$_GPC['url'];
            }
            mkdirs(ATTACHMENT_ROOT.$qrcode_rdir);
            //echo ATTACHMENT_ROOT.$qrcode_rdir;
            QRcode::png(urldecode($url),ATTACHMENT_ROOT.$qrcode_rdir.$filename, $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);
            return $qrcode_rdir.$filename;
        }
        
        function share(){
            global $_W,$_GPC,$_M;
            $act=page::$act;
            $isshare=pdo_fetch('select * from '.tablename('hxq_newywym_share')." where uniacid={$_W['uniacid']} and actid={$_GPC['actid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            if (!empty($isshare)){
                $isshare['total']++;
                pdo_update('hxq_newywym_share',array('total'=>$isshare['total']),array('id'=>$isshare['id']));
            }else{
                $data=array(
                    'uniacid'=>$_W['uniacid'],
                    'actid'=>$act['id'],
                    'cateid'=>$_GPC['cateid'],
                    'oauth_openid'=>$_SESSION['oauth_openid'],
                    'nickname'=>$_W['fans']['nickname'],
                    'total'=>1,
                    'addtime'=>TIMESTAMP,
                    'addip'=>getip()
                );
                pdo_insert('hxq_newywym_share',$data);
            }
            $msg=error(0,'提交成功') ;
            exit(json_encode($msg));
        }
        

     function regfan($params_=array()){
    	    Global $_W,$_GPC;
    	    $uid=$_W['fans']['uid'];
    	    load()->model('mc');
    	    $act=page::$act;
    	    if (!empty($act['focusfollow'])&&empty($uid)){  
        	        regmember();
        	        if (is_error($reinfo)){
        	            return($reinfo);
        	        }
        	        $uid=$reinfo['uid'];
        	}
    	    $uid=intval($uid);
    	    $record = array(
    	        'bizid' => $params_['bizid'],
    	        'pdtid' => $params_['pdtid'],
    	        'cateid' => $params_['cateid'],
    	        'actid' => $params_['actid'],
    	        'uid' =>$uid, 
    	        'openid' => $_W['fans']['openid'],
    	        'oauth_openid' => $_SESSION['oauth_openid'],
    	        'uniacid' => $_W['uniacid'],
    	        'avatar' => $_W['fans']['tag']['avatar'],
    	        'nickname' =>  $_W['fans']['nickname'],
    	        'sex'=>$_W['fans']['tag']['sex'],
    	        'province'=>$_W['fans']['tag']['province'],
    	        'city'=>$_W['fans']['tag']['city'],
    	        'addtime' => TIMESTAMP,
    	    );
    	    pdo_insert('hxq_newywym_fan', $record);
    	    $record['id']=pdo_insertid();
    	    $actfanid=regactfan($record['id']);
    	    $reinfo=array('errno'=>0,'message'=>'登记成功','fan'=>$record,'actfanid'=>$actfanid);
    	    return($reinfo);
    	}
    	
    	
        
        function regmember(){
            global $_W,$_GPC;
            $uniacid=$_W['uniacid'];
            $acid=$_W['acid'];
            if (empty($_W['fans']['follow'])){
                $reinfo=array('errno'=>1,'message'=>'未关注');
                return $reinfo;
            }
            $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
            $fields = array(
                'uniacid' => $_W['uniacid'],
                'email' => md5($_W['fans']['openid']) . '@we7.cc',
                'groupid' => $default_groupid,
                'mobile' => '',
                'salt' => random(8),
                'password' => md5($fields['email'] . $fields['salt'] . $_W['config']['setting']['authkey']),
                'nickname' => $_W['fans']['nickname'],
                'avatar' => $_W['fans']['tag']['avatar'],
                'gender' => $_W['fans']['tag']['sex'],
                'nationality' => $_W['fans']['tag']['country'],
                'resideprovince' => $_W['fans']['tag']['province'] . '省',
                'residecity' => $_W['fans']['tag']['city'] . '市',
                'createtime' => TIMESTAMP
            );
            pdo_insert('mc_members', $fields);
            $uid = pdo_insertid();
            if (!empty($uid)){
                if (pdo_update('mc_mapping_fans', array('uid' => $uid), array('openid' => $_SESSION['openid']))){
                    return array('errno'=>0,'message'=>'注册会员成功','uid'=>$uid);
                }else{
                    return array('errno'=>2,'message'=>'更新粉丝表信息失败');
                }
            }else{
                return array('errno'=>2,'message'=>'注册会员失败');
            };
        }
        
        function regactfan($fanid){
            global $_W,$_GPC;
            $act=page::$act;
            $actfan=pdo_fetch("select * from ".tablename('hxq_newywym_activity_fan')." where uniacid={$_W['uniacid']}  and actid={$act['id']} and fanid='{$fanid}'");
            if (empty($actfan)){
                $data=array(
                      'uniacid'=>$_W['uniacid'],
                      'actid'=>$act['id'],
                      'bizid'=>$act['bizid'],
                      'fanid'=>$fanid,
                      'addtime'=>TIMESTAMP
                );
                pdo_insert('hxq_newywym_activity_fan',$data);
                return pdo_insertid();
            }else{
                return $actfan['id'];
            }
        }
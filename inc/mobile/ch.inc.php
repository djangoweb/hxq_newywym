<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $this->dooauth();
	    if (empty($_SESSION['oauth_openid'])){
	        $msg=array('errno'=>2,'message'=>'系统设置有误');
	        exit(json_encode($msg));
	    }
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$cate['actid']}");
	    $guesturl=urldecode($_GPC['guesturl']);
	    //echo $guesturl;
	    $arrurl=parse_url($guesturl);
	    $querystring=$arrurl['query'];
	    parse_str($querystring,$querys);
	        if (empty($querys['sharefrom'])){
	            $querys['sharefrom']=1;
	        }
	        $isclick=pdo_fetch('select * from '.tablename('hxq_newywym_click')." where sharefrom={$querys['sharefrom']} and uniacid={$_W['uniacid']} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
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
	                'sharefrom'=>$querys['sharefrom'],
	                'addtime'=>TIMESTAMP,
	                'addip'=>getip(),

	                'bizid'=>$act['bizid'],
	            );
	            pdo_insert('hxq_newywym_click',$data);
	        }
	    
	    
	    
	    
	    
	


<?php
class verif extends page{
    public function main($op=''){
	   global $_W,$_GPC;
       $uniacid=$_W['uniacid'];
	   $op=in_array($op,array('default','coupon','sw'))?$op:'default';
	   if ($op=='default'){
	     $actid=$_GPC['actid'];
	     $veriferid=$_GPC['veriferid'];
	     $password=$_GPC['password'];
	     $id=$_GPC['id'];
	     if (empty($id)){
	         die(json_encode(error(1,'未指定奖品')));
	     }
	     $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and id='{$_GPC['id']}'");
	     if ($token['verif']>0){
	         exit(json_encode(error(0,'奖品已由'.$token['verifernickname'].'核销成功')));
	     }
	     if (empty($actid)){
	        die(json_encode(error(1,'未指定活动')));
	     }
	     if (empty($veriferid)){
	         die(json_encode(error(1,'未指定核销员')));
	     }
	     $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and id=$veriferid");
	     if (empty($verifer)){
	         die(json_encode(error(1,'未指定核销员')));
	     }
	     if ($verifer['disable']){
	         die(json_encode(error(1,'未指定核销员'.$verifer['disable'])));
	     }
	     if ($verifer['password']!=$password){
	         die(json_encode(error(1,'核销密码不正确')));
	     }
	     $data=array(
	         'veriferid'=>$verifer['id'],
	         'verifernickname'=>$verifer['name'],
	         'verif'=>1,
	         'veriftime'=>TIMESTAMP
	     );
	     pdo_update('hxq_newywym_token',$data,array('id'=>$_GPC['id']));
	     exit(json_encode(error(0,'核销成功')));
	   }elseif($op=='coupon'){
	       $password=$_GPC['password'];
	       $id=$_GPC['id'];
	       if (empty($id)){
	           die(json_encode(error(1,'未指定')));
	       }
	       $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid=$uniacid and id=$id");
	       if ($grant['veriftime']){
	           die(json_encode(error(1,'已核销')));
	       }
	       $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon')." where uniacid=$uniacid and id={$grant['couponid']}");
	       $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$coupon['bizid']}");
	       
	       if ($biz['verifpwd']!=$password){
	           die(json_encode(error(1,'核销密码不正确')));
	       }
	       $data=array(
	           'veriftime'=>TIMESTAMP
	       );
	       pdo_update('hxq_newywym_coupongrant',$data,array('id'=>$_GPC['id']));
	       exit(json_encode(error(0,'核销成功')));
	   }elseif($op=='sw'){
	       $password=$_GPC['password'];
	       $id=$_GPC['id'];
	       if (empty($id)){
	           die(json_encode(error(1,'未指定')));
	       }
	       $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and id=$id");
	       if ($token['veriftime']){
	           die(json_encode(error(1,'已核销')));
	       }
	       $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$token['actid']}");
	       
	       $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']}");
	       
	       if ($biz['verifpwd']!=$password){
	           die(json_encode(error(1,'核销密码不正确')));
	       }
	       $data=array(
	           'veriftime'=>TIMESTAMP
	       );
	       pdo_update('hxq_newywym_token',$data,array('id'=>$_GPC['id']));
	       exit(json_encode(error(0,'核销成功')));
	   }
    }
    
    function scanverif($op='verif'){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $op=in_array($op,array('verif','check'))?$op:'verif';
        $type=$_GPC['type'];
        $this->dojump();
        $this->dooauth(true);
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['bizid']}");
            if (empty($biz)){
                $msg=error(1,'商家不存在');
                include $this->template('error');
            }
            $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$_GPC['bizid']} and endtime>unix_timestamp()",array(),'id');
            foreach($coupons as &$row){
                $coupon['starttime']=date('Y-m-d',$coupon['starttime']);
                $coupon['endtime']=date('Y-m-d',$coupon['endtime']);
                $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
            }
        if ($op=='verif'){
            if ($type=='sw'){
               $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']}  and id={$_GPC['id']}");
                if (empty($token)){
                    $msg=error(1,'记录不存在');
                    include $this->template('error');
                    exit;
                }
                if ($token['veriftime']){
                    $msg=error(1,'已核销');
                    include $this->template('error');
                    exit;
                }
                $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where oauth_openid='{$_SESSION['oauth_openid']}' and uniacid={$_W['uniacid']} and bizid={$token['bizid']}");
                if (empty($verifer)){
                    $msg=error(1,'核销员不存在');
                    include $this->template('error');
                    exit;
                }
                $data=array(
                    'veriftime'=>TIMESTAMP,
                    'veriferid'=>$verifer['id'],
                );
                pdo_update('hxq_newywym_token',$data,array('id'=>$_GPC['id']));
                $msg=error(0,'核销成功');
                include $this->template('scanverif');
                    exit;
            }elseif($type=='coupon'){
                $grant=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                if (empty($grant)){
                    $msg=error(1,'记录不存在');
                    include $this->template('error');
                    exit;
                }
                if ($grant['veriftime']){
                    $msg=error(1,'已核销');
                    include $this->template('error');
                    exit;
                }
                $verifer=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where oauth_openid='{$_SESSION['oauth_openid']}' and uniacid={$_W['uniacid']} and bizid={$grant['bizid']}");
                if (empty($verifer)){
                    $msg=error(1,'核销员不存在');
                    include $this->template('error');
                    exit;
                }
                $data=array(
                    'veriftime'=>TIMESTAMP,
                    'veriferid'=>$verifer['id'],
                );
                pdo_update('hxq_newywym_coupongrant',$data,array('id'=>$_GPC['id']));
                $msg=error(0,'核销成功');
                include $this->template('scanverif');
                    exit;
            }
        }
        if ($op=='check'){
            if ($type=='sw'){
            $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
            if (empty($token)){
                $msg=error(1,'记录不存在');
                response($msg);
            }
            if ($token['veriftime']){
                $msg=error(0,'已核销');response($msg);
            }else{
                    $msg=error(1,'未核销');response($msg);
                }
            }elseif ($type=='coupon'){
                $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupongrant')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
                if ($coupon['veriftime']){
                    $msg=error(0,'已核销');response($msg);
                }else{
                    $msg=error(1,'未核销');response($msg);
                }
            }
        }
    }
    
    function bindverifer($op='display'){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $op=in_array($op,array('display','bind'))?$op:'display';
        parent::$act=$act;
        $this->dooauth(true);
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$_GPC['bizid']}");
       if (empty($biz)){
           $msg=error(1,'商家不存在');
           include $this->template('error');
       }
        $coupons=pdo_fetchall('select * from '.tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and bizid={$_GPC['bizid']} and endtime>unix_timestamp()",array(),'id');
        foreach($coupons as &$row){
            $coupon['starttime']=date('Y-m-d',$coupon['starttime']);
            $coupon['endtime']=date('Y-m-d',$coupon['endtime']);
            $coupon['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $coupon['description']);
        }
        unset($row);
        if ($op=='bind'){
            $isbind=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid={$_W['uniacid']} and bizid={$biz['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            
            //var_dump($isbind);
            if (!$isbind){
                $data=array(
                'uniacid'=>$uniacid,
                'bizid'=>$biz['id'],
                'avatar'=>$_W['fans']['avatar'],
                'nickname'=>$_W['fans']['nickname'],
                'oauth_openid'=>$_SESSION['oauth_openid'],
                'addtime'=>TIMESTAMP,
                'delete'=>0
            );
            pdo_insert('hxq_newywym_verifer',$data);
            $msg=error(0,'绑定成功');
            }
            include $this->template('bindverif');
            exit;
        }
        if ($op=='display'){
            $isbind=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid={$_W['uniacid']} and bizid={$biz['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
            include $this->template('bindverif');
            exit;
        }
    }
}
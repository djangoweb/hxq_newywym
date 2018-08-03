<?php
	 require_once MODULE_ROOT.'/inc/mobileinit.php';
	   $op=in_array($_GPC['op'],array('default','coupon','sw'))?$_GPC['op']:'default';
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
<?php
	 require_once MODULE_ROOT.'/inc/mobileinit.php';
	 $act['name']='绑定核销员';
	 $op=in_array($_GPC['op'],array('display','bind'))?$_GPC['op']:'display';
	 $this->checkact();
	 if ($op=='bind'){
	     $isbind=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and oauth_openid='{$_SESSION['oauth_openid']}'");
	     if ($isbind){
	         die(json_encode(error(1,'您已绑定核销员'.$isbind['name'])));
	     }
	     if (!preg_match("/[0-9]{11}/",$_GPC['mobile'])){
	         die(json_encode(error(1,'请输入正确的手机号码')));
	     }
	     if (!$_GPC['name']){
	         die(json_encode(error(1,'请输入您的姓名')));
	     }
	     $exists=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and name='{$_GPC['name']}' and mobile='{$_GPC['mobile']}'");
	     if (!$exists){
	         die(json_encode(error(1,'请输入您的姓名与电话号码不存在，请重新输入!')));
	     }
	     $data=array(
	         'nickname'=>$_W['fans']['nickname'],
	         'oauth_openid'=>$_SESSION['oauth_openid'],
	     );
	     pdo_update('hxq_newywym_verifer',$data,array('mobile'=>$_GPC['mobile'],'name'=>$_GPC['name']));
	     die(json_encode(error(0,'绑定成功，您可以使用此微信号来核销客户的奖品')));
	 }
	 $isbind=pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and oauth_openid='{$_SESSION['oauth_openid']}'");
	 if($isbind){
	     message('您已经绑定核销员，如需重新绑定，请联系管理员解除绑定后再在此页面重新绑定！');
	 }
	 include $this->template('bindverifer');
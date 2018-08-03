<?php
/**
 * 二维码存储仓库模块订阅器
 *
 * @author ysw
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class hxq_newywymModuleReceiver extends WeModuleReceiver {
	public function receive() {
	    Global $_W;
	    $message=$this->message;
		$type = $this->message['type'];
		if ($this->message['event']=='user_get_card'){
		    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon') ." where uniacid={$_W['uniacid']} and  couponid='{$message['cardid']}'");
		    if (!empty($coupon)){
		        $data=array(
		            'openid'=>$message['fromusername'],
		            'oauth_openid'=>$message['fromusername'],
		            'type'=>2,
		            'uniacid'=>$_W['uniacid'],
		            'actid'=>$coupon['actid'],
		            'name'=>$coupon['name'],
		            'cid'=>$coupon['id'],
		            'couponid'=>$coupon['couponid'],
		            'addtime'=>TIMESTAMP,
		            'nickname'=>$_W['fans']['nickname'],
		            'avatar'=>$_W['fans']['tag']['avatar'],
		            'addip'=>getip(),
		            'code'=>$message['usercardcode']
		        );
		        pdo_insert('hxq_newywym_coupongrant',$data);
		    }else{
		        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_award') ." where uniacid={$_W['uniacid']} and  weixincouponid='{$message['cardid']}'");
		        if (!empty($coupon)){
		            $id=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_token') ." where uniacid={$_W['uniacid']} and  weixincouponid='{$message['cardid']}' and openid='{$message['fromusername']}' order by id desc");
		            pdo_update('hxq_newywym_token',array('code'=>$message['usercardcode']),array('id'=>$id));
		        }
		    }
		}elseif ($this->message['event']=='user_consume_card'){
		    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon') ." where uniacid={$_W['uniacid']} and  couponid='{$message['cardid']}'");
		    if (!empty($coupon)){
		        pdo_update('hxq_newywym_coupongrant',array('verifer'=>$message['staffopenid'],'veriftime'=>TIMESTAMP),array('uniacid'=>$_W['uniacid'],'openid'=>$message['fromusername'],'couponid'=>$message['cardid'],'code'=>$message['usercardcode']));
		    }else{
		          $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_award') ." where uniacid={$_W['uniacid']} and  weixincouponid='{$message['cardid']}'");
		        if (!empty($coupon)){
		          $id=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_token') ." where uniacid={$_W['uniacid']} and  code='{$message['usercardcode']}' order by id desc");
		          pdo_update('hxq_newywym_token',array('verifer'=>$message['staffopenid'],'veriftime'=>TIMESTAMP),array('id'=>$id));
		        }
		    }
		}elseif ($this->message['event']=='user_del_card'){
		    $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_coupon') ." where uniacid={$_W['uniacid']} and  couponid='{$message['cardid']}'");
		    if (!empty($coupon)){
		        pdo_update('hxq_newywym_coupongrant',array('deleted'=>TIMESTAMP),array('uniacid'=>$_W['uniacid'],'openid'=>$message['fromusername'],'couponid'=>$message['cardid'],'code'=>$message['usercardcode']));
		    }else{
		        $coupon=pdo_fetch('select * from '.tablename('hxq_newywym_award') ." where uniacid={$_W['uniacid']} and  weixincouponid='{$message['cardid']}'");
		        if (!empty($coupon)){
		            $id=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_token') ." where uniacid={$_W['uniacid']} and  code='{$message['usercardcode']}' order by id desc");
		            
		            pdo_update('hxq_newywym_token',array('deleted'=>1),array('id'=>$id));
		        }
		    }
		}
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
	}
}
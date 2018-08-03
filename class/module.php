<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_NAME','hxq_newywym');
require_once IA_ROOT.'/addons/hxq_newywym/inc/functions.php';
require_once IA_ROOT.'/addons/hxq_newywym/inc/route.php';


class hxq_newywymModule extends WeModule {
 	public function fieldsFormDisplay($rid = 0) {
 	    global $_W, $_GPC;
 	    if (!empty($rid)){
 	        $reply=pdo_fetch('select * from '.tablename('hxq_newywym_setting')." where uniacid={$_W['uniacid']} and rid=$rid and type='reply'");
 	    }
 	    include $this->template('reply');
 	}

	public function fieldsFormValidate($rid = 0) {
	    global $_W, $_GPC;
	    if (empty($_GPC['replytitle'])){
	        return '请输入关注回复标题';
	    };
	    if (empty($_GPC['replythumb'])){
	        return '请输入关注回复图片';
	    };
	    if (empty($_GPC['replydescription'])){
	        return '请输入关注回复描述';
	    };
	}

	public function fieldsFormSubmit($rid) {
	    global $_W, $_GPC;
	  $setting=pdo_fetch('select * from '.tablename('hxq_newywym_setting')." where uniacid={$_W['uniacid']} and rid=$rid");
	  if (!empty($setting)){
	   pdo_update('hxq_newywym_setting',array('uniacid'=>$_W['uniacid'],'replytitle'=>$_GPC['replytitle'],'replythumb'=>$_GPC['replythumb'],'replydescription'=>$_GPC['replydescription'],'updatetime'=>TIMESTAMP),array('rid'=>$rid));    
	  }else{
	        $data=array(
	            'uniacid'=>$_W['uniacid'],
	            'type'=>'reply',
	            'rid'=>$rid,
	            'replytitle'=>$_GPC['replytitle'],
	            'replythumb'=>$_GPC['replythumb'],
	            'replydescription'=>$_GPC['replydescription'],
	            'updatetime'=>TIMESTAMP
	        );
	        pdo_insert('hxq_newywym_setting',$data);
	    }
	}

	public function ruleDeleted($rid) {
	    pdo_delete('hxq_newywym_setting',array('replytitle'=>'','replythumb'=>'','replydescription'=>'','updatetime'=>TIMESTAMP),array('rid'=>$rid));
	} 

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实  现） 
       if(checksubmit("savepay")) {
           //字段验证, 并获得正确的数据$dat
           $dat['paytype']=$_GPC['paytype'];
           $dat['appid']=$_GPC['appid'];
           $dat['apikey']=$_GPC['apikey'];
           $dat['mchid']=$_GPC['mchid'];
           $dat['send_name']=$_GPC['send_name'];
           $dat['act_name']=$_GPC['act_name'];
           $dat['wishing']=$_GPC['wishing'];
           $dat['remark']=$_GPC['remark'];
           $dat['sslcert']=$_GPC['sslcert'];
           $dat['sslkey']=$_GPC['sslkey'];
           $dat['rootca']=$_GPC['rootca'];
           if (empty($dat['appid'])){
               message('请输入appid！');
           }
           if (empty($dat['apikey'])){
               message('请输入apikey！');
           }
           if (empty($dat['mchid'])){
               message('请输入mchid！');
           }
           if (empty($dat['send_name'])){
               message('请输入send_name！');
           }
           if (empty($dat['wishing'])){
               message('请输入wishing！');
           }
           if (!empty($_GPC['subappid'])){
                
               $dat['subappid']=$_GPC['subappid'];
           }
           if (!empty($_GPC['submchid'])){
               $dat['submchid']=$_GPC['submchid'];
           }
           if (empty($dat['sslcert'])){
               message('请将微信支付证书(sslcert)文件打开后的内容粘贴到此');
           }
           if (empty($dat['sslkey'])){
               message('请将微信支付私钥(sslkey)文件打开后的内容粘贴到此');
           }
           if (empty($dat['rootca'])){
               message('请将CA证书(rootca)文件打开后的内容粘贴到此');
           }
           load()->func('file');
           if (!is_dir(MODULE_ROOT.'/cert')){
               mkdirs(MODULE_ROOT.'/cert');
           }
           $cert=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.apiclient_cert.pem','w');
           fwrite($cert,$dat['sslcert']);
           fclose($cert);
           $key=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.apiclient_key.pem','w');
           fwrite($key,$dat['sslkey']);
           fclose($key);
           $rootca=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.rootca.pem','w');
           fwrite($rootca,$dat['rootca']);
           fclose($rootca);
           
			$data=$this->module['config'];
			$data['pay']=$dat;
			$this->saveSettings($data);
           message('保存支付参数成功！', 'referer', 'success');
       }
       if(checksubmit("saveqrcode")) {
           //字段验证, 并获得正确的数据$dat
           $dat['agenter']=$_GPC['agenter'];
           if (empty($dat['agenter'])){
               message('请输入二维码售价！');
           }

           $data=$this->module['config'];
           $data['qrcode']=$dat;
           $this->saveSettings($data);
           message('保存二维码售价成功！', 'referer', 'success');
       }
       if (checksubmit('savecorpright')){
           $dat['corpright']=$_GPC['corpright'];
           
			$data=$this->module['config'];
			$data['corpright']=$dat['corpright'];
			$this->saveSettings($data);
           message('保存版权成功！', referer(), 'success');
       }
       
       if(checksubmit("savejump")) {
           $dat['redirect']=$_GPC['redirect'];
           $dat['display']=$_GPC['display'];
           /* if (empty($dat['redirect'])){
            message('请输入转发域名！');
            }
            if (empty($dat['display'])){
            message('请输入展示域名！');
           } */
           
			$data=$this->module['config'];
			$data['jump']=$dat;
			$this->saveSettings($data);
           message('保存防封成功！', referer(), 'success');
       }
      
       load()->func('tpl');
		$config=$this->module['config'];
		include $this->template('setting');
	}
	
	private function  ischinese($str)
    {
	   if (empty($str))return false;
	   if(!preg_match("/[^\x80-\xff]/i","$str")){ return true; } else {return false;}
    }

}

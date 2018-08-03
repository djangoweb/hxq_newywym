<?php
/**
 * 二维码存储仓库模块定义
 *
 * @author ysw
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class hxq_newywymModule extends WeModule {
 	public function fieldsFormDisplay($rid = 0) {
 	    global $_W, $_GPC;
 	    $uniacid=$_W['uniacid'];
 	    $acts=pdo_fetchall('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid");
 	    if ($rid){
 	    $reply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where rid=$rid");
 	    }
 	    include $this->template('reply');
 	}

	public function fieldsFormValidate($rid) {
	    global $_W, $_GPC;
	    $uniacid=$_W['uniacid'];
	    if ($_W['account']['level']!=4){
    	    if (empty($_GPC['actid'])){
    	        return '请选择活动';
    	    }
    	    if (strexists($_GPC['keywords'],"},{")){
    	        return '只能添加一个关键词';
    	    }
    	    $keywords=substr(substr($_GPC['keywords'],1),0,-1);
    	    $keywords=json_decode(stripslashes(html_entity_decode($keywords)),true);
    	    if ($_GPC['rid']){
    	        $reply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and rid={$_GPC['rid']}");
    	        if (!$reply) return '规则不存在';
    	        $keyreply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and rid!={$_GPC['rid']} and keyword='{$keywords['content']}'");
    	        if ($keyreply){
    	            return '活动关键字已存在';
    	        }
    	        $actreply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and rid!={$_GPC['rid']} and type={$_GPC['type']} and actid='{$_GPC['actid']}'");
    	        if ($actreply){
    	            return '该活动此类型关键字，请换一个类型';
    	        }
    	    }else{
    	        $reply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and keyword='{$keywords['content']}'");
    	        if ($reply){
    	            return '关键词已存在';
    	        }
    	        $keyreply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and type={$_GPC['type']} and actid='{$_GPC['actid']}'");
    	        if ($keyreply){
    	            return '该活动此类型关键字，请换一个类型';
    	        }
    	    }
	    }
	}

	public function fieldsFormSubmit($rid) {
	    global $_W, $_GPC;
	    if ($_W['account']['level']!=4){
	        $keywords=substr(substr($_GPC['keywords'],1),0,-1);
	       $keywords=json_decode(stripslashes(html_entity_decode($keywords)),true);
    	  $reply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid={$_W['uniacid']} and rid=$rid");
    	  if (!empty($reply)){
    	        pdo_update('hxq_newywym_reply',array('actid'=>$_GPC['actid'],'keyword'=>$keywords['content'],'type'=>$_GPC['type']),array('rid'=>$rid));    
    	  }else{
    	        $data=array(
    	            'uniacid'=>$_W['uniacid'],
    	            'rid'=>$rid,
    	            'keyword'=>$keywords['content'],
    	            'actid'=>$_GPC['actid'],
    	            
    	            'type'=>$_GPC['type'],
    	            'addtime'=>TIMESTAMP
    	        );
    	        pdo_insert('hxq_newywym_reply',$data);
    	  }
	    }
	}

	public function ruleDeleted($rid) {
	    pdo_delete('hxq_newywym_reply',array('rid'=>$rid));
	} 

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实  现） 
		if(checksubmit("savepay")) {
			//字段验证, 并获得正确的数据$dat
		    $dat['paytype']=$_GPC['paytype'];
			$dat['appid']=$_GPC['appid'];
			$dat['apikey']=$_GPC['apikey'];
			$dat['mchid']=$_GPC['mchid'];
			$dat['wishing']=$_GPC['wishing'];
			$dat['sslcert']=$_GPC['sslcert'];
			$dat['sslkey']=$_GPC['sslkey'];
			if (empty($dat['appid'])){
			    message('请输入appid！');
			}
			if (empty($dat['apikey'])){
			    message('请输入apikey！');
			}
			if (empty($dat['mchid'])){
			    message('请输入mchid！');
			}
			if (empty($dat['wishing'])){
			    message('请输入wishing！');
			}
			if (empty($dat['sslcert'])){
			    message('请将微信支付证书(sslcert)文件打开后的内容粘贴到此');
			}
			if (empty($dat['sslkey'])){
			    message('请将微信支付私钥(sslkey)文件打开后的内容粘贴到此');
			}
			if(!empty($_GPC['scene_id'])){
                $dat['scene_id']=$_GPC['scene_id'];
            }	
			load()->func('file');
			if (!is_dir(MODULE_ROOT.'/cert')){
			    mkdirs(MODULE_ROOT.'/cert');
			}
			$cert=fopen(MODULE_ROOT.'/cert/'.$_W['uniacid'].'.apiclient_cert.pem','w');
			fwrite($cert,$dat['sslcert']);
			fclose($cert);
			$key=fopen(MODULE_ROOT.'/cert/'.$_W['uniacid'].'.apiclient_key.pem','w');
			fwrite($key,$dat['sslkey']);
			fclose($key);
		    if (!empty($_GPC['rootca'])){
               $dat['rootca']=$_GPC['rootca'];
               $rootca=fopen(MODULE_ROOT.'/cert/'.$_W['uniacid'].'.rootca.pem','w');
               fwrite($rootca,$dat['rootca']);
               fclose($rootca);
            }
			$data=$this->module['config'];
			$data['pay']=$dat;
			$this->saveSettings($data);
			message('保存支付参数成功！', 'referer', 'success');
		}
		if(checksubmit("savecompany")) {
		    //字段验证, 并获得正确的数据$dat
		    $dat['name']=$_GPC['name'];
		    $dat['phone']=$_GPC['phone'];
		    $dat['address']=$_GPC['address'];
		    $dat['thumb']=$_GPC['thumb'];
		    $dat['description']=$_GPC['description'];
		    $dat['show']=$_GPC['show'];
		    if (empty($dat['name'])){
		        message('请输入公司名称！');
		    }
		    if (empty($dat['phone'])){
		        message('请输入电话！');
		    }
		    if (empty($dat['address'])){
		        message('请输入地址！');
		    }
		    if (empty($dat['thumb'])){
		        message('请输入缩略图！');
		    }
		    if (empty($dat['description'])){
		        message('请输入send_name！');
		    }
		    $data=$this->module['config'];
		    $data['company']=$dat;
		    $this->saveSettings($data);
		    message('保存公司成功！', 'referer', 'success');
		}
		if (checksubmit('savenotice')){
		    $data=$this->module['config'];
		    $dat['dokeeptplid']=$_GPC['dokeeptplid'];
		    $dat['dofissiontplid']=$_GPC['dofissiontplid'];
		    $dat['coupontplid']=$_GPC['coupontplid'];
		    $dat['tplid']=$_GPC['tplid'];
		    $dat['chtplid']=$_GPC['chtplid'];
		    $dat['tplopenids']=$_GPC['tplopenids'];
		    $dat['chtplopenids']=$_GPC['chtplopenids'];
		    $data['notice']=$dat;
		    $this->saveSettings($data);
		    message('保存通知设置成功！', referer(), 'success');
		}
		if (checksubmit('corpright')){
		    $data=$this->module['config'];
		    $dat['corpright']=$_GPC['corpright'];
		    if (empty($dat['corpright'])){
		        message('请输入版权信息！');
		    }
		    $data['corpright']=$dat['corpright'];
		    $this->saveSettings($data);
		    message('保存版权成功！', referer(), 'success');
		}
		if (checksubmit('saveslat')){
		    if (empty($_GPC['slat'])||strlen($_GPC['slat'])!=8){
		        message('请输入8位加密盐！');
		    }
		    $rst=pdo_insert('hxq_newywym_slat',array('slat'=>$_GPC['slat']));
		    message('保存加密盐成功！', referer(), 'success');
		}
		
		if(checksubmit("savejump")) {
		    $data=$this->module['config'];
		    $dat['redirect']=$_GPC['redirect'];
		    $dat['display']=$_GPC['display'];
		    if (!empty($dat['redirect'])&&substr($dat['redirect'],-1)!='/'){
		        message('网址请以/结束');
		    }
		    if (!empty($dat['display'])){
		        if (strexists($dat['display'],"\r\n")){
		            $displays=explode("\r\n",$dat['display']);
		        }elseif(strexists($dat['display'],"\n")){
		            $displays=explode("\n",$dat['display']);
		        }elseif(strexists($dat['display'],"\r")){
		            $displays=explode("\r",$dat['display']);
		        }else{
		            $displays=array($dat['display']);
		        }
		        $displays=array_filter($displays);
		        foreach ($displays as $row){
		            if (substr($row,-1)!='/'){
		                message('网址请以/结束');
		            }
		        }
		    }
		    
		    /* if (empty($dat['redirect'])){
		        message('请输入转发域名！');
		    }
		    if (empty($dat['display'])){
		        message('请输入展示域名！');
		    } */
		    $data['jump']=$dat;
		    $this->saveSettings($data);
		    message('保存防封成功！', referer(), 'success');
		}
		//这里来展示设置项表单/*
		$slat=pdo_fetch('select * from '.tablename('hxq_newywym_slat'));
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

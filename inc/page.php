<?php 
 class page extends WeModuleSite{
     public $globalcfg;
     public $cfg;
     public static $act;
     public function __construct(){
         global $_W,$_GPC,$site;
         $uniacid=$_W['uniacid'];
         if (IN_SYS&&!empty($_GPC['uniacid_'])&&$uniacid!=$_GPC['uniacid_']){
             load()->model('module');
             load()->model('account');
             module_save_switch(MODULE_NAME, $_GPC['uniacid_']);
             $uniacid = intval($_GPC['uniacid_']);
             $role = uni_permission($_W['uid'], $uniacid);
             if(empty($role)) {
                 itoast('操作失败, 非法访问.', '', 'error');
             }
             uni_account_save_switch($uniacid);
             uni_account_switch($uniacid,$_W['siteurl'],'success');
         }
         if ($_W['container'] == 'wechat') {
             $unisetting = uni_setting_load();
             if (!empty($unisetting['jsauth_acid'])) {
                 $jsauth_acid = $unisetting['jsauth_acid'];
             } else {
                 if ($_W['account']['level'] < 3 && !empty($unisetting['oauth']['account'])) {
                     $jsauth_acid = $unisetting['oauth']['account'];
                 } else {
                     $jsauth_acid = $_W['acid'];
                 }
             }
             if (!empty($jsauth_acid)) {
                 $account_api = WeAccount::create($jsauth_acid);
                 if (!empty($account_api)) {
                     $urls = parse_url($_W['siteroot']);
                     //$urls['path'] = str_replace(array('/web', '/app', '/payment/wechat', '/payment/alipay', '/payment/jueqiymf', '/api'), '', $urls['path']);
                     //$_W['siteroot'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];
                     $url = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '') . $_W['script_name'] . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];
                     $_W['account']['jssdkconfig'] = $account_api->getJssdkConfig($url);
                     $_W['account']['jsauth_acid'] = $jsauth_acid;
                 }
             }
             unset($jsauth_acid, $account_api);
         }
         $setting=pdo_get('hxq_newywym_setting',array('type'=>'global'),'setting');
         if (!empty($setting)){
             $this->globalcfg=iunserializer($setting['setting']);
         }else{
             $this->globalcfg=array();
         }
         $slat=pdo_fetch('select * from '.tablename('hxq_newywym_slat').'');
         $this->globalcfg['slat']=$slat['slat'];
         $this->cfg=$site->module['config'];
     }
     public function template($filename) {
	        global $_W,$_GPC;
	        $name='hxq_newywym';
	        $defineDir = IA_ROOT.'/addons/hxq_newywym/';
	        if(defined('IN_SYS')) {
	            $source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
	            $compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
	            if(!is_file($source)) {
	                $source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
	            }
	            if(!is_file($source)) {
	                $source = $defineDir . "/template/web/{$filename}.html";
	            }
	            if(!is_file($source)) {
	                $source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
	            }
	            if(!is_file($source)) {
	                $source = IA_ROOT . "/web/themes/default/{$filename}.html";
	            }
	        } else {
	            $source = IA_ROOT . "/app/themes/{$_W['template']}/{$name}/{$filename}.html";
	            $compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/{$filename}.tpl.php";

	            if(!is_file($source)) {
	                $source = IA_ROOT . "/app/themes/default/{$name}/{$filename}.html";
	            } 

	            if(!is_file($source)) {
	                $source = $defineDir . "/template/mobile/{$filename}.html";
	            }
	            if(!is_file($source)) {
	                $source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
	            } 
	           if(!is_file($source)) {
	                if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
	                    $source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
	                } else {
	                    $source = IA_ROOT . "/app/themes/default/{$filename}.html";
	                }
	            }
	        }
	        if(!is_file($source)) {
	            exit($source."Error: template source '{$filename}' is not exist!");
	        }
	        $paths = pathinfo($compile);
	        $compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
	        if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
	            template_compile($source, $compile, true);
	        }
	        return $compile;
	    }
	    
	    protected function checktoken($slat,$tk='',$vtk=''){
	        Global $_W,$_GPC,$_M;
	        
	        if (empty($tk)||empty($vtk)){
	            return array('errno'=>2,'message'=>'请扫码参与活动！');
	        }
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where type=2 and mintk<=$tk and maxtk>=$tk");
	        if (empty($cate)){
	            return array('errno'=>2,'message'=>'请扫码参与活动！');
	        }
	        if (substr(md5($tk.$slat),0,8)!=strtolower($vtk)){
	            return array('errno'=>2,'message'=>'请扫码参与活动！','cate'=>$cate);
	        }
	        if ($cate['uniacid']!=$_W['uniacid']){
	            $_W['uniacid']=$cate['uniacid'];
	            header('location:'.mobileurl('index',array('tk'=>$tk,'vtk'=>$vtk)));
	            exit();
	        }
	        //var_dump($token);
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where  uniacid={$_W['uniacid']} and id={$cate['actid']}");
	        if (empty($act)){
	            return array('errno'=>3,'message'=>'该码未正式启用！','cate'=>$cate);
	        }
	        $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where  uniacid={$_W['uniacid']} and tk='$tk'");
	        if (!empty($token)){
	            $scan=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid={$_W['uniacid']} and id={$token['scanid']} and isgrant=1");
	            if (empty($scan)){
	                $scan=$token;
	            }
	            if($_SESSION['oauth_openid']!=$token['oauth_openid']){
	                return array('errno'=>6,'message'=>"奖品已被领取",'cate'=>$cate,'act'=>$act,'token'=>$token,'scan'=>$scan);
	            }else{
	                return array('errno'=>7,'message'=>"奖品已经被您领取",'cate'=>$cate,'act'=>$act,'token'=>$token,'scan'=>$scan);
	            }
	        }else{
	            $scan=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid={$_W['uniacid']} and tk='$tk' and oauth_openid='{$_SESSION['oauth_openid']}'");
	            if (!empty($scan)){
	                return array('errno'=>8,'message'=>"恭喜您中奖了",'cate'=>$cate,'act'=>$act,'scan'=>$scan);
	            }
	            return array('errno'=>0,'message'=>"该码可以使用",'cate'=>$cate,'act'=>$act);
	        }
	    }
	    
	    public function censusdata($guesturl=''){
	        global $_W,$_GPC,$_M;
	        //echo $guesturl;
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
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
	    
	    protected function dojump(){
            global $_W,$_GPC;
	        $jump=$this->globalcfg['jump'];
	        if (!empty($jump['display'])){
	            if (strexists($jump['display'],"\r\n")){
	                $displays=explode("\r\n",$jump['display']);
	            }elseif(strexists($jump['display'],"\n")){
	                $displays=explode("\n",$jump['display']);
	            }elseif(strexists($jump['display'],"\r")){
	                $displays=explode("\r",$jump['display']);
	            }else{
	                $displays=array($jump['display']);
	            }
	            $this->globalcfg['jump']['displays']=array_filter($displays);
	            $this->globalcfg['jump']['display']=$this->globalcfg['jump']['displays'][0];
	        } 
	        //echo $_W['siteroot'];
	        if (defined('IN_MOBILE')&&!$_W['isajax']&&!empty($this->globalcfg['jump']['redirect'])&&!empty($this->globalcfg['jump']['display'])&&!in_array($_W['siteroot'],$this->globalcfg['jump']['displays'])){
	            header('location:'.$this->globalcfg['jump']['display'].substr($_W['script_name'],1) . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING']);
	        }
	    }
	    
	    protected function dooauth($focus=false){
	        global $_W,$_GPC;
	        if ($_W['container']=='wechat'&&((page::$act['act']['getfaninfo']&&(empty($_W['fans']['nickname'])||empty($_W['fans']['tag']['avatar'])))||$focus)){
	            if (empty($_SESSION['userinfo'])){
	                mc_oauth_userinfo();
	            }
	            $userinfo=unserialize(base64_decode($_SESSION['userinfo']));
	            if (is_error($userinfo)){
	                message($userinfo['message']);
	            }
	            $_W['fans']=(array)$_W['fans'];
	            $_W['fans']['nickname']=$userinfo['nickname'];
	            $_W['fans']['tag']=array();
	            $_W['fans']['tag']['avatar']=$userinfo['avatar'];
	        }
	    }
	    
	    public function func($op,$params=array()){
	        global $_W,$_GPC,$_M;
	        if (!empty($_GPC['actid'])){
	            $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where id={$_GPC['actid']}");
	            $act['page']=iunserializer($act['page']);
	            $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
	            $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
	            self::$act=$act;
	        }
	        include_once  MODULE_ROOT.'/inc/mfunctions.php';
	        return $op($params);
	    }
	    
	    public function qrcode($op,$params=array()){
	        global $_W,$_GPC,$_M;
	        if (!empty($_GPC['actid'])){
	            $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where id={$_GPC['actid']}");
	            $act['page']=iunserializer($act['page']);
	            $act['jj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
	            $act['blacklistgroups']=iunserializer($act['blacklistgroups']);
	            self::$act=$act;
	        }
	        include_once MODULE_ROOT.'/inc/mfunctions.php';
	        response(array('errno'=>0,'message'=>'cg','qrcode'=>$op($params)));
	    }
 }
<?php 
 class page extends WeModuleSite{
     public $globalcfg;
     public $cfg;
     public $act;
     public function __construct(){
         global $_W,$_GPC,$site;
         $uniacid=$_W['uniacid'];
         if (!empty($_GPC['uniacid_'])&&$uniacid!=$_GPC['uniacid_']){
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
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where type=1 and mintk<=$tk and maxtk>=$tk");
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
	        $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and tk='$tk'");
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

	    public function registfan(){
	        Global $_W,$_GPC;
	        require_once MODULE_ROOT.'/inc/mobileinit.php';
	        if (empty($_SESSION['oauth_openid'])){
	            $msg=array('errno'=>1,'message'=>'系统设置有误');
	            include $this->template('index_error');
	            exit;
	        }
	        $cateid=intval($_GPC['cateid']);
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$cateid}");
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	        require_once MODULE_ROOT.'/inc/verif.class.php';
	        $checklist=array(
	            'mobile'=>array('isMobile'),
	        );
	        $uniquelist=array();
	        $verif_=new verify();
	        foreach($act['faninfoarr'] as $k=>$v){
	            $checkfunc=$checklist[$k];
	            if (!empty($checkfunc)){
	                $funcname=$checkfunc[0];
	                array_shift($checkfunc);
	            }else{
	                $funcname='';
	            }
	            $rst=true;
	            if (method_exists($verif_,$funcname)){
	                //var_dump($checkfunc);
	                switch (count($checkfunc)) {
	                    case 0:
	                        $rst= $verif_->$funcname($_GPC[$k]);
	                        break;
	                    case 1:
	                        $rst=  $verif_->$funcname($_GPC[$k],$checkfunc[0]);
	                        break;
	                    case 2:
	                        //echo $_GPC[$k],$checkfunc[0],$checkfunc[1];
	                        $rst=  $verif_->$funcname($_GPC[$k],$checkfunc[0],$checkfunc[1]);
	                        break;
	                        //var_dump($rst);
	                }
	            }else{
	                if (empty($_GPC[$k])){
	                    $rst=false;
	                }
	            }
	            if (!$rst){
	                $reinfo=json_encode(array('errno'=>1,'message'=>'请正确填写'.$v));
	                exit($reinfo);
	            }
	            if (array_key_exists($k,$uniquelist)){
	                $restr="\"".$k."\";[si]:[0-9]{1,}:\"{$_GPC[$k]}\";";
	                $exists=pdo_fetchcolumn('select id from '.tablename('hxq_newywym_fan')." where uniacid={$uniacid} and actid={$this->act['id']} and focusgetinfoparams regexp '{$restr}'");
	                if ($exists){
	                    exit(json_encode(error(1,$uniquelist[$k])));
	                }
	            }
	            $tmprequireinfo[$k]=$_GPC[$k];
	        }
	        $params=array('actid'=>$act['id'],'cateid'=>$cate['id']);
	        $result=$this->regfan($tmprequireinfo,$params);
	        exit(json_encode($result));
	    }
	    
	    protected function regmember(){
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
	    
	    protected function dooauth(){
	        global $_W,$_GPC;
	        if ($_W['container']=='wechat'&&$_GPC['preview']!=1&&$this->act['getfaninfo']&&(empty($_W['fans']['nickname'])||empty($_W['fans']['tag']['avatar']))){
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
	    
 }
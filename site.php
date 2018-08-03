<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_ROOT',IA_ROOT.'/addons/hxq_newywym/');
$_M=array();
require MODULE_ROOT.'inc/config.php';
require MODULE_ROOT.'/inc/slat.php';
require MODULE_ROOT.'/inc/functions.php';
class hxq_newywymModuleSite extends WeModuleSite {
	protected $date;
	protected $act;
	public function __construct(){
	    Global $_W,$_GPC,$_M;
	    $_M['slat']=pdo_fetchcolumn('select slat from '.tablename('hxq_newywym_slat'));
	}
	
	protected function checktoken($slat,$tk='',$vtk=''){
	    Global $_W,$_GPC,$_M;
	    if (empty($tk)||empty($vtk)){
	        return array('errno'=>2,'message'=>'请扫码参与活动！');
	    }
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where mintk<=$tk and maxtk>=$tk");
        if (empty($cate)){
            return array('errno'=>2,'message'=>'请扫码参与活动！');
        }
        if (substr(md5($tk.$slat),0,8)!=strtolower($vtk)){
            return array('errno'=>2,'message'=>'请扫码参与活动！','cate'=>$cate);
        }
        if ($cate['uniacid']!=$_W['uniacid']){
            $keys = array_keys($_SESSION);
            foreach ($keys as $key) {
                unset($_SESSION[$key]);
            }
            unset($keys, $key);
            setcookie(session_name(),'',TIMESTAMP-3600);
            session_write_close();
            $_W['uniacid']=$cate['uniacid'];
            //unset($_SESSION['dest_url']);
            /*echo "<div style='word-break:break-all;width:90%;'>";
             $_W['uniacid']=$cate['uniacid'];
             $session_=pdo_fetch('select * from '.tablename('core_sessions')." where sid='{$_W['session_id']}'");
             $session=var_export($_SESSION,true);
             $session_=var_export($session_,true);
             echo "出错了<br>session丢失<br>state:{$state}<br>网址: {$_W['siteurl']}<br>session_id:{$_W['session_id']}<br>session:$session<br>session_db:$session_";
             echo mobileurl('index',array('tk'=>$tk,'vtk'=>$vtk));
             echo "</div>";
            exit;  */
            header('location:'.$this->createmobileurl('index',array('tk'=>$tk,'vtk'=>$vtk)));
            exit();
        }
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
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
	        $scan_=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid={$_W['uniacid']} and tk='$tk' and oauth_openid='{$_SESSION['oauth_openid']}'");
	        if (!empty($scan_)){
	            pdo_update('hxq_newywym_scan',array('updatetime'=>TIMESTAMP),array('id'=>$scan_['id']));
	            return array('errno'=>8,'message'=>"恭喜您中奖了",'cate'=>$cate,'act'=>$act,'scan'=>$scan_,'fission'=>$fission);
	        }
    	    return array('errno'=>0,'message'=>"该码可以使用",'cate'=>$cate,'act'=>$act,'fission'=>$fission);
	   }
	}
	
	
	
	public function domobileredirectaward(){
	    Global $_W,$_GPC;
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	    if ($token['type']==1){
	        header('location:'.$this->createmobileurl('awardedrcd',array('cateid'=>$token['cateid'],'type'=>'1')));
	    }elseif($token['type']==2){

	        header('location:'.$this->createmobileurl('awardedrcd',array('cateid'=>$token['cateid'],'type'=>'2')));
	    }elseif($token['type']==3){

	        header('location:'.$this->createmobileurl('awardedrcd',array('cateid'=>$token['cateid'],'type'=>'3')));
	    }elseif($token['type']==4){

	        header('location:'.$this->createmobileurl('awardedrcd',array('cateid'=>$token['cateid'],'type'=>'4')));
	    }elseif($token['type']==5){

	        header('location:'.$this->createmobileurl('awardedrcd',array('cateid'=>$token['cateid'],'type'=>'coupon')));
	    }
	}
	public function domobileregfan(){
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
        foreach($this->act['faninfoarr'] as $k=>$v){
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
	
	function domobilecheckfisnarea(){
	    global $_W,$_GPC;
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    if (empty($act)){
	        die(json_encode(error(1,'找不到活动')));
	    }
	    if (strexists($act['fisnarea'],',')){
	        $act['fisnarea']=explode(',',$act['fisnarea']);
	    }else{
	        $act['fisnarea']=array($act['fisnarea']);
	    }
	    $rst=$this->checkarea($act['fisnarea'],$_GPC['address']);
	    if ($rst){
	        pdo_update('hxq_newywym_fission',array('checkarea'=>1),array('id'=>$_GPC['fid']));
	        die(json_encode(error(0,'检测正确')));
	    }
	    die(json_encode(error(1,'检测错误')));
	}
	
	function domobilecheckkeeparea(){
	    global $_W,$_GPC;
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    if (empty($act)){
	        die(json_encode(error(1,'找不到活动')));
	    }
	    if (strexists($act['keeparea'],',')){
	        $act['keeparea']=explode(',',$act['keeparea']);
	    }else{
	        $act['keeparea']=array($act['keeparea']);
	    }
	    $rst=$this->checkarea($act['keeparea'],$_GPC['address']);
	    if ($rst){
	        pdo_update('hxq_newywym_shtmline',array('checkarea'=>1),array('id'=>$_GPC['stlid']));
	        die(json_encode(error(0,'检测正确')));
	    }
	    die(json_encode(error(1,'检测错误')));
	}
	
	function checkarea($areas,$address){
	    foreach($areas as $row){
	        if (strexists($address, $row)){
	            return true;
	        }
	    }
	    return false;
	}
	
	function domobilechkfollow(){
        require_once MODULE_ROOT.'/inc/mobileinit.php';
        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
        
        //echo 'select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}";
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    //var_dump($_W['fans']);
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
	                    'name'=>$this->modulename.$scene_id,
	                    'keyword'=>$this->modulename.$scene_id,
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
	            $keyword=pdo_fetchcolumn('select keyword from '.tablename('hxq_newywym_reply')." where uniacid={$_W['uniacid']} and type=1 and actid={$act['id']}");
	            $message='长按关注公众号，发送 <span style="color:red">'.$keyword.'</span> 即可领奖！';
	            $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
	            exit(json_encode($msg));
	        }
	}else{
	    die(json_encode(error(0,'验证通过')));
	}
	}
	
	function domobilechkfisnfollow(){
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    //var_dump($_W['fans']);
	    if (!empty($act['focusfollow'])&&empty($_W['fans']['follow'])){
	        if ($_W['account']['level']==4){
	            $account=Weaccount::create($_W['account']);
	            $scene_base=200000000;
	            $scene_id=$_GPC['fid'];
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
	                    'name'=>$this->modulename.'__'.$scene_id,
	                    'keyword'=>$this->modulename.'__'.$scene_id,
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
	            $keyword=pdo_fetchcolumn('select keyword from '.tablename('hxq_newywym_reply')." where uniacid={$_W['uniacid']} and type=2 and actid={$act['id']}");
	            $message='长按关注公众号，发送 <span style="color:red">'.$keyword.'</span> 即可领奖！';
	            $msg=array('errno'=>5,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
	            exit(json_encode($msg));
	        }
	    }else{
	        die(json_encode(error(0,'验证通过')));
	    }
	}
	
	function domobilecheckiparea(){
	    global $_W,$_GPC,$_M;
	}
	
	function domobileredirect(){
	    global $_W,$_GPC,$_M;
	    $actid=intval($_GPC['actid']);
	    if (empty($actid)){
	        $msg=error(1,'参数错误') ;
	    }
	    if (is_error($msg)){
	        include $this->template('error');
	        exit();
	    }
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    if (empty($act)){
	        $msg=error(1,'参数错误') ;
	        include $this->template('error');
	        exit();
	    }
	    $shareoutlink=$act['shareoutlink'];
	    header('location:'.$shareoutlink);
	    exit;
	}
	
	public function census($guesturl=''){
	    global $_W,$_GPC,$_M;
	    //echo $guesturl;
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");

	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
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
	    
	}
	
	
	public  function dowebqrcode(){
	    Global $_W,$_GPC;
	    require MODULE_ROOT.'/inc/phpqrcode.php';
	    $qrcode_rdir="images/{$_W['uniacid']}/".$this->modulename.'/';
	    load()->func('file');
	    mkdirs(ATTACHMENT_ROOT.$qrcode_rdir);
	    QRcode::png(urldecode($_GPC['url']),ATTACHMENT_ROOT.$qrcode_rdir.$_GPC['filename'].'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);
	    die(json_encode(array('errno'=>0,'message'=>'成功','qrurl'=>$_W['attachurl_local'].$qrcode_rdir.$_GPC['filename'].'.png')));
	}
	
	public  function domobileqrcode(){
	    Global $_W,$_GPC;
	    require MODULE_ROOT.'/inc/phpqrcode.php';
	    $qrcode_rdir="images/{$_W['uniacid']}/".$this->modulename.'/';
	    load()->func('file');
	    mkdirs(ATTACHMENT_ROOT.$qrcode_rdir);
	    QRcode::png(urldecode($_GPC['url']),ATTACHMENT_ROOT.$qrcode_rdir.$_GPC['filename'].'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);
	    die(json_encode(array('errno'=>0,'message'=>'成功','qrurl'=>$_W['attachurl_local'].$qrcode_rdir.$_GPC['filename'].'.png')));
	}
	
	public function domobileshare(){
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $actid=intval($_GPC['actid']);
	    $cateid=intval($_GPC['cateid']);
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    if (empty($cate)){
	        $msg=error(1,'参数错误') ;
	        exit(json_encode($msg));
	    }
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	    if (empty($act)){
	        $msg=error(1,'参数错误') ;
	        exit(json_encode($msg));
	    }
	    $sharefrom=$_GPC['sharefrom'];
	    if (empty($sharefrom)){
	        $sharefrom=1;
	    }
	        $isshare=pdo_fetch('select * from '.tablename('hxq_newywym_share')." where uniacid={$_W['uniacid']} and sharefrom=$sharefrom and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
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
	                'sharefrom'=>$sharefrom,
	                'addtime'=>TIMESTAMP,
	                'addip'=>getip(),
	                'bizid'=>$act['bizid'],
	            );
	            pdo_insert('hxq_newywym_share',$data);
	        }
	    $msg=error(0,'提交成功') ;
	    exit(json_encode($msg));
	}
	
	protected function regfan($params=array(),$params_=array()){
	    Global $_W,$_GPC;
	    $uid=$_W['fans']['uid'];
	    load()->model('mc');
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$params_['actid']}");
	    if (!empty($act['focusfollow'])&&empty($uid)){  
    	        $reinfo=$this->regmember();
    	        if (!is_error($reinfo)){
    	            $uid=$reinfo['uid'];
    	        }
    	        
    	}
	    $str_params=iserializer($params);
	    $uid=intval($uid);
	    $record = array(
	        'bizid' => $params_['bizid'],
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
	        'faninfoarr'=>$str_params,
	        'addtime' => TIMESTAMP,
	    );
	    pdo_insert('hxq_newywym_fan', $record);
	    $record['id']=pdo_insertid();
	    $reinfo=array('errno'=>0,'message'=>'登记成功','fan'=>$record);
	    return($reinfo);
	}
	
	public function doMobileship(){
	    
	}
	
	public function checkact(){
	    global $_W,$_GPC,$_M;
	    if (empty($_GPC['actid'])){
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where id={$_GPC['actid']}");
            if (empty($act)){
                message('请先创建活动！');
            }
	        $urlparams=$_GET;
	        $urlparams['actid']=$act['id'];
	        header('location:?'.http_build_query($urlparams));
	        exit;
	    }else{
	        $cplen = strlen($_W['config']['cookie']['pre']);
	        foreach($_COOKIE as $key => $value) {
	            if(substr($key, 0, $cplen) == $_W['config']['cookie']['pre']) {
	                $_CK[substr($key, $cplen)] = $value;
	            }
	        }
	        if ($_CK['actid']){
	            if ($_GET['actid']&&$_CK['actid']!=$_GET['actid']){
	                $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where id={$_GET['actid']}");
	                if (!empty($act)){
    	                isetcookie('actid',$_GET['actid'],TIMESTAMP+3600);
    	                $urlparams=$_GET;
    	                $urlparams['actid']=$_GET['actid'];
    	                header('location:?'.http_build_query($urlparams));exit;
	                }
	            }
	        }
	    }
	}
	public function doMobileshippay(){
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $id=$_GPC['id'];
	    if ($id==''){
	        message('请先提交快递订单。');
	    }
	    $ship=pdo_fetch("select * from ".tablename('hxq_newywym_ship')." where  id={$id}");
	    if (empty($ship)){
	        message('快递订单已经被删除！');
	    }
	    $verifid=pdo_fetchcolumn('select verifid from '.tablename('hxq_newywym_awarded').' where id='.$ship['awardedid']);
	    if ($verifid>0){
	        message('该奖品已经线下领取过了，不能提交快递订单。');
	    }
	    $ship['area']=@iunserializer($ship['area']);
	    $ship['pricechoosed']=@iunserializer($ship['pricechoosed']);
	    $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where  (openid='{$ship['openid']}' or oauth_openid='{$ship['oauth_openid']}')");
	    $title=pdo_fetchcolumn('select title from '.tablename('hxq_newywym_awarded')." where id={$ship['awardedid']}");
	/*   $tid='00000000'.$tid;
	    $tid=substr($tid,-8); */
	    $chargerecord=array(
	        'tid'=>$ship['ordersn'],
	        'ordersn' => $ship['ordersn'],  //收银台中显示的订单号
	        'price' =>$ship['pricechoosed']['price'],
	        'title'=>'奖品'.$title.'寄送'.($ship['pricechoosed']['range']=='province'?'省内':'省外').'快递费',
	    );
	    // 一些业务代码。
	    //构造支付请求中的参数
	    $params = array(
	        'tid' => $chargerecord['tid'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
	        'ordersn' => $chargerecord['ordersn'],  //收银台中显示的订单号
	        'title' => $chargerecord['title'],          //收银台中显示的标题
	        'fee' => $chargerecord['price'],      //收银台中显示需要支付的金额,只能大于 0
	        'user' => $_W['member']['uid']     //付款用户, 付款的用户名(选填项)
	    );
	    //调用pay方法
	    $this->pay($params);
	}
	
	public function payResult($ret=array()){
	    global $_W,$_GPC;
	    $uniacid=$_W['uniacid'];
	    $acid=$_W['acid'];
	    if ($ret['from']=='return'){
	        $params['ordersn']=$ret['tid'];
	        $params['uniacid']=$ret['uniacid'];
	        $plid=pdo_fetchcolumn('select plid from '.tablename('core_paylog')." where uniacid={$uniacid} and tid='{$ret['tid']}'");
	     
	        pdo_update('hxq_newywym_ship',array('status'=>1,'paylogid'=>$plid),$params);
	        $awardedid=pdo_fetchcolumn('select awardedid from'.tablename('hxq_newywym_ship')." where uniacid={$uniacid} and ordersn='{$ret['tid']}'");
	        $activityid=pdo_fetchcolumn('select actid from '.tablename('hxq_newywym_ship')." where uniacid=:uniacid and ordersn=:ordersn",$params);
	        //echo $this->createmobileurl('my',array('actid' => $activityid,'id'=>$awardedid)).'#awarded'.$awardedid;
	        message('支付成功，主办方发货后您会收到公众号消息通知请注意查看，即将返回我的奖品页！',$this->createmobileurl('my',array('actid' => $activityid,'id'=>$awardedid),false).'#awarded'.$awardedid,'success');
	    }
	    if ($ret['from']=='notify'){
	        $params['ordersn']=$ret['tid'];
	        $params['uniacid']=$ret['uniacid'];
	        $status=pdo_fetchcolumn('select status from '.tablename('hxq_newywym_ship').' where ordersn=:ordersn and uniacid=:uniacid',$params);
	        if ($status==1){
	            $result = array(
	                'return_code' => 'success',
	                'return_msg' => 'success'
	            );
	            echo array2xml($result);
	            exit;
	        }else{
	            $params_['ordersn']=$ret['tid'];
	            $params_['uniacid']=$ret['uniacid'];
	            pdo_update('hxq_newywym_ship',array('status'=>1),$params_);
	            $result = array(
	                'return_code' => 'success',
	                'return_msg' => 'success'
	            );
	            echo array2xml($result);
	            exit;
	        }
	    }
	    $result = array(
	                'return_code' => 'fail',
	                'return_msg' => 'fail'
	            );
	    echo array2xml($result);
	    exit;
	}
	
	protected function sendnotice($postdata){
	    global $_W,$_GPC,$_M;
        $acct=WeAccount::create($_W['uniacid']);
	    $config=$this->module['config'];
        if(strpos($config['notice']['openids'],'|')!==false){
            $openids=explode($config['notice']['openids'],'|');
            foreach($openids as $openid){
                $acct->sendTplNotice($openid, $config['notice']['tplid'], $postdata);
            }
        }else{
            return $acct->sendTplNotice($config['notice']['openids'], $config['notice']['tplid'], $postdata);
        }
	}
	public function doMobilelocation(){
	    require MODULE_ROOT.'/inc/mobileinit.php';
	    $scan=pdo_fetch('select * from '.tablename('hxq_newywym_scan')." where uniacid={$_W['uniacid']} and id={$_GPC['scanid']}");
	    if (!empty($scan)){
	        $actid=$_GPC['actid'];
	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$actid}");
	        if ($act['dscanwarning']&&$_GPC['vdistrict']=='false'){
	            $chdata=array(
	                'uniacid'=>$_W['uniacid'],
	                'actid'=>$act['id'],
	                'actname'=>$act['name'],
	                'district'=>$act['district'],
	                'tk'=>$scan['tk'],
	                'scandistrict'=>js_unescape($_COOKIE['address']),
	                'oauth_openid'=>$_SESSION['oauth_openid'],
	                'nickname'=>$_W['fans']['nickname'],
	                'addtime'=>TIMESTAMP,
	            );
	            pdo_insert('hxq_newywym_ch',$chdata);
	            if ($this->module['config']['notice']['chtplid']&&$this->module['config']['notice']['chtplopenids']){
	            $postdata=array(
	                'first'=>array('value'=>$act['name'].'[出现异地扫码] 扫码人 '.$_W['fans']['nickname'].' 扫码地 '.js_unescape($_COOKIE['address'])),
	                'keyword1'=>array('value'=>'该批次二维码定义的销售地 '.$act['district'],'color'=>'#FF0000'),
	                'keyword2'=>array('value'=>date('Y-m-d H:i:s',TIMESTAMP)),
	                'remark'=>array('value'=>'异地销售信息已记录到数据库','color'=>'#FF0000'),
	            );
	            sendtplmessage($postdata,$this->module['config']['notice']['chtplid'],$this->module['config']['notice']['chtplopenids']);
	            }
	        }
	        pdo_update('hxq_newywym_scan',array('province'=>js_unescape($_COOKIE['province']),'city'=>js_unescape($_COOKIE['city']),'district'=>js_unescape($_COOKIE['district']),'street'=>js_unescape($_COOKIE['street']),'streetnum'=>js_unescape($_COOKIE['streetnum'])),array('id'=>$_GPC['scanid']));
	        echo json_encode(error(0,'更新成功'));
            exit;	       
	    }
	    echo json_encode(error(1,'更新失败'));
	}

	public function doMobilelotteryposition(){
	    global $_W,$_GPC,$_M;
	    require MODULE_ROOT.'/inc/function.php';
	    $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and tk={$_GPC['tk']} and `oauth_openid`='{$_SESSION['oauth_openid']}'");
	    if (!empty($token)){
	        pdo_update('hxq_newywym_token',array('province'=>js_unescape($_COOKIE['province']),'city'=>js_unescape($_COOKIE['city']),'district'=>js_unescape($_COOKIE['district']),'street'=>js_unescape($_COOKIE['street']),'streetnum'=>js_unescape($_COOKIE['streetnum'])),array('tk'=>$_GPC['tk']));
	    }
	    $give=pdo_fetch('select * from '.tablename('hxq_newywym_give')." where uniacid={$_W['uniacid']} and tk={$_GPC['tk']} and `oauth_openid`='{$_SESSION['oauth_openid']}'");
	    if (!empty($give)){
	        pdo_update('hxq_newywym_give',array('province'=>js_unescape($_COOKIE['province']),'city'=>js_unescape($_COOKIE['city']),'district'=>js_unescape($_COOKIE['district']),'street'=>js_unescape($_COOKIE['street']),'streetnum'=>js_unescape($_COOKIE['streetnum'])),array('tk'=>$_GPC['tk']));
	    }
	}
	
	public function doMobileupdatetoken(){
	    global $_W,$_GPC,$_M;
	    require MODULE_ROOT.'/inc/function.php';
	    if (empty($_GPC['id'])){
	        die(json_encode(error(1,'未指定id')));
	    }
	    $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and id={$_GPC['id']}");
	    $award=pdo_fetch('select * from '.tablename('hxq_newywym_award')." where uniacid={$_W['uniacid']} and id={$token['awardid']}");
	    $rst=pdo_update('hxq_newywym_token',array('isgrant'=>1),array('id'=>$_GPC['id']));
	    $award['description']=str_replace(array(" ","\r\n","\r","\n"),array('&nbsp;','<br>','<br>','<br>'), $award['description']);
	     
	       if ($rst){
	           die(json_encode(array('errno'=>0,'message'=>'更新成功','description'=>$award['description'])));
	       }else{
	           die(json_encode(array('errno'=>``,'message'=>'更新失败','description'=>$award['description'])));
	       }
	}
	
	
	protected function dooauth($focus=false){
	    global $_W,$_GPC;
	    //var_dump($_W['fans']);
	    if ($_W['container']=='wechat'&&((empty($_W['fans']['nickname'])||empty($_W['fans']['tag']['avatar']))||$focus)){
	            if (empty($_SESSION['userinfo'])){
	                mc_oauth_userinfo();
	            }
	            $userinfo=unserialize(base64_decode($_SESSION['userinfo']));
	            if (is_error($userinfo)){
	                message($userinfo['message']);
	            }
	            $_W['fans']['nickname']=$userinfo['nickname'];
	            $_W['fans']['tag']=array();
	            $_W['fans']['tag']['avatar']=$userinfo['avatar'];
	    }
	}
	
	protected function template($filename) {
	    global $_W,$_GPC,$_M;
	    $name = strtolower($this->modulename);
	    $defineDir = dirname($this->__define);
	    if(defined('IN_SYS')) {
	        $source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
	        $compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
	        if(!is_file($source)) {
	            $source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
	        }
	        if(!is_file($source)) {
	            $source = $defineDir . "/template/{$filename}.html";
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
	        exit("Error: template source '{$filename}' is not exist!");
	    }
	    $paths = pathinfo($compile);
	    $compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
	    if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
	        template_compile($source, $compile, true);
	    }
	    return $compile;
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
         pdo_update('mc_mapping_fans', array('uid' => $uid), array('openid' => $_SESSION['openid']));
         return array('errno'=>0,'uid'=>$uid);
	}
	
	
	public function timediff($begin_time,$end_time='')
	{
		if (empty($end_time)){$end_time=TIMESTAMP;}
	  if ( $begin_time < $end_time ) {
		$starttime = $begin_time;
		$endtime = $end_time;
	  } else {
		$starttime = $end_time;
		$endtime = $begin_time;
	  }
	  $timediff = $endtime - $starttime;
	  $days = intval( $timediff / 86400 );
	  $remain = $timediff % 86400;
	  $hours = intval( $remain / 3600 );
	  $remain = $remain % 3600;
	  $mins = intval( $remain / 60 );
	  $secs = $remain % 60;
	  if ($days>0){
		return $days.'天前';  
	  }elseif ($hours>0){
		  return $hours.'小时前'; 
	  }
	  elseif ($mins>0){
		  return $mins.'分钟前'; 
	  }
	  elseif ($secs>0){
		  return $secs.'秒前'; 
	  }
	  //$res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
	  //return $res; www.shuotupu.com
	}
	
	
	protected function formatdatecn($date=''){
	    if (empty($date)){
	        $date=time();
	    }
	       $year=date('Y',$date); 
	       $month=date('m',$date);
	       $day=date('d',$date);
	       $hour=date('h',$date);
	       $minute=date('i',$date);
	       $datacn=$month.'月'.$day.'日'.$hour.'时';
	       return $datacn;
	}
	 
	
	
}
   ?>
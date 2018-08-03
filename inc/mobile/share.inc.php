<?php
require_once MODULE_ROOT.'/inc/mobileinit.php';
$tk=intval($_GPC['tk']);
$vtk=addslashes($_GPC['vtk']);
if (empty($tk)){
    $msg=array('errno'=>1,'message'=>'请使用微信扫码，参与活动！');
    include $this->template('error');
    exit();
}
if (empty($vtk)){
    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where mintk<=$tk and maxtk>=$tk");
    if(empty($cate)){
        $msg=error(1,'请通过扫描产品的二维码参与活动！');
        include $this->template('error') ;
        exit;
    }
}else{
    $msg=$this->checktoken($_M['slat'],$tk,$vtk);
    if ($msg['errno']==1){
        $msg=error(1,'请通过扫描产品的二维码参与活动！');
        include $this->template('error') ;
        exit;
    }
    $cate=$msg['cate'];
}
if (empty($cate['actid'])){
    $msg=error(1,'该码段尚未启用！');
    include $this->template('error') ;
    exit;
}
$act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where id={$cate['actid']}");
$act['page']=iunserializer($act['page']);
$act['shortjj']=str_replace(array("\n","\r","\n\r"),'',$act['jj']);
$act['shortjj_']=cutstr($act['shortjj'],50);
$act['jj_']=str_replace(array("\r\n",'\r','\n',' '),array('</br>','</br>','</br>','&nbsp;'),$act['jj']);
if (!empty($act['district'])){
    if (strpos($act['district'],',')!==false){
        $act['districtarr']=explode(',',$act['district']);
        $act['districtarr']=array_filter($act['districtarr']);
    }else{
        $act['districtarr'][]=$act['district'];
    }
}
$_M['act']=$act;
$_M['cate']=$cate;
if (TIMESTAMP<$act['starttime']||TIMESTAMP>$act['endtime']){
    $msg=error(1,"{$act['name']}活动时间".date('Y-m-d',$act['starttime']).'至'.date('Y-m-d',$act['endtime']).'。');
    include $this->template('error') ;
    exit;
}
if (empty($_SESSION['oauth_openid'])&&!$_GPC['preview']){
    $msg=array('errno'=>1,'message'=>'请使用微信扫码，参与活动！');
    include $this->template('error');
    exit();
}
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
            $message='长按识别二维码关注公众号后，点击弹出的图文信息即可抽奖。';
        }else{
            $qrurl=$_W['account']['qrcode'];
            $message='长按识别二维码关注公众号后，再次扫码即可抽奖！';
        }
        $msg=array('errno'=>4,'message'=>$message,'qrurl'=>$qrurl);
        include $this->template('follow');
        exit;
    }else{
        $qrurl=$_W['account']['qrcode'];
        $message='长按二维码关注公众号，发送口令即可领取红包！';
        $msg=array('errno'=>4,'message'=>$message,'qrurl'=>$qrurl,'tkvtk'=>$tk.'_'.$vtk);
        include $this->template('follow');
        exit;
    }
}
$fan=pdo_fetch('select * from '.tablename('hxq_newywym_fan')." where uniacid={$_W['uniacid']} and oauth_openid='{$_SESSION['oauth_openid']}'");
$totalscan=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and actid={$act['id']} and oauth_openid='{$_SESSION['oauth_openid']}'");
$totalscan=intval($totalscan);
$totalscan++;
$_M['fan']=$fan;
$_M['totalscan']=$totalscan;
if (!is_error($msg)&&$act['blacklist']&&!empty($fan)){
    $member=mc_fetch($fan['uid'],array('groupid'));
    if (in_array($member['groupid'],$act['blacklistgroups'])){
        $msg=array('errno'=>3,'message'=>"活动尚未开始，敬请期待");//黑名单限制，为了好听用尚未开始的字眼
    }
}
$awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and actid={$cate['actid']}  order by sort desc");
foreach ($awards as $key=>&$row){
    if ($row['scantimes']>0&&$totalscan<$row['scantimes']){
        $row['disable']=1;
    }
    $row['thumb']=$_W['attachurl'].$row['thumb'];
}
unset($row);
$rctawarded=pdo_fetchall('select * from '.tablename('hxq_newywym_token')." where uniacid={$uniacid} and actid={$cate['actid']} and type!=100 order by id desc limit 0,10");
include $this->template('share');
exit;

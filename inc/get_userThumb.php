<?php
require_once '../../framework/bootstrap.inc.php';
$eid = intval($_GPC['eid']);
if(!empty($eid)) {
    $sql = 'SELECT * FROM ' . tablename('modules_bindings') . ' WHERE `eid`=:eid';
    $entry = pdo_fetch($sql, array(':eid' => $eid));
    $_GPC['m'] = $entry['module'];
} else {
    $entry = array(
        'module' => $_GPC['m'],
        'do' => $_GPC['do'],
        'state' => $_GPC['state'],
        'direct' => 0
    );
}
$_W['uniacid']=$_GPC['i'];
$moduels = uni_modules();
if (empty($moduels[$entry['module']])) {
    WeUtility::logging('warning',"{$entry['module']}:您访问的功能模块不存在，请重新进入,粉丝openid:{$_GPC['openid']}");
    exit;
}
if(empty($entry)) {
    WeUtility::logging('warning',"{$entry['module']}:非法访问,粉丝openid:{$_GPC['openid']}");
    exit;
}
$_GPC['__entry'] = $entry['title'];
$_GPC['__state'] = $entry['state'];
define('IN_MODULE', $entry['module']);
$site = WeUtility::createModuleSite($entry['module']);
if(!is_error($site)) {
    $method = 'doMobile' . ucfirst($entry['do']);
    $moduleconfig=$site->module['config'];
    $actid=$_GPC['actid'];
    if (empty($actid)){
        WeUtility::logging('warning',"{$entry['module']}:模块调用红包程序，未指定活动id,粉丝openid:{$_GPC['openid']}");
        exit();
    }
    $act=$site->getactivity();
    $fan=pdo_fetch("select * from ".tablename('hxq_clctcard_fan')." where actid={$act['id']} and id='{$_GPC['fid']}'");
    if (empty($fan)){
        echo json_encode(error(1,'粉丝不存在！'));
        exit;
    }
	$sharethumb=pdo_fetchcolumn('select sharethumb from '.tablename('hxq_clctcard_fan')." where actid={$act['id']} and id='{$fan['id']}'");
    if (!empty($sharethumb)){
        echo json_encode(array('errno'=>0,'message'=>'已生成'));
        exit;
    }
    load()->func('file');
    if (!is_dir(ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/')){
        mkdirs(ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/');
    }
    if (strpos($fan['avatar'],'http')!==false){
        $avatar=file_get_contents($fan['avatar']);
        if (!$avatar){
            $rst=copy(MODULE_ROOT.'/resources/images/noavatar.jpg',ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_avt.jpg');
        }else{
            $rst=file_put_contents(ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_avt.jpg',$avatar);
        }
        if ($rst){
        $avt_filedir=ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_avt.jpg';
        $avt_filename='images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_avt.jpg';
        pdo_update('hxq_clctcard_fan',array('avatar'=>$avt_filename),array('id'=>$_GPC['fanid']));
        }
    }
    $shareurl=murl('entry//index',array('m'=>$this->modulename,'actid'=>$act['id'],'do'=>'grant','sid'=>$fan['id']),true,true);
    $shareurl=str_replace('&','%26',$shareurl);
    $qrapi='http://qr.topscan.com/api.php?text='.$shareurl;
    $qrfile=file_get_contents($qrapi);
    if ($qrfile){
        $rst=file_put_contents(ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_qr.png',$qrfile);
        if ($rst){
           $qr_filedir=ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_qr.png';
           $qr_filename='images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_qr.png';
           pdo_update('hxq_clctcard_fan',array('qrcode'=>$qr_filename),array('id'=>$_GPC['fanid']));
        }
    }
    if ($avt_filename&&$qr_filename){
        $avtleft=$act['sharesetting']['avtleft'];
        $avttop=$act['sharesetting']['avttop'];
        $avtsize=$act['sharesetting']['avtsize'];
        $qrleft=$act['sharesetting']['qrleft'];
        $qrtop=$act['sharesetting']['qrtop'];
        $qrsize=$act['sharesetting']['qrsize'];
        $sharebgsize=getimagesize(ATTACHMENT_ROOT.$act['sharesetting']['fansharebg']);
        switch($sharebgsize[2]){
            case 1:
                $shareformat='gif';
                $sharebg = imagecreatefromgif(ATTACHMENT_ROOT.$act['sharesetting']['fansharebg']);
                break;
            case 2:
                $shareformat='jpg';
                $sharebg = imagecreatefromjpeg(ATTACHMENT_ROOT.$act['sharesetting']['fansharebg']);
                break;
            case 3:
                $shareformat='png';
                $sharebg = imagecreatefrompng(ATTACHMENT_ROOT.$act['sharesetting']['fansharebg']);
                break;
        }
        $avatarsize=getimagesize($avt_filedir);
        list($wh, $hh) = $avatarsize;
        switch($avatarsize[2]){
            case 1:
                $avatar = imagecreatefromgif($avt_filedir);
                break;
            case 2:
                $avatar = imagecreatefromjpeg($avt_filedir);
                break;
            case 3:
                $avatar = imagecreatefrompng($avt_filedir);
                break;
        }
        //response(error(1,$avtsize.$wh,$hh))
        imagecopyresized($sharebg,$avatar,$avtleft,$avttop,0,0,$avtsize,$avtsize,$wh,$hh);
        $qrfilesize=getimagesize($qr_filedir);
        list($wh, $hh) = $qrfilesize;
        switch($qrfilesize[2]){
            case 1:
                $qrfile = imagecreatefromgif($qr_filedir);
                break;
            case 2:
                $qrfile = imagecreatefromjpeg($qr_filedir);
                break;
            case 3:
                $qrfile = imagecreatefrompng($qr_filedir);
                break;
        }
        imagecopyresized($sharebg,$qrfile,$qrleft,$qrtop,0,0,$qrsize,$qrsize,$wh,$hh);
        $font = MODULE_ROOT."/resources/images/stxinwei.ttf";
        $text = strtoupper(substr(PHP_OS,0,3))==='WIN'?"我是\"{$fan['nickname']}\"\r\n快来帮我一起集字卡吧!":"我是\"{$fan['nickname']}\"\n快来帮我一起集字卡吧!";
        $col = imagecolorallocate($sharebg, 255, 255, 255);
        imagettftext($sharebg, 14, 0, $act['sharesetting']['tipleft'], $act['sharesetting']['tiptop']+14, $col,$font,$text);
        ob_start();
        switch ($shareformat){
            case 'gif':imagegif($sharebg);break;
            case 'jpg':imagejpeg($sharebg);break;
            case 'png':imagepng($sharebg);break;
        }
        $sharethumb=ob_get_contents();
        ob_clean();
        $rst=file_put_contents(ATTACHMENT_ROOT.'images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_hb.'.$shareformat,$sharethumb);
        if ($rst){
            $st_filedir=ATTACHMENT_ROOT.'/images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_hb.'.$shareformat;
            $st_filename='/images/'.$uniacid.'/'.$this->modulename.'/'.$act['id'].'/'.$_SESSION['oauth_openid'].'_hb.'.$shareformat;
            pdo_update('hxq_clctcard_fan',array('sharethumb'=>$st_filename),array('id'=>$fan['id']));
        }
        echo json_encode(error(1,'海报创建成功！'));
        exit;
    }
    echo json_encode(error(1,'无法创建邀请海报，请检查应用配置！'));
    exit;
}
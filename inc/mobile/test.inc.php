<?php

require MODULE_ROOT.'/inc/phpqrcode.php';
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$_GPC['cateid']}");
	    $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']}");
	     
	    for($i=$cate['mintk'];$i<=$cate['maxtk'];$i++){
	        $exists=pdo_fetchcolumn('select * from '.tablename('hxq_newywym_token')." where tk={$i} and isgrant=1");
	        if (empty($exists)){
	            $tk=$i;
	            break;
	        }
	    }
	    $vtk=substr(md5($tk.$_M['slat']),0,8);
	    $qrcode_rdir="images/{$_W['uniacid']}/".$this->modulename.'/'.$act['id'].'/';
	    $url=$_W['siteroot'].'/app/'.$this->createmobileurl('index',array('tk'=>$tk,'vtk'=>$vtk));
	    QRcode::png($url,ATTACHMENT_ROOT.$qrcode_rdir.$tk.'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);
        echo '<div style="text-align:center"><img src="'.$_W['attachurl_local'].$qrcode_rdir.$tk.'.png'.'" style="margin-top:200px"></div>';
        echo '<div style="text-align:center">识别二维码体验</div>';

	    
	    
	


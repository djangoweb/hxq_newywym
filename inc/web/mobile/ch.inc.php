<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';
	    $this->dooauth();
	    if (empty($_SESSION['oauth_openid'])){
	        $msg=array('errno'=>2,'message'=>'系统设置有误');
	        exit(json_encode($msg));
	    }
	    $guesturl=urldecode($_GPC['guesturl']);
	    $this->census($guesturl);
	    
	    
	    
	    
	    
	


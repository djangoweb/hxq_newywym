<?php   global $_W,$_GPC,$_M;
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$_GPC['actid']}");
	    $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where uniacid={$_W['uniacid']} and id={$act['bizid']}");
	    $coupons=pdo_fetchall("select * from ".tablename('hxq_newywym_coupon')." where uniacid={$_W['uniacid']} and enable=1 and isshow=1 and bizid={$biz['id']} and endtime>unix_timestamp()");
	    if (empty($coupons)){
	        $msg=error(1,'参数错误') ;
	        die(json_encode($msg));
	    }
	    foreach($coupons as $key=>&$row){
	        $row['starttime']=date('Y-m-d',$row['starttime']);
	        $row['endtime']=date('Y-m-d',$row['enttime']);
	    }
	    unset($row);
	    $msg=array('errno'=>0,'message'=>'成功','coupons'=>$coupons) ;
	    die(json_encode($msg));
	    exit;
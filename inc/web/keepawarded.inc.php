<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $actid=$_GPC['actid'];
	    $op=in_array($_GPC['op'],array('display','export','grantaward','delete','verif'))?$_GPC['op']:'display';
	    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
	    $bizid=$_GPC['bizid'];
	    $actid=$_GPC['actid'];
	    //error_reporting(E_ALL);
	    if ($actid){
	        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id=$actid order by id desc");
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and  bizid={$act['bizid']} order by id desc",array(),'id');
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
	    }elseif ($bizid){
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$bizid} order by id desc");
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and bizid={$bizid} order by id desc",array(),'id');
	    }else{
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid order by id desc",array(),'id');
	    }
	    if ($biz){
	        $where.=" and bizid={$biz['id']} ";
	    }
	    if ($op=='delete'){
	        $id=$_GPC['id'];
	        if (empty($id)){
	            message('未指定中奖记录id');
	        }
	        $awarded=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid=$uniacid and id=$id");
	        if (empty($awarded)){
	            message('找不到中奖纪录');
	        }
	        pdo_delete('hxq_newywym_token',array('tk'=>$awarded['tk']));
	        message('删除中奖记录成功，二维码可以重新使用！',referer(),'success');
	    }
	    if ($op=='export'){
	        $name='中奖记录';
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".$name.".xls");
	        $sql="select * from ".tablename('hxq_newywym_shtmline')." where  uniacid={$uniacid} $where order by id asc";
	        $awardeds=pdo_fetchall($sql);
	        $strexport="微信名\topenid\t奖品\t金额\t中奖时间\r";
	        $totalrp=0;
	        foreach ($awardeds as $row){
	            if ($row['type']==1){
	                $totalrp+=$row['redpack'];
	            }
	            $strexport.=$row['nickname']?$row['nickname']:'匿名'; 
	            $strexport.="\t".$row['oauth_openid'];
	            $strexport.="\t".$awards[$row['awardid']]['title'];
	            $strexport.="\t".$awards[$row['awardid']]['redpack'];
	            $strexport.="\t(".date('Y-m-d H:i',$row['addtime']).')';
	            $strexport.="\r";
	        }
	        $strexport.="\t"."\t"."\t".$totalrp."\r";
	        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
	        exit($strexport);
	    }
	    
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_shtmline')." where uniacid={$uniacid} and isgrant=1 $where");
	    //echo "select count(id) from ".tablename('hxq_newywym_fission')." where uniacid={$uniacid} and isgrant=1 $where and type!=5";
	    $pagesize=30;
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start = ($page - 1) * $pagesize;
	    $limit .= " LIMIT {$start},{$pagesize}";
	    $pager = pagination($total, $page, $pagesize);
	    //echo "select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1  {$where} and type!=5 order by addtime desc {$limit}";
	    $awardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_shtmline')." where uniacid={$uniacid} and isgrant=1  {$where} order by addtime desc {$limit}");
	    
	    unset($row);
	    include $this->template('keepawarded');
	
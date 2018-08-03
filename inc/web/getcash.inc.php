<?php 
	    global $_W,$_GPC,$_M;
	    $op=in_array($_GPC['op'],array('display','export','grantaward','delete','verif'))?$_GPC['op']:'display';
	    $uniacid=$_W['uniacid'];
        $actid=$_GPC['actid'];
    	$acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
    	if (!empty($actid)){
    	    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
    	}
    	if (empty($acts)){
    	    message('请先创建活动再管理串货信息！');
    	}
    	if ($act){
    	   $where.=" and actid={$act['id']} ";
    	}
	    if ($op=='export'){
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".TIMESTAMP.".xls");
	        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} and actid={$actid}",array(),'id');
	        if (!empty($condition)){
	            $sql="select * from ".tablename('hxq_newywym_awarded')." where actid={$actid} and uniacid={$uniacid} {$condition} order by id asc";
	        }else{
	            $sql="select * from ".tablename('hxq_newywym_awarded')." where actid={$actid} and uniacid={$uniacid} and type!=10000 order by id asc";
	        }
	        
	        $awardeds=pdo_fetchall($sql);
	        $strexport="微信名\t登记信息\t奖品\t已领取\t中奖时间\t核销时间\t地址\r";
	        foreach ($awardeds as $row){
	            $strexport.=str_replace(array('"',"\'"),'',$row['nickname']); 
	            $row['faninfoarr']=@iunserializer($row['faninfoarr']);
	            foreach ($row['faninfoarr'] as $key=>$item){
	                $strrequireinfo.="{$this->act['faninfoarr'][$key]}:$item  ";
	            }
	            $strexport.="\t".$strrequireinfo;
	            $strexport.="\t".$awards[$row['awardid']]['title'];
	            $strexport.="\t".($row['isgrant']?'已领取':'未领取');
	            $strexport.="\t".date('Y-m-d H:i',$row['addtime']);
	            $strexport.="\t".(empty($row['veriftime'])?'未核销':date('Y-m-d H:i',$row['veriftime']));//要改
	            $strexport.="\t".$row['address'];
	            $strexport.="\r";
	            $strrequireinfo='';
	        }
	        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
	        exit($strexport);
	    }
	    /* $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} and actid={$actid}",array(),'id');
	    $awardids=pdo_fetchall("select id from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} and actid={$actid}");
	    $awardids=array_map('array_shift', $awardids);
	    $strawardids=implode($awardids,",");
	    if(!empty($strawardids)){
    	    $awardstotal=pdo_fetchall("select  count(id) as total,awardid from ".tablename('hxq_newywym_awarded')." where  uniacid={$uniacid} and actid={$actid} and awardid in ({$strawardids}) $where group by awardid"); 
    	    //echo "select  count(a.id) as total,a.awardid,b.* from ".tablename('hxq_newywym_awarded')." a inner join ".tablename('hxq_newywym_award')." b on a.awardid=b.id where  a.uniacid={$uniacid} and a.activityid={$actid} and a.awardid in ({$strawardids})  group by a.awardid"; 
    	    foreach($awardstotal as $key=>&$row){
    	        
    	    }
    	    unset($row);
	    } */
	    
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cash')." where uniacid={$uniacid}   $where");
	    $pagesize=30;
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start = ($page - 1) * $pagesize;
	    $limit .= " LIMIT {$start},{$pagesize}";
	    $pager = pagination($total, $page, $pagesize);
	    $awardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_cash')." where uniacid={$uniacid}   {$where} order by addtime desc {$limit}");
	    foreach($awardeds as $key=>&$row){
	        //$row['ship']=pdo_fetch('select id from '.tablename('hxq_newywym_ship').' where uniacid=:uniacid and activityid=:activityid and awardedid=:awardedid',array('activityid:'=>$row['ship'],'activityid:'=>$row['ship'],'activityid:'=>$row['ship']));
	        $row['title']=$awards[$row['awardid']]['title'];
	        $row['faninfoarr']=@iunserializer($row['faninfoarr']);
	    }
	    unset($row);
	    include $this->template('getcash');
	
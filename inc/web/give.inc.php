<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $actid=$_GPC['actid'];
	    $op=in_array($_GPC['op'],array('display','export','grantaward','delete','verif'))?$_GPC['op']:'display';
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
	    if ($op=='delete'){
	        $id=$_GPC['id'];
	        if (empty($id)){
	            message('未指定中奖记录id');
	        }
	        $awarded=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and id=$id");
	        if (empty($awarded)){
	            message('找不到中奖纪录');
	        }
	        $awardid=$awarded['awardid'];
	        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where  uniacid={$uniacid} and actid={$awarded['actid']}",array(),'id');
	        $rst=pdo_delete('hxq_newywym_token',array('id'=>$id));
	        if ($rst){
	            $award=pdo_fetch("select * from ".tablename('hxq_newywym_award')." where id={$awarded['awardid']}");
	            if (!empty($award)){
	                $award['dayawarded']=iunserializer($award['dayawarded']);
	                if (array_key_exists($this->date,$award['dayawarded'])){
	                    $award['dayawarded'][$date]-=1;
	                }
	            }
	            $award['dayawarded']=iserializer($award['dayawarded']);
	            $award['awarded']-=1;
	            $data=array('awarded'=>$award['awarded'],'dayawarded'=>$award['dayawarded']);
	            pdo_query('hxq_newywym_award',$data,array('id'=>$award['id']));
	            $fan=pdo_fetch('select * from '.tablename('hxq_newywym_fan')." where cateid={$cateid} and oauth_openid='{$awarded['oauth_openid']}'");
	            if (!empty($fan)){
	                $fan['daylottery']=iunserializer($fan['daylottery']);
	                if (array_key_exists($this->date,$fan['daylottery'])){
	                    $fan['daylottery'][$this->date]-=1;
	                }
	                $fan['lottery']-=1;
	                $fan['dayawarded']=iunserializer($fan['dayawarded']);
	                if ($awarded['type']!=10000){
	                    if (array_key_exists($this->date,$fan['dayawarded'])){
	                        $fan['dayawarded'][$this->date]-=1;
	                    }
	                    $fan['dayawarded']-=1;
	                }
	                $fan['daylottery']=iserializer($fan['daylottery']);
	                $fan['dayawarded']=iserializer($fan['dayawarded']);
	                $data=array(
	                'daylottery'=>$fan['daylottery'],
	                'lottery'=>$fan['lottery'],
	                'dayawarded'=>$fan['dayawarded'],
	                'awarded'=>$fan['awarded']
	                );
	                pdo_update('hxq_newywym_fan',$data,array('id'=>$fan['id']));
	            }
	            if ($awarded['verifid']){
	                pdo_delete('hxq_newywym_verif',array('id'=>$awarded['verifid']));
	            }
	        }	
	        message('删除成功',referer(),'success');
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
	    $totaltoken=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where");
	    $totalcredit1=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=3");
	    $sumcredit1=pdo_fetchcolumn("select sum(credit1) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=3");
	    $totalrp=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=1");
	    $sumrp=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=1");	     
	    $sumcredit1=$sumcredit1?$sumcredit1:0;
	    $sumrp=$sumrp?$sumrp:0;
	    $daytotaltoken=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
	    $daytotalcredit1=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=3 and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
	    $daysumcredit1=pdo_fetchcolumn("select sum(credit1) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=3 and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
	    $daytotalrp=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=1 and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
	    $daysumrp=pdo_fetchcolumn("select sum(redpack) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where and type=1 and addtime>=".strtotime(date('Y-m-d 00:00:00'))." and addtime<=".strtotime(date('Y-m-d 23:59:59')));
	    $daysumcredit1=$daysumcredit1?$daysumcredit1:0;
	    $daysumrp=$daysumrp?$daysumrp:0;
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   $where");
	    $pagesize=30;
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start = ($page - 1) * $pagesize;
	    $limit .= " LIMIT {$start},{$pagesize}";
	    $pager = pagination($total, $page, $pagesize);
	    $gives=pdo_fetchall("select * from ".tablename('hxq_newywym_give')." where uniacid={$uniacid}   {$where} order by addtime desc {$limit}");
/* 	    foreach($awardeds as $key=>&$row){
	        //$row['ship']=pdo_fetch('select id from '.tablename('hxq_newywym_ship').' where uniacid=:uniacid and activityid=:activityid and awardedid=:awardedid',array('activityid:'=>$row['ship'],'activityid:'=>$row['ship'],'activityid:'=>$row['ship']));
	        //$row['faninfoarr']=@iunserializer($row['faninfoarr']);
	    } */
	    unset($row);
	    include $this->template('give');
	
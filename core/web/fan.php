<?php 
class fan extends page{ public function main($op=''){
	    global $_W,$_GPC,$_M;
	    load()->model('mc');
	    $uniacid=$_W['uniacid'];
	    $groups=mc_groups($uniacid);
	    $op=in_array($op,array('display','export','disable','enable','delete'))?$op:'display';
        
        if ($op=='delete'){
	        $fan=pdo_fetch("select * from ".tablename('hxq_newywym_fan')." where id={$_GPC['id']}");
            $awardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid}  and  oauth_openid='{$fan['oauth_openid']}'");
            foreach($awardeds as $key=>$row){
               //$award=pdo_fetch("select * from ".tablename('hxq_newywym_token')." where id={$row['awardid']} ");
               /* $award['dayawarded']=iunserializer($award['dayawarded']);
               if ($row['type']!=10000){
                   if (array_key_exists($this->date,$fan['dayawarded'])){
                       $fan['dayawarded'][$this->date]-=1;
                   }
                   if (array_key_exists($this->date,$fan['daylottery'])){
                       $fan['daylottery'][$this->date]-=1;
                   }
                   $fan['lottery']-=1;
                   $fan['awarded']-=1; 
                   if (array_key_exists($this->date,$award['dayawarded'])){
                       $award['dayawarded']-=1;
                   }
                   $award['awarded']-=1;
               }
               $data=array('awarded'=>$award['awarded'],'dayawarded'=>iserializer($award['dayawarded'])); */
               if ($row['gcid']){
                   pdo_delete('hxq_newywym_gcid',array('id'=>$row['gcid']));
               }
	        }
	        pdo_delete('hxq_newywym_token',array('oauth_openid'=>$fan['oauth_openid']));
	        pdo_delete('hxq_newywym_fan',array('id'=>$_GPC['id']));
	        message('操作成功','referer','success');
	    }
	    if ($op=='export'){
	        set_time_limit(0);
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".TIMESTAMP.".xls");
	        if ($act['getfaninfo']){
	            $exstr="微信名";
	            foreach($act['faninfoarr'] as $key=>$item){
	                $exstr.="\t".$item;
	            };
	            $exstr.="\t性别\t省\t市\r";
	        }else{
	            $exstr="微信名\t性别\t省\t市\r";
	        }
	        if (!empty($cateid)){
	           $where.=" and cateid=$cateid ";
	        }
	        $fans=pdo_fetchall("select *  from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}  $where order by id desc");
	        foreach($fans as $key=>&$row){
	            $exstr.="{$row['nickname']}";
	            if ($act['getfaninfo']){
	                $row['faninfoarr']=@iunserializer($row['faninfoarr']);
	                foreach($act['faninfoarr'] as $key=>$item){
	                    $exstr.="\t".$row['faninfoarr'][$key];
	                };
	            }
	            $exstr.="\t".($row['sex']==1?'男':($row['sex']==2?'女':'未知'));
	            $exstr.="\t{$row['province']}";
	            $exstr.="\t{$row['city']}\r";
	        }
	        $exstr=iconv('UTF-8',"GB2312//IGNORE",$exstr);
	        exit($exstr);
	    }
	    if ($_W['role']=='operator'){
	       $biz=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	       $where=" and bizid = {$biz['id']}";
	       $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$act['bizid']} order by id desc",array(),'id');
	       if ($_GPC['actid']){
	           $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']} $where order by id desc");
	       $where.=" and  actid={$act['id']}";  
	       $sqlcount="select count(*)  from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.actid={$act['id']}";
	           $sql="select a.* from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.actid={$act['id']}";
	          
	       }else{
	           $sqlcount="select count(*)  from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.bizid={$act['bizid']}";
	           $sql="select a.* from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.bizid={$act['bizid']}";
	       }
	       $pagesize=50;
	       $page=$_GPC['page']>0?$_GPC['page']:1;
	       $start = ($page - 1) * $pagesize;
	       $total=pdo_fetchcolumn($sqlcount);
	       $pager = pagination($total, $page, $pagesize);
	       $fans=pdo_fetchall($sql);
	       foreach ($fans as $key=>&$row){
	           $row['totallottery']=pdo_fetchcolumn("select count(id)  from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and oauth_openid='{$row['oauth_openid']}' and isgrant=1 $where");
	           $row['totalshare']=pdo_fetchcolumn("select total  from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and oauth_openid='{$row['oauth_openid']}' $where");
	           $member=mc_fetch($row['uid'],array('groupid'));
	           $row['groupid']=$member['groupid'];
	       }
	       unset($row);
	       include $this->template('fan/fanmanage');
	    }else{
            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  order by id desc",array(),'id');
            if ($_GPC['actid']){
                $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");
                $where.=" and  actid={$act['id']}";
                $sqlcount="select count(*)  from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.actid={$act['id']}";
	           $sql="select a.* from ".tablename('hxq_newywym_fan')." a inner join ".tablename('hxq_newywym_activity_fan')." b on a.id=b.fanid and a.uniacid=b.uniacid where b.uniacid={$uniacid} and b.actid={$act['id']}";
	          
	       }else{
	           $sqlcount="select count(*)  from ".tablename('hxq_newywym_fan')." where uniacid={$uniacid}";
	           $sql="select * from ".tablename('hxq_newywym_fan')."  where  uniacid={$uniacid}";
	       }
            $pagesize=50;
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start = ($page - 1) * $pagesize;
            $total=pdo_fetchcolumn($sqlcount);
            $pager = pagination($total, $page, $pagesize);
            $fans=pdo_fetchall($sql);
            foreach ($fans as $key=>&$row){
                $row['totallottery']=pdo_fetchcolumn("select count(id)  from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and oauth_openid='{$row['oauth_openid']}' and isgrant=1 $where");
                $row['totalshare']=pdo_fetchcolumn("select total  from ".tablename('hxq_newywym_share')." where uniacid={$uniacid} and oauth_openid='{$row['oauth_openid']}' $where");
                $member=mc_fetch($row['uid'],array('groupid'));
                $row['groupid']=$member['groupid'];
            }
            unset($row);
            include $this->template('fan/fanmanage');
	    }
	    //
}}
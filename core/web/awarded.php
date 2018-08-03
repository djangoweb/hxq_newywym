<?php class awarded extends page{
    public function main($op=''){
	    global $_W,$_GPC,$_M;

	    $op=in_array($op,array('display','export','disable','enable','delete'))?$op:'display';
	    $uniacid=$_W['uniacid'];
	    if ($op=='delete'){
	        $id=$_GPC['id'];
	        if (empty($id)){
	            message('未指定中奖记录id');
	        }
	        $awarded=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid=$uniacid and id=$id");
	        if (empty($awarded)){
	            message('找不到中奖纪录');
	        }
	        pdo_delete('hxq_newywym_token',array('tk'=>$awarded['tk']));
	        message('删除中奖记录成功，二维码可以重新使用！',referer(),'success');
	    }
	    if ($op=='export'){
	        if ($_W['role']=='operator'){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	            $bizids=array_keys($bizs);
	            $strbizids=join(',',$bizids);
	            $where=" and bizid in ($strbizids)";
	            if ($_GPC['actid']){
	                $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']} $where order by id desc");
	                $where.=" and actid={$_GPC['actid']}";
	                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	                $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
	                $cates=array_map('array_shift',$cates);
	                $strcates=join(',',$cates);
	                $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
	            }elseif ($_GPC['bizid']){
	                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} and id in ($strbizids) order by id desc");
	                $where.=" and bizid={$_GPC['bizid']}";
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	            }else{
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} $where order by id desc",array(),'id');
	                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id in ($strbizids) order by id desc",array(),'id');
	            }
	        }else{
    	        if ($_GPC['actid']){
                    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");
                    $where.=" and actid={$_GPC['actid']}";
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                    $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
                    $cates=array_map('array_shift',$cates);
                    $strcates=join(',',$cates);
                    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
                }elseif ($_GPC['bizid']){
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
                    $where.=" and bizid={$_GPC['bizid']}";
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                }else{
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
                    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
                }
	        }
            if ($_GPC['awardid']){
                $where.=" and awardid=".$_GPC['awardid'];
            }
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".($biz['name']?$biz['name']:'').($act['name']?$act['name']:'').".xls");
	        $sql="select * from ".tablename('hxq_newywym_token')." where  uniacid={$uniacid} $where and type!=5 order by id asc";
	        $awardeds=pdo_fetchall($sql);
	        $strexport="微信名\t奖品\t已领取\t中奖时间\t核销时间\r"; 
	        foreach ($awardeds as $row){
	            $strexport.=$row['nickname']?$row['nickname']:'匿名'; 
	            $strexport.="\t".$row['title'];
	            $strexport.="\t".($row['isgrant']?'已领取':'未领取');
	            $strexport.="\t".date('Y-m-d H:i',$row['addtime']);
	            $strexport.="\t".(empty($row['veriftime'])?'未核销':date('Y-m-d H:i',$row['veriftime']));//要改
	            
	            $strexport.="\r";	        }
	        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
	        exit($strexport);
	    }
	    if ($_W['role']=='operator'){
	        $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	        $bizids=array_keys($bizs);
	        $strbizids=join(',',$bizids);
	        $where=" and bizid in ($strbizids)";
	        if ($_GPC['actid']){
	            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']} $where order by id desc");
	            $where.=" and actid={$_GPC['actid']}";
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	            $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
	            $cates=array_map('array_shift',$cates);
	            $strcates=join(',',$cates);
	            $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
	        }elseif ($_GPC['bizid']){
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} and id in ($strbizids) order by id desc");
	            $where.=" and bizid={$_GPC['bizid']}";
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	        }else{
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} $where order by id desc",array(),'id');
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id in ($strbizids) order by id desc",array(),'id');
	        }
	    }else{
    	   $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
           if ($_GPC['actid']){
                $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");
                $where.=" and actid={$_GPC['actid']}";
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
                $cates=array_map('array_shift',$cates);
                $strcates=join(',',$cates);
                $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
           }elseif ($_GPC['bizid']){
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
                $where.=" and bizid={$_GPC['bizid']}";
                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
           }else{
                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
           }
	    }
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1 $where and type!=5");
	    $pagesize=30;
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start = ($page - 1) * $pagesize;
	    $limit .= " LIMIT {$start},{$pagesize}";
	    $pager = pagination($total, $page, $pagesize);
	    $awardeds=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} and isgrant=1  {$where} and type!=5 order by addtime desc {$limit}");
	    unset($row);
	    include $this->template('awarded/awardedmanage');}
    public function verif($op=''){
        global $_W,$_GPC,$_M;
        $uniacid=$_W['uniacid'];
        $op=in_array($op,array('display','export','disable','enable','delete'))?$op:'display';
        if ($op=='export'){
        
	        if ($_W['role']=='operator'){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	            $bizids=array_keys($bizs);
	            $strbizids=join(',',$bizids);
	            $where=" bizid in ($strbizids)";
	            if ($_GPC['actid']){
	                $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']} $where order by id desc");
	                $where.=" and actid={$_GPC['actid']}";
	                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	                $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
	                $cates=array_map('array_shift',$cates);
	                $strcates=join(',',$cates);
	                $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
	            }elseif ($_GPC['bizid']){
	                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} and id in ($strbizids) order by id desc");
	                $where.=" and bizid={$_GPC['bizid']}";
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	            }else{
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} $where order by id desc",array(),'id');
	                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id in ($strbizids) order by id desc",array(),'id');
	            }
	        }else{
    	        if ($_GPC['actid']){
                    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");
                    $where.=" and actid={$_GPC['actid']}";
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                    $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
                    $cates=array_map('array_shift',$cates);
                    $strcates=join(',',$cates);
                    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
                }elseif ($_GPC['bizid']){
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
                    $where.=" and bizid={$_GPC['bizid']}";
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                }else{
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
                    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
                }
	        }
            if ($_GPC['awardid']){
                $where.=" and awardid=".$_GPC['awardid'];
            }
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:filename=".($biz['name']?$biz['name']:'').($act['name']?$act['name']:'').".xls");
            $sql="select * from ".tablename('hxq_newywym_token')." where  uniacid={$uniacid} $where and type!=5 order by id asc";
            $awardeds=pdo_fetchall($sql);
            $strexport="微信名\t奖品\t已领取\t中奖时间\t核销时间\r";
            foreach ($awardeds as $row){
                $strexport.=$row['nickname']?$row['nickname']:'匿名';
                $strexport.="\t".$row['title'];
                $strexport.="\t".($row['isgrant']?'已领取':'未领取');
                $strexport.="\t".date('Y-m-d H:i',$row['addtime']);
                $strexport.="\t".(empty($row['veriftime'])?'未核销':date('Y-m-d H:i',$row['veriftime']));//要改
                $strexport.="\r";
            }
                $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
                exit($strexport);
        }
        if ($op=='display'){
            if ($_W['role']=='operator'){
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
                $bizids=array_keys($bizs);
                $strbizids=join(',',$bizids);
                $where=" and bizid in ($strbizids)";
                if ($_GPC['actid']){
                    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']} $where order by id desc");
                    $where.=" and actid={$_GPC['actid']}";
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                    $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
                    $cates=array_map('array_shift',$cates);
                    $strcates=join(',',$cates);
                    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
                }elseif ($_GPC['bizid']){
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']} and id in ($strbizids) order by id desc");
                    $where.=" and bizid={$_GPC['bizid']}";
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                }else{
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} $where order by id desc",array(),'id');
                    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id in ($strbizids) order by id desc",array(),'id');
                }
            }else{
                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid}  order by id desc",array(),'id');
                if ($_GPC['actid']){
                    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and id={$_GPC['actid']}  order by id desc");
                    $where.=" and actid={$_GPC['actid']}";
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$act['bizid']}  order by id desc");
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                    $cates=pdo_fetchall("select id from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
                    $cates=array_map('array_shift',$cates);
                    $strcates=join(',',$cates);
                    $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ($strcates) order by id desc",array(),'id');
                }elseif ($_GPC['bizid']){
                    $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['bizid']}  order by id desc");
                    $where.=" and bizid={$_GPC['bizid']}";
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
                }else{
                    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
                    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id desc",array(),'id');
                }
            }
            if ($_GPC['awardid']){
                $where.=" and awardid=".$_GPC['awardid'];
            }
            $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where veriftime<>0  and type=4 and uniacid={$uniacid}  $where order by id desc");
            $pagesize=30;
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start = ($page - 1) * $pagesize;
            $limit .= " LIMIT {$start},{$pagesize}";
            $pager = pagination($total, $page, $pagesize);
            $verifs=pdo_fetchall("select * from ".tablename('hxq_newywym_token')." where veriftime<>0 and uniacid={$uniacid}  and type=4 $where order by id desc $limit",array(),'id');
            include $this->template('awarded/verif');
        }
    }   
}
	
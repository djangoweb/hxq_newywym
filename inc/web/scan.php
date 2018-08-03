<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $actid=$_GPC['actid'];
	    $op=in_array($_GPC['op'],array('display','export','grantaward','delete','verif'))?$_GPC['op']:'display';
	    $actid=$_GPC['actid'];
	    $acts=pdo_fetchall("select * from ".tablename('hxq_ywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
	    if (!empty($actid)){
	        $cates=pdo_fetchall("select * from ".tablename('hxq_ywym_cate')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
	        $act=pdo_fetch("select * from ".tablename('hxq_ywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
	        $cateid=$_GPC['cateid'];
	        if (!empty($cateid)){
	          $cate=pdo_fetch("select * from ".tablename('hxq_ywym_cate')." where uniacid={$uniacid} and actid={$actid} and  id=$cateid order by id desc",array(),'id');
            }
            $awards=pdo_fetchall("select * from ".tablename('hxq_ywym_award')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
        }else{
            $cates=pdo_fetchall("select * from ".tablename('hxq_ywym_cate')." where uniacid={$uniacid}  order by id desc",array(),'id');
            $awards=pdo_fetchall("select * from ".tablename('hxq_ywym_award')." where uniacid={$uniacid}  order by id desc",array(),'id');
	    }
	    if ($actid){
	        $where.=" and actid={$actid} ";
	    }
	    if (!empty($cateid)){
	        $where.=" and cateid={$cateid} ";
	    }
	    if ($op=='delete'){
	        $id=$_GPC['id'];
	        if (empty($id)){
	            message('未指定中奖记录id');
	        }
	        pdo_delete('hxq_ywym_token',array('id'=>$id,'disable'=>0));
	        message('删除中奖记录成功，二维码可以重新使用！');
	    }
	    if ($op=='export'){
	        header("Content-type:application/vnd.ms-excel");
	        header("Content-Disposition:filename=".TIMESTAMP.".xls");
	        if ($actid){
	            $condition=" and actid={$actid}";
	        }
	        $awards=pdo_fetchall("select * from ".tablename('hxq_ywym_award')." where  uniacid={$uniacid} $where",array(),'id');
	        $sql="select * from ".tablename('hxq_ywym_token')." where actid={$actid} and uniacid={$uniacid} {$where} order by id asc";
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
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_ywym_scan')." where uniacid={$uniacid}   $where");
	    $pagesize=30;
	    $page=$_GPC['page']>0?$_GPC['page']:1;
	    $start = ($page - 1) * $pagesize;
	    $limit .= " LIMIT {$start},{$pagesize}";
	    $pager = pagination($total, $page, $pagesize);
	    $scans=pdo_fetchall("select * from ".tablename('hxq_ywym_scan')." where uniacid={$uniacid}   {$where} order by addtime desc {$limit}");
	    foreach($scans as $key=>&$row){
	    }
	    unset($row);
	    include $this->template('scan');
	
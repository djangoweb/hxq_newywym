<?php

class cate extends page{
      public function main($op='display'){
	      global $_W,$_GPC,$_M;
	      $uniacid=$_W['uniacid'];
	      $op=in_array($op,array('display','export','getact','getbiz','bindbiz','bindact','checkcate','unbindact','edit','saveadd','disable','setdistrict','undisable','splitcate'))?$op:'display';
	      if ($op=='getact'){
	          $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$_GPC['bizid']} order by id desc");
	          if ($biz['uid']!=$_W['uid']){
	              echo json_encode(array('message'=>'error','errno'=>1));
	              exit();
	          }
	          $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and bizid={$_GPC['bizid']} order by id desc",array(),'id');
	          if (empty($acts)){
	              echo json_encode(array('message'=>'cg','acts'=>$acts,'biz'=>$biz,'errno'=>1));
	              exit();
	          }else{
	              echo json_encode(array('message'=>'cg','acts'=>$acts,'biz'=>$biz,'errno'=>0));
	              exit();
	          }
	      }
	      if ($op=='getbiz'){
	          $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid order by id desc",array(),'id');
	          if (empty($bizs)){
	              echo json_encode(array('message'=>'cg','bizs'=>$bizs,'errno'=>1));
	              exit();
	          }else{
	              echo json_encode(array('message'=>'cg','bizs'=>$bizs,'errno'=>0));
	              exit();
	          }
	      }
	      if ($op=='bindbiz'){
	          if (!$_GPC['bizid']){
	              echo json_encode(array('message'=>'请选择商家','errno'=>1));
	              exit();
	          }
	          pdo_update('hxq_newywym_cate',array('bizid'=>$_GPC['bizid']),array('id'=>$_GPC['id']));
	          echo json_encode(array('errno'=>0,'message'=>'成功'));
	          exit();
	      }
	      if ($op=='unbindbiz'){
	          pdo_update('hxq_newywym_cate',array('bizid'=>0),array('id'=>$_GPC['id']));
	          echo json_encode(array('errno'=>0,'message'=>'成功'));
	          exit();
	      }
	      if ($op=='bindact'){
	          if (!$_GPC['act']){
	              echo json_encode(array('message'=>'请选择活动','errno'=>1));
	              exit();
	          }
	          $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$_GPC['act']} order by id desc");
	           
	          $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
	           
	          if ($biz['uid']!=$_W['uid']){
	              echo json_encode(array('message'=>'error','errno'=>1));
	              exit();
	          }
	          pdo_update('hxq_newywym_cate',array('actid'=>$_GPC['act']),array('id'=>$_GPC['id']));
	          echo json_encode(array('errno'=>0,'message'=>'成功'));
	          exit();
	      }
	      if ($op=='unbindact'){
	          pdo_update('hxq_newywym_cate',array('actid'=>0),array('id'=>$_GPC['id']));
	          echo json_encode(array('errno'=>0,'message'=>'成功'));
	          exit();
	      }
	      if ($op=='checkcate'){
	          $mintk=$_GPC['mintk'];
	          $total=$_GPC['total'];
	          $maxtk=$mintk+$total-1;
	          $t0exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 and (maxtk>=$mintk and mintk<=$mintk)");
	          if (!$t0exists){
	              die(json_encode(error(1,'起始码不存在')));
	          }
	          $t1exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2 and (maxtk>=$mintk and mintk<=$mintk)");
	          if ($t1exists){
	              die(json_encode(error(1,'号段已存在于['.$t1exists['mintk'].'-'.$t1exists['maxtk'].']中')));
	          }
	          if($mintk+$total-1>$t0exists['maxtk']){
	              $pretotal=$t0exists['maxtk']-$mintk+1;
	              $aftertotal=$total-$pretotal;
	              $t0exists_=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1  and mintk>{$t0exists['mintk']}");
	              if (!$t0exists_){
	                  die(json_encode(error(1,'剩余的二维码最多只能再生成'.$pretotal.'个二维码的码段')));
	              }
	              if ($aftertotal>$t0exists_['total']){
	                  die(json_encode(error(1,'剩余的二维码不够您需要 的数量')));
	              }
	          }
	          die(json_encode(error(0,'可以')));
	      }
	      if ($op=='display'){
	          $actid=$_GPC['actid'];
              $pid=$_GPC['pid'];
              $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
    	      $bizids=array_keys($bizs);
    	      $strbizids=join(',',$bizids);
    	      $where=" and bizid in ($strbizids)";
              if ($_GPC['actid']){
                  $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$actid} $where order by id desc");
                  if (!$act){
                      message('权限不足');
                  }
                  $where.=" and actid=".$_GPC['actid'];
                  $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
                  $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and bizid={$biz['id']} order by id desc",array(),'id');
              }elseif($_GPC['bizid']){
                  $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$_GPC['bizid']} order by id desc");
                  if ($biz['uid']!=$_W['uid']){
                      message('权限不足');
                  }
                  $where.=" and bizid=".$biz['id'];
                  $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid $where order by id desc",array(),'id');
                  
              }else{
                  $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid $where order by id desc",array(),'id');
              }
	          $pagesize=20;
	          $page=$_GPC['page']?intval($_GPC['page']):1;
	          $start=($page-1)*$pagesize;
	          $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2  $where order by id desc");
	          $totalprice=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2  $where order by id desc");
	          $pager=pagination($total, $page, $pagesize);
	          $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and  type=2 $where order by id desc limit {$start},{$pagesize}",array(),'id');
	          foreach ($cates as &$cate){
	              $cate['totalused']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where  tk>={$cate['mintk']} and tk<={$cate['maxtk']} and isgrant=1 order by id desc");
	              $cate['award']=pdo_fetchcolumn("select id from ".tablename('hxq_newywym_award')." where cateid={$cate['id']} order by id desc");
	          }
	          unset($cate);/*
	          pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>1));
	          pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>2));
	          pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>3));
	          pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>4)); */
	          include $this->template('cate/cate');
	      }
	       
	}
	  
	public function showqrcode($op=''){
	global $_W,$_GPC,$_M;
	$uniacid=$_W['uniacid'];
	$op=in_array($op,array('display','add','delete','qrcode','download','reset','exportused'))?$op:'display';
	$op_=in_array($_GPC['op_'],array('txt','zip','url','xls'))?$_GPC['op_']:'';
    //var_dump($activitys);
	
    if ($op=='delete'){
        //$token=pdo_fetchcolumn('select tk from '.tablename('hxq_newywym_cate').' where uniacid='.$uniacid.' and actid='.$actid." and id={$_GPC['id']}");
        pdo_delete('hxq_newywym_scan',array('tk'=>$_GPC['id']));
        pdo_delete('hxq_newywym_token',array('tk'=>$_GPC['id']));
        $data=array(
            'uniacid'=>$uniacid,
            'actid'=>$act['id'],
            'pdtid'=>$cate['pdtid'],
            'cateid'=>$cate['id'],
            'tk'=>$_GPC['id'],
            'title' =>'未中奖',
            'enable'=>-1,
            'addip'=>getip(),
            'addtime'=>TIMESTAMP
        );
        pdo_insert('hxq_newywym_token',$data);
        message('删除成功！',referer(),'success');
    }
	if ($op=='qrcode'){
	    require MODULE_ROOT.'/inc/phpqrcode.php';
	    $tk=$_GPC['tk'];
	    $vtk=$_GPC['vtk'];
	    $cateid=$_GPC['cateid'];
	    $url=$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$tk,'vtk'=>$vtk));
	    $qrcode_rdir="images/{$uniacid}/".MODULE_NAME.'/'.$cateid.'/';
	    load()->func('file');
	    mkdirs(ATTACHMENT_ROOT.$qrcode_rdir);
	    QRcode::png($url,ATTACHMENT_ROOT.$qrcode_rdir.$tk.'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);
	    header('location:'.$_W['attachurl_local'].$qrcode_rdir.$tk.'.png');
	    exit;
	}   
	if ($op=='reset'){
	    //$token=pdo_fetchcolumn('select tk from '.tablename('hxq_newywym_cate').' where uniacid='.$uniacid.' and actid='.$actid." and id={$_GPC['id']}");
	    pdo_delete('hxq_newywym_scan',array('tk'=>$_GPC['id']));
	    pdo_delete('hxq_newywym_token',array('tk'=>$_GPC['id']));
	    message('回收成功','referer','success');
	}
	if ($op=='download'){
    if (empty($cate)){
        message('请先添加活动');
    }
	    if ($op_=='url'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where  id={$cateid}");
	        header("Content-type:txt/plain");
	        header("Content-Disposition: filename=".$cateid.'['.$cate['mintk'].'-'.$cate['maxtk'].'].txt');
	        $strtxt='';
    	    for($i=$cate['mintk'];$i<=$cate['maxtk'];$i++){
    	    $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
    	    $strtxt.=$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk))."\r\n";
        	}
        	$strtxt=iconv('UTF-8',"GB2312",$strtxt);
	        exit($strtxt);
	    }
	    if ($op_=='xls'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where  id={$cateid}");
	        header("Content-type:application/vnd.ms-excel;");
	        header("Content-Disposition:filename=".$cateid.'['.$cate['mintk'].'-'.$cate['maxtk'].'].xls');
	        $strtxt="编号\t链接\r";
	        for($i=$cate['mintk'];$i<=$cate['maxtk'];$i++){
	            $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
	            $strtxt.=$i;
	            $strtxt.="\t".$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk));
	            $strtxt.="\r";
	        }
	        $strtxt=iconv('UTF-8',"GB2312",$strtxt);
	        exit($strtxt);
	    }
	    if ($op_=='zip'){
	        require MODULE_ROOT.'/inc/phpqrcode.php';
	        set_time_limit(0);
	        load()->func('file');
	        $token_rdir="images/{$uniacid}/".MODULE_NAME.'/'.$cateid.'/';
	        $token_adir=ATTACHMENT_ROOT."images/{$uniacid}/".MODULE_NAME.'/'.$cateid.'/';
	        mkdirs($token_adir);
	        $total=$cate['maxtk']-$cate['mintk']+1;
	        $pagesize=100;
	        $page=$_GPC['page'];
	        $page=intval($page);
	        $start=($page-1)*$pagesize;
	        $j=0;
        	for($i=$cate['mintk']+$start;($i<$cate['mintk']+$start+$pagesize&&$i<=$cate['maxtk']);$i++){
	            $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
	            $tokenurl=$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk));
	            //echo $tokenurl;
	            $qrfile=QRcode::png($tokenurl,$token_adir.$i.'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);   
	            $j++;
	        }
	        if ($i<$cate['maxtk']+1){
 	           $rst=json_encode(array('errno'=>1,'page'=>$page+1,'total'=>$total,'j'=>$j));
	           exit($rst);
            } 
	        $files=array();
	        $dirs=opendir(ATTACHMENT_ROOT."images/{$uniacid}/".MODULE_NAME.'/'.$cateid.'/');
	        while($dir=readdir($dirs)){
	            if ($dir!='.'&&$dir!='..'){
	                $files[]="images/{$uniacid}/".MODULE_NAME.'/'.$cateid.'/'.$dir;
	            }
	        }
	        $file_adir=dirname($token_adir);
	        $file_rdir=dirname($token_rdir);
	        $filename=$cateid.'['.$cate['mintk'].'-'.$cate['maxtk'].'].zip';
	        $result=zip($files,ATTACHMENT_ROOT,$file_adir,$filename);
	        if ($result){
	            $rst=json_encode(array('errno'=>2,'message'=>'压包成功','total'=>$total,'j'=>$j,'redirect'=>$_W['attachurl'].$file_rdir.'/'.$filename,'i'=>$i));
	        }else{
	            $rst=json_encode(array('errno'=>3,'message'=>'压包失败','total'=>$total));
	        }
	        exit($rst);
	    }
	}elseif ($op=='exportused'){
	    
	    $actid=$_GPC['actid'];
	    $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where  id=$actid");
	    $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where  actid=$actid order by mintk asc");
	    header("Content-type:txt/plain");
	    header("Content-Disposition: filename=".$actid.'_'.$act['name'].'_'.'已使用二维码'.'.txt');
	    $strtxt='';
	    foreach($cates as $row){
	        for($i=$row['mintk'];$i<=$row['maxtk'];$i++){
	            $a=pdo_fetchcolumn("select id from ".tablename('hxq_newywym_token')." where tk=$i");
	            if ($a>0){
	                $strtxt.=$i.' ';
	            }
	        }	    
	    }
	    exit($strtxt);
	}
if(!$_GPC['cateid']){
    message('请指定码段id');
}
    $cateid=$_GPC['cateid'];
    $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where  id=$cateid");
    if ($cate['actid']){
        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where  id={$cate['actid']}");
        $biz=pdo_fetch('select * from '.tablename('hxq_newywym_biz')." where  id={$cate['bizid']}");

        if ($biz['uid']!=$_W['uid']){
        message('权限有误');
        }
    }
	$pagesize=20;
	$page=$_GPC['page']>0?$_GPC['page']:1;
	$total=pdo_fetchcolumn("select total from ".tablename('hxq_newywym_cate')." where  id=$cateid");
	if (($page-1)*$pagesize<$total){
	    $start = ($page - 1) * $pagesize;
	}else{
	    $page=(int)($total/$pagesize);
	    $start=$page*$pagesize;
	}
	for($i=$cate['mintk']+$start;($i<$cate['mintk']+$start+$pagesize&&$i<=$cate['maxtk']);$i++){
	    $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
	    $token=pdo_fetch("select * from ".tablename('hxq_newywym_token')." where  tk=$i");
	    $tokens[]=array('id'=>$i,
	        'url'=>$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk)),
    	    'qrurl'=>WebUrl('cate',array('op'=>'showqrcode.qrcode','cateid'=>$cate['id'],'tk'=>$i,'vtk'=>$vtk)),
	        'province'=>$token['province'],
	        'city'=>$token['city'],
	        'district'=>$token['district'],
	        'usedtime'=>$token['addtime'],
	        'enable'=>$token['enable']
	    );
	}
	$pager = pagination($total, $page, $pagesize);
	include $this->template('cate/tokenmanage');
}
	  
}

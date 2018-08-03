<?php 
    require MODULE_ROOT.'/inc/function.php';
	global $_W,$_GPC,$_M;
	$uniacid=$_W['uniacid'];
	$op=in_array($_GPC['op'],array('display','add','delete','qrcode','download','reset'))?$_GPC['op']:'display';
	$op_=in_array($_GPC['op_'],array('txt','zip','url','xls'))?$_GPC['op_']:'';
    //var_dump($activitys);
	if (!$_W['isfounder']){
	    $where=" and uniacid=$uniacid";
	}
	$cateid=$_GPC['cateid'];
    if (!empty($cateid)){
        $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where id=$cateid order by id desc");
    }
    if (empty($cate)){
        message('请先添加活动');
    }
    if (!$_W['isfounder']){
        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
    }else{
        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." order by id desc",array(),'id');
    }
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
	    $url=$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('tk'=>$tk,'vtk'=>$vtk));
	    $qrcode_rdir="images/{$uniacid}/".$this->modulename.'/'.$cateid.'/';
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
	    if ($op_=='url'){
	        if ($_GPC['type']=='used'){
	            $where=" and usedtime!=''";
	        }elseif($_GPC['type']=='unused'){
	            $where=" and usedtime=''";
	        }
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where $where and id={$cateid}");
	        header("Content-type:txt/plain");
	        header("Content-Disposition: filename=".$cateid.'['.$cate['mintk'].'-'.$cate['maxtk'].'].txt');
	        $strtxt='';
    	    for($i=$cate['mintk'];$i<=$cate['maxtk'];$i++){
    	    $vtk=substr(md5($i.$_M['slat']),0,8);
    	    $strtxt.=$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('tk'=>$i,'vtk'=>$vtk))."\r\n";
        	}
        	$strtxt=iconv('UTF-8',"GB2312",$strtxt);
	        exit($strtxt);
	    }
	    if ($op_=='xls'){
	        if ($_GPC['type']=='used'){
	            $where=" and usedtime!=''";
	        }elseif($_GPC['type']=='unused'){
	            $where=" and usedtime=''";
	        }
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where $where and id={$cateid}");
	        header("Content-type:application/vnd.ms-excel;");
	        header("Content-Disposition:filename=".$cateid.'['.$cate['mintk'].'-'.$cate['maxtk'].'].xls');
	        $strtxt="编号\t链接\r";
	        for($i=$cate['mintk'];$i<=$cate['maxtk'];$i++){
	            $vtk=substr(md5($i.$_M['slat']),0,8);
	            $strtxt.=$i;
	            $strtxt.="\t".$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('tk'=>$i,'vtk'=>$vtk));
	            $strtxt.="\r";
	        }
	        $strtxt=iconv('UTF-8',"GB2312",$strtxt);
	        exit($strtxt);
	    }
	    if ($op_=='zip'){
	        require MODULE_ROOT.'/inc/phpqrcode.php';
	        set_time_limit(0);
	        load()->func('file');
	        $token_rdir="images/{$uniacid}/".$this->modulename.'/'.$cateid.'/';
	        $token_adir=ATTACHMENT_ROOT."images/{$uniacid}/".$this->modulename.'/'.$cateid.'/';
	        mkdirs($token_adir);
	        $total=$cate['maxtk']-$cate['mintk']+1;
	        $pagesize=100;hhhhhhhhhhhhhh
	        $page=$_GPC['page'];
	        $page=intval($page);
	        $start=($page-1)*$pagesize;
	        $j=0;
        	for($i=$cate['mintk']+$start;($i<$cate['mintk']+$start+$pagesize&&$i<=$cate['maxtk']);$i++){
	            $vtk=substr(md5($i.$_M['slat']),0,8);
	            $tokenurl=$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('tk'=>$i,'vtk'=>$vtk,'slat'=>$_M['slat']));
	            $qrfile=QRcode::png($tokenurl,$token_adir.$i.'.png', $level=QR_ECLEVEL_L, $size=3, $margin=4,$saveandprint=false);   
	            $j++;
	        }
	        if ($i<$cate['maxtk']+1){
 	           $rst=json_encode(array('errno'=>1,'page'=>$page+1,'total'=>$total,'j'=>$j));
	           exit($rst);
            } 
	        $files=array();
	        $dirs=opendir(ATTACHMENT_ROOT."images/{$uniacid}/".$this->modulename.'/'.$cateid.'/');
	        while($dir=readdir($dirs)){
	            if ($dir!='.'&&$dir!='..'){
	                $files[]="images/{$uniacid}/".$this->modulename.'/'.$cateid.'/'.$dir;
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
	}
	$pagesize=20;
	$page=$_GPC['page']>0?$_GPC['page']:1;
	$total=pdo_fetchcolumn("select total from ".tablename('hxq_newywym_cate')." where 1=1 $where and id=$cateid");
	if (($page-1)*$pagesize<$total){
	    $start = ($page - 1) * $pagesize;
	}else{
	    $page=(int)($total/$pagesize);
	    $start=$page*$pagesize;
	}
	for($i=$cate['mintk']+$start;($i<$cate['mintk']+$start+$pagesize&&$i<=$cate['maxtk']);$i++){
	    $vtk=substr(md5($i.$_M['slat']),0,8);
	    $token=pdo_fetch("select * from ".tablename('hxq_newywym_token')." where 1=1 $where and tk=$i");
	    $tokens[]=array('id'=>$i,
	        'url'=>$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('cateid'=>$cate['id'],'tk'=>$i,'vtk'=>$vtk)),
    	    'qrurl'=>$this->createWebUrl('tokenmanage',array('op'=>'qrcode','cateid'=>$cate['id'],'tk'=>$i,'vtk'=>$vtk)),
	        'province'=>$token['province'],
	        'city'=>$token['city'],
	        'district'=>$token['district'],
	        'usedtime'=>$token['addtime'],
	        'enable'=>$token['enable']
	    );
	}
	$pager = pagination($total, $page, $pagesize);
	include $this->template('tokenmanage');

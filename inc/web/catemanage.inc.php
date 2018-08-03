<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $op=in_array($_GPC['op'],array('display','qd','export','getuni','binduni','unbinduni','bindcate','checkcate','unbindcate','edit','saveadd','disable','abstractcate','setdistrict','undisable','getact','splitcate','delete','fission'))?$_GPC['op']:'display';
	    if ($op=='delete'){
	        pdo_delete('hxq_newywym_cate',array('id'=>$_GPC['cateid']));
	        message('删除成功',referer(),'success');
	    }
	    if ($op=='export'){
	        if (checksubmit('excel')){
	            if (empty($_M['slat'])){
	                message('请先在模块参数设置里设置加密盐再继续进行操作！');
	            }
	            if (!is_numeric($_GPC['mintk'])||$_GPC['mintk']<1){
	                message('起始码请输入大于0数字！');
	            }
	            if (!is_numeric($_GPC['total'])||$_GPC['total']<1){
	                message('二维码总数请输入大于0数字！');
	            }
	            $mintk=$_GPC['mintk'];
	            $total=$_GPC['total'];
                header("Content-type:application/vnd.ms-excel;");
                header("Content-Disposition:filename=[".$_GPC['mintk'].'-'.($_GPC['mintk']+$total-1).']'.$_GPC['total'].'.xls');
                $strtxt="编号\t链接\r";
                for($i=$mintk;$i<$mintk+$total;$i++){
                    $vtk=substr(md5($i.$_M['slat']),0,8);
                    $strtxt.=$i;
                    $strtxt.="\t".$_W['siteroot'].'addons/'.$this->modulename.'/scan.php?'.$i.'-'.$vtk;
                    $strtxt.="\r";
                }
                $strtxt=iconv('UTF-8',"GB2312",$strtxt);
                exit($strtxt);
	        }
	        if (checksubmit('excelshort')){
	            if (empty($_M['slat'])){
	                message('请先在模块参数设置里设置加密盐再继续进行操作！');
	            }
	            if (!is_numeric($_GPC['mintk'])||$_GPC['mintk']<1){
	                message('起始码请输入大于0数字！');
	            }
	            if (!is_numeric($_GPC['total'])||$_GPC['total']<1){
	                message('二维码总数请输入大于0数字！');
	            }
	            $mintk=$_GPC['mintk'];
	            $total=$_GPC['total'];
	            header("Content-type:application/vnd.ms-excel;");
	            header("Content-Disposition:filename=[".$_GPC['mintk'].'-'.($_GPC['mintk']+$total-1).']'.$_GPC['total'].'.xls');
	            $strtxt="编号\t链接\r";
	            for($i=$mintk;$i<$mintk+$total;$i++){
	                $vtk=substr(md5($i.$_M['slat']),0,8);
	                $strtxt.=$_W['siteroot'].'scan.php?'.$i.'-'.$vtk;
	                $strtxt.="\r";
	            }
	            $strtxt=iconv('UTF-8',"GB2312",$strtxt);
	            exit($strtxt);
	        }

	        if (checksubmit('txtshort')){
	            if (empty($_M['slat'])){
	                message('请先在模块参数设置里设置加密盐再继续进行操作！');
	            }
	            if (!is_numeric($_GPC['mintk'])||$_GPC['mintk']<1){
	                message('起始码请输入大于0数字！');
	            }
	            if (!is_numeric($_GPC['total'])||$_GPC['total']<1){
	                message('二维码总数请输入大于0数字！');
	            }
	            $mintk=$_GPC['mintk'];
	            $total=$_GPC['total'];
	            header("Content-type:application/txt;");
	            header("Content-Disposition:filename=[".$_GPC['mintk'].'-'.($_GPC['mintk']+$total-1).']'.$_GPC['total'].'.txt');
	            for($i=$mintk;$i<$mintk+$total;$i++){
	                $vtk=substr(md5($i.$_M['slat']),0,8);
	                $strtxt.=$_W['siteroot'].'scan.php?'.$i.'-'.$vtk;
	                $strtxt.="\r\n";
	            }
	            $strtxt=iconv('UTF-8',"GB2312",$strtxt);
	            exit($strtxt);
	        }
            if (checksubmit('txt')){
                if (empty($_M['slat'])){
                    message('请先在模块参数设置里设置加密盐再继续进行操作！');
                }
                if (!is_numeric($_GPC['mintk'])||$_GPC['mintk']<1){
                    message('起始码请输入大于0数字！');
                }
                if (!is_numeric($_GPC['total'])||$_GPC['total']<1){
                    message('二维码总数请输入大于0数字！');
                }
                $mintk=$_GPC['mintk'];
                $total=$_GPC['total'];
                header("Content-type:application/txt;");
                header("Content-Disposition:filename=[".$_GPC['mintk'].'-'.($_GPC['mintk']+$total-1).']'.$_GPC['total'].'.txt');
                for($i=$mintk;$i<$mintk+$total;$i++){
                    $vtk=substr(md5($i.$_M['slat']),0,8);
                    $strtxt.=$i;
                    $strtxt.="\t".$_W['siteroot'].'addons/'.$this->modulename.'/scan.php?'.$i.'-'.$vtk;
                    $strtxt.="\r\n";
                }
                $strtxt=iconv('UTF-8',"GB2312",$strtxt);
                exit($strtxt);
            }
	        include $this->template('exportqrcode');
	    }
	    if ($op=='getact'){
	        $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$_GPC['uniacid_']} order by id desc",array(),'id');
	        $uniaccounts=pdo_fetch('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid where a.uniacid={$_GPC['uniacid_']} and a.isdeleted=0 order by a.uniacid desc");
	            if (empty($acts)){
	            echo json_encode(array('message'=>'cg','data'=>'','uniac'=>$uniaccounts,'errno'=>1));
	            exit();
	        }else{
	            echo json_encode(array('message'=>'cg','data'=>$acts,'uniac'=>$uniaccounts,'errno'=>0));
	            exit();
	        }
	    }
	    if ($op=='getuni'){
	        $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid where  a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
	            
	        if (empty($uniaccounts)){
	            echo json_encode(array('message'=>'cg','uniac'=>$uniaccounts,'errno'=>1));
	            exit();
	        }else{
	            echo json_encode(array('message'=>'cg','uniac'=>$uniaccounts,'errno'=>0));
	            exit();
	        }
	    }
	    if ($op=='binduni'){
	        if (!$_GPC['uniacid_']){
	            echo json_encode(array('message'=>'请选择公众号','errno'=>1));
	            exit();
	        }
	        pdo_update('hxq_newywym_cate',array('uniacid'=>$_GPC['uniacid_']),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'成功'));
	        exit();
	    }
	    if ($op=='unbinduni'){
	        pdo_update('hxq_newywym_cate',array('uniacid'=>0),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'成功'));
	        exit();
	    }
	    if ($op=='bindcate'){
	        if (!$_GPC['act']){
	            echo json_encode(array('message'=>'请选择活动','errno'=>1));
	            exit();
	        }
	        pdo_update('hxq_newywym_cate',array('actid'=>$_GPC['act']),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'成功'));
	        exit();
	    }
	    if ($op=='unbindcate'){
	        pdo_update('hxq_newywym_cate',array('actid'=>0),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'成功'));
	        exit();
	    }
	    if ($op=='splitcate'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where id={$_GPC['id']}");
	        if ($cate['maxtk']<=$_GPC['maxtk']){
	            echo json_encode(array('errno'=>1,'message'=>'结束号码不对'));
	            exit;
	        }
	        $total=$_GPC['maxtk']-$cate['mintk']+1;
	        pdo_update('hxq_newywym_cate',array('maxtk'=>$_GPC['maxtk'],'total'=>$total),array('id'=>$_GPC['id']));
	        $data=$cate;
	        $newmintk=$_GPC['maxtk']+1;
	        $newmaxtk=$cate['maxtk'];
	        $data['addtime']=TIMESTAMP;
	        $data['mintk']=$newmintk;
	        $data['maxtk']=$newmaxtk;
	        $data['total']=$newmaxtk-$newmintk+1;
	        $data['actid']=0;
	        unset($data['id']);
	        //var_dump($data);
	        pdo_insert('hxq_newywym_cate',$data);
	        echo json_encode(array('errno'=>0,'message'=>'成功'));
	        exit();
	    }
	    if ($op=='abstractcate'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where id={$_GPC['id']}");
	        if (!$cate){
	            echo json_encode(array('errno'=>1,'message'=>'未指定码段ID'));
	            exit;
	        }
	        if (empty($_GPC['abstract'])){
	            echo json_encode(array('errno'=>1,'message'=>'请填写销售区域'));
	            exit;
	        }
	        pdo_update('hxq_newywym_cate',array('abstract'=>$_GPC['abstract']),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'更新备注成功'));
	        exit;
	    }
	    if ($op=='setdistrict'){
	        $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where id={$_GPC['id']}");
	        if (!$cate){
	            echo json_encode(array('errno'=>1,'message'=>'未指定码段ID'));
	            exit;
	        }
	        if (empty($_GPC['district'])){
	            echo json_encode(array('errno'=>1,'message'=>'请填写销售区域'));
	            exit;
	        }
	        pdo_update('hxq_newywym_cate',array('district'=>$_GPC['district']),array('id'=>$_GPC['id']));
	        echo json_encode(array('errno'=>0,'message'=>'更新销售区域成功'));
	        exit;
	    }
	    if ($op=='disable'){
	        pdo_update('hxq_newywym_cate',array('status'=>0),array('id'=>$_GPC['id']));
	        message('禁用成功！',referer(),'success');
	    }
	    if ($op=='undisable'){
	        pdo_update('hxq_newywym_cate',array('status'=>1),array('id'=>$_GPC['id']));
	        message('启用成功！',referer(),'success');
	    }
	    if ($op=='checkcate'){
	        $mintk=$_GPC['mintk'];
	        $total=$_GPC['total'];
	        $maxtk=$mintk+$total-1;
	        $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where (maxtk>=$maxtk and mintk<=$mintk) or (maxtk>=$maxtk and $maxtk>=mintk and mintk>$mintk) or  (maxtk>=$mintk and $mintk>=mintk and $maxtk>maxtk) or (maxtk<$maxtk and mintk>$mintk)");
	        if ($exists){
	            die(json_encode(error(1,'号段已存在于['.$exists['mintk'].'-'.$exists['maxtk'].']中')));
	        }else{
	            die(json_encode(error(0,'可以')));
	        }
	    }
	    if ($op=='edit'){
	        if (checksubmit('submit')){
	           if (empty($_M['slat'])){
	               message('请先在模块参数设置里设置加密盐再继续进行操作！');
	           }
	        $mintk=$_GPC['mintk'];
	        $total=$_GPC['total'];
	        if (!$mintk){
	            message('请填写起始码段');
	        }
	        if (!$total){
	           message('请填写二维码数量');
	        }
	        $maxtk=$mintk+$total-1;
	        $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where (maxtk>=$maxtk and mintk<=$mintk) or (maxtk>=$maxtk and $maxtk>=mintk and mintk>$mintk) or  (maxtk>=$mintk and $mintk>=mintk and $maxtk>maxtk) or (maxtk<$maxtk and mintk>$mintk)");
	        if ($exists){
	            message('号段已存在于['.$exists['mintk'].'-'.$exists['maxtk'].']中');
	        }
	        $record=array(
	            'uniacid'=>$_GPC['uniaccounts'],
	            'mintk'=>$mintk,
	            'total'=>$total,
	            'maxtk'=>$maxtk,
	            'addtime'=>TIMESTAMP,
	        );
	        pdo_insert('hxq_newywym_cate',$record);
	        message('更新成功',$this->createweburl('catemanage'),'success');
	         }
	       $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid where  a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
	       $mintk=pdo_fetchcolumn('select maxtk from '.tablename('hxq_newywym_cate')."  order by maxtk desc");
	       $mintk=intval($mintk)+1;
	       include $this->template('addcate');
	       exit;
	    }
	    if ($op=='fission'){
	        if (checksubmit('submit')){
	            $cate=$_GPC['cate'];
	            if ($cate['fission']){
	                if (!$cate['fissionnum']){
	                    message('请填写二次转发人数');
	                }
	                if (!$cate['area']){
	                    message('请填写lbs定位的地区');
	                }
	            }
	            pdo_update('hxq_newywym_cate',$cate,array('id'=>$_GPC['cateid']));
	            message('更新成功',$this->createweburl('catemanage'),'success');
	        }
	        $cate=pdo_fetch('select * from  '.tablename('hxq_newywym_cate')." where  id={$_GPC['cateid']}");
	        include $this->template('catefission');
	        exit;
	    }
	    if ($op=='qd'){
	        if (checksubmit('submit')){
	            $qdid=$_GPC['qdid'];
	            $area=$_GPC['area'];
	            $abstract=$_GPC['abstract'];
	            $data=array(
	                'qdid'=>$qdid,
	                'area'=>$area,
	                'abstract'=>$abstract
	            );
	            var_dump($data);
	            pdo_update('hxq_newywym_cate',$data,array('id'=>$_GPC['cateid']));
	            
	            message('修改成功',referer(),'success');
	        }
	       $cateid=$_GPC['cateid'];
	       $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where id={$_GPC['cateid']} order by id desc");
	       $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where id={$cate['actid']} order by id desc");
	       include $this->template('catesetting');
	    }
	    if ($op=='display'){
	        if (!$_W['isfounder']){
	            $where=" and uniacid=$uniacid";
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where 1=1 $where order by id desc",array(),'id');
	            $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid where a.uniacid={$_W['uniacid']} and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
	            
	        }else{
	            $acts_=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where 1=1 order by id desc",array(),'id');
	            $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
	           //var_dump($uniaccounts);
	           if ($_GPC['uniacid_']){
	                $where=" and uniacid=".$_GPC['uniacid_'];
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where 1=1 $where order by id desc",array(),'id');
	            }
	        }
	        $actid=$_GPC['actid'];
	        if (!empty($actid)){
	            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where 1=1 $where  and id={$actid} order by id desc");
	        }
	        if ($act){
	            $where.=" and actid={$act['id']} ";
	        }
	        $pagesize=20;
	        $page=$_GPC['page']?intval($_GPC['page']):1;
	        $start=($page-1)*$pagesize;
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where 1=1 $where order by id desc");

	        $pager=pagination($total, $page, $pagesize);
	        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where  1=1 $where order by mintk desc limit {$start},{$pagesize}",array(),'id');
	    foreach ($cates as &$cate){
	        $cate['totalused']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where  tk>={$cate['mintk']} and tk<={$cate['maxtk']} order by id desc");
	    }
    unset($cate);/* 
    pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>1));
    pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>2));
    pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>3));
    pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>4)); */
	    include $this->template('catemanage');
	    }
	    if ($op=='checkcf'){
	        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')."",array(),'id');
	       foreach($cates as $key=>$row){
	           
	       }
	    }
	
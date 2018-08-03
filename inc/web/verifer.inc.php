<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $op=in_array($_GPC['op'],array('display','edit','unbind','displayverif'))?$_GPC['op']:'displayverif';
    $actid=$_GPC['actid'];
    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
    if (!empty($actid)){
        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
        $cateid=$_GPC['cateid'];
        if (!empty($cateid)){
          $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and id=$cateid order by id desc",array(),'id');
        }
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} order by id desc",array(),'id');
    }else{
        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid}  order by id desc",array(),'id');
        $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid}  order by id desc",array(),'id');
    }
    $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid",array(),'id');
        
    if ($actid){
        $where.=" and actid={$actid} ";
    }
    if (!empty($cate)){
        $where.=" and cateid={$cate['id']} ";
    }
	    if ($op=='edit'){
	        if (checksubmit('submit')){
	            $name=$_GPC['name'];
	            $mobile=$_GPC['mobile'];
	            $disable=$_GPC['disable'];
	            $password=$_GPC['password'];
	            if (!$_GPC['name']){
	                message('请填写名称');
	            }
	            if (!$_GPC['password']){
	                message('请填写密码');
	            }
	            if (!preg_match("/^\d{11}$/",$_GPC['mobile'])){
	                message('请输入正确的手机号码');
	            }
	            $record=array(
	                'uniacid'=>$_W['uniacid'],
	                'name'=>$name,
	                'mobile'=>$mobile,
	                'disable'=>$disable,
	                'password'=>$password,
	            );
	            if (empty($_GPC['id'])){
	                if (pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and mobile='$mobile'")){
	                    message('该电话号码已经存在');
	                }
	                $record['actid']=$actid;
	                $record['addtime']=TIMESTAMP;
	                pdo_insert('hxq_newywym_verifer',$record);
	            }else{
	                if (pdo_fetch('select * from '.tablename('hxq_newywym_verifer')." where uniacid=$uniacid and  mobile='$mobile' and id!={$_GPC['id']}")){
	                    message('该电话号码已经存在');
	                }
	                pdo_update('hxq_newywym_verifer',$record,array('id'=>$_GPC['id']));
	            }
	            message('更新成功',$this->createweburl('verifer'),'success');
	        }
	        $id=$_GPC['id'];
	        if(!empty($id)){
	            $verifer=pdo_fetch("select * from ".tablename('hxq_newywym_verifer')." where id=$id");
	        }
	        include $this->template('veriferedit');
	    }
	    if ($op=='display'){
	        $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} $where order by id desc");
	        $pagesize=30;
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start = ($page - 1) * $pagesize;
	        $limit .= " LIMIT {$start},{$pagesize}";
	        $pager = pagination($total, $page, $pagesize);
	        $verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} $where order by id desc $limit",array(),'id');
	        foreach($verifers as $key=>&$row){
	            $row['totalverif']=$row['totalverifmoney']=0;
	            $row['totalverif']=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} $where and veriferid={$row['id']} and type=1 order by id desc");
	            $row['totalverifmoney']=pdo_fetchcolumn("select sum(swprice) from ".tablename('hxq_newywym_token')." where uniacid={$uniacid} $where and veriferid={$row['id']} and come=1 order by id desc");
	        }
	        unset($row);
	        include $this->template('verifer');
	    }
	    if ($op=='displayverif'){	        
	        if ($_GPC['actid']){
	            $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$act['id']} order by id desc");
	            $cates_=array_map('array_shift',$cates);
	            $cates_=array_values($cates_);
	            $str_cates=join(',',$cates_);
	            $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and cateid in ({$str_cates})  order by id desc",array(),'id');
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
	        include $this->template('verif');
	    }
	
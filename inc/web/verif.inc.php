<?php 
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $op=in_array($_GPC['op'],array('display','add','edit','displayverif'))?$_GPC['op']:'display';
        $actid=$_GPC['actid'];
	    $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid} order by id desc",array(),'id');
	    if (!empty($actid)){
	        $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
	        $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniacid}  and id={$actid} order by id desc");
	        $cateid=$_GPC['cateid'];
	        if (!empty($cateid)){
	          $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid={$uniacid} and actid={$actid} and  id=$cateid order by id desc",array(),'id');
            }
            $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid} and actid={$actid} order by id desc",array(),'id');
        }else{
            $awards=pdo_fetchall("select * from ".tablename('hxq_newywym_award')." where uniacid={$uniacid}  order by id desc",array(),'id');
	    }
	    if ($actid){
	        $where.=" and actid={$actid} ";
	    }
	    if (!empty($cateid)){
	        $where.=" and cateid={$cateid} ";
	    }
    	if ($op=='edit'){
	        $id=$_GPC['id'];
	        if(!empty($id)){
	            $cate=pdo_fetch("select * from ".tablename('hxq_newywym_verifer')." where id=$id");
	        }
	        include $this->template('veriferedit');
	        exit;
	    }
	    if ($op=='saveadd'){
	        $actid=$_GPC['actid'];
	        $name=$_GPC['name'];
	        $mobile=$_GPC['mobile'];
	        $disable=$_GPC['disable'];
	        if (!$_GPC['actid']){
	            message('请选择活动');
	        }
	        if (!$_GPC['name']){
	           message('请填写名称');
	        }
	        if (!$_GPC['mobile']){
	            message('手机号码');
	        }
	        $record=array(
	            'uniacid'=>$_W['uniacid'],
	            'actid'=>$actid,
	            'name'=>$name,
	            'mobile'=>$mobile,
	            'disable'=>$disable,
	        );
	        if (empty($_GPC['id'])){
	            $record['addtime']=TIMESTAMP;
	            pdo_insert('hxq_newywym_verifer',$record);
	        }else{
	            pdo_update('hxq_newywym_verifer',$record,array('id'=>$_GPC['id']));
	        }
	        message('更新成功',$this->createweburl('catemanage',array('actid'=>$actid)),'success');
	    }
	    $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and actid=$actid and type!=5 order by id desc");
	    $verifer=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} and actid=$actid and type!=5 order by id desc",array(),'id');
	    	    include $this->template('verifer');
	
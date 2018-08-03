<?php
class founder extends page{
   public function main($op='display'){
       global $_W,$_GPC,$_M;
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('display','edit','edituser'))?$op:'display';
       if ($op=='edituser'){
	       $usergroups=pdo_fetchall('select * from '.tablename('users_group')." where name='营销码代理组'");
           if (checksubmit('submit')){
               if (!$_GPC['uniacid__']){
                   message('未指定代理公众号uniacid');
               }
                $user=$_GPC['user'];
	            if (empty($user['username'])){
	                message('请输入用户名');
	            }
	            if (empty($user['groupid'])){
	                message('请输入用户组');
	            }
	            if (empty($user['password'])){
	                message('请输入密码');
	            }
	            if (empty($user['repassword'])){
	                message('请输入确认密码');
	            }
	            if (strlen($user['password'])<6||strlen($user['repassword'])<6){
	                message('密码必须大于6位');
	            }
	            if ($user['password']!=$user['repassword']){
	                message('两次输入的密码不一致');
	            }
	            if (empty($user['uid'])){
	                $exists=pdo_fetchcolumn('select uid from '.tablename('users')." where username='{$user['username']}'");
	                if ($exists){
	                    message('用户已存在');
	                }
	                $salt=random(8,false);
    	            $password=sha1("{$user['password']}-{$salt}-{$_W['config']['setting']['authkey']}");
    	            $data=array(
    	                'groupid'=>$user['groupid'],
    	                'username'=>$user['username'],
    	                'password'=>$password,
    	                'salt'=>$salt,
    	                'type'=>'1',
    	                'status'=>'2',
    	                'joindate'=>TIMESTAMP,
    	                'joinip'=>getip(),
    	                'starttime'=>TIMESTAMP
    	            );
    	            pdo_insert('users',$data);
    	            $uid=pdo_fetchcolumn('select uid from '.tablename('users')." order by uid desc");
    	            $unidata=array(
    	                'uniacid'=>$_GPC['uniacid__'],
    	                'uid'=>$uid,
    	                'role'=>'owner',
    	                'rank'=>0,
    	            );
    	            pdo_insert('uni_account_users',$unidata);
    	            $unipermission=array(
    	                'uniacid'=>$_GPC['uniacid__'],
    	                'uid'=>$uid,
    	                'type'=>MODULE_NAME,
    	                'permission'=>MODULE_NAME.'_cover_index'.'|'.MODULE_NAME.'_menu_agenter'.'|'.MODULE_NAME.'_menu_coupon'.'|'.MODULE_NAME.'_menu_biz|'.MODULE_NAME.'_menu_census|'.MODULE_NAME.'_menu_awarded|'.MODULE_NAME.'_menu_fan|'.MODULE_NAME.'_menu_cate|'.MODULE_NAME.'_menu_award|'.MODULE_NAME.'_menu_activity',
    	            );
    	            pdo_insert('users_permission',$unipermission);
	            }else{
	                $salt=pdo_fetchcolumn('select salt from '.tablename('users')." where uid ={$user['uid']}");
	                $password=sha1("{$user['password']}-{$salt}-{$_W['config']['setting']['authkey']}");
	                $data=array(
	                    'groupid'=>$user['groupid'],
	                    'password'=>$password,
	                );
	                pdo_update('users',$data,array('uid'=>$user['uid']));
	            }
               message('更新成功',weburl('founder',array('op'=>'main')),'success');
           }
           if (!$_GPC['uniacid__']){
               message('未指定代理公众号uniacid');
           }
           if ($_GPC['uid']){
               $user=pdo_fetch('select * from '.tablename('users')." where uid={$_GPC['uid']}");
           }
           include $this->template('main/addagentuser');
       }
         if ($op=='edit'){
	        if (checksubmit('submit')){
	            $agent=$_GPC['agent'];
	            if (empty($agent['uniacid__'])){
	                message('请选择代理公众号');
	            }
	            if (empty($agent['username'])){
	                message('请输入用户名');
	            }
	            if (empty($agent['phone'])){
	                message('请输入电话');
	            }
	            if (empty($agent['address'])){
	                message('请输入地址');
	            }
	            if (empty($agent['id'])){
    	            $agentdata=array(
    	                'uniacid'=>$agent['uniacid__'],
    	                'recharge'=>0,
    	                'phone'=>$agent['phone'],
    	                'address'=>$agent['address'],
    	                'addtime'=>TIMESTAMP,
    	                'uname'=>$agent['username'],
    	            );
    	            pdo_insert('hxq_newywym_agent',$agentdata);
	            }else{
	                $data_=array(
	                    'uname'=>$agent['username'],
	                    'phone'=>$agent['phone'],
	                    'address'=>$agent['address'],
	                );
	                pdo_update('hxq_newywym_agent',$data_,array('id'=>$agent['id']));
	            }
	            message('更新成功',weburl('founder',array('op'=>'main')),'success');
    	        }
    	        $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid  and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	        
    	        $id=$_GPC['id'];
    	        if ($id){
    	            $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where id={$id}");
    	        }
    	        if (!$id){
    	            foreach($uniaccounts as $key=>$row){
    	                $exists=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid={$row['uniacid']}");
    	                if ($exists){
    	                    unset($uniaccounts[$key]);
    	                }
    	            }
    	        }
    	        include $this->template('main/addagent');
            }elseif($op=='display'){
                $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	         
               $page=$_GPC['page']>0?$_GPC['page']:1;
               $start=($page-1)*30;
               $pagesize=30;
               $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_agent')." order by id desc");
               $agents=pdo_fetchall("select * from ".tablename('hxq_newywym_agent')." order by id desc limit $start,$pagesize");
               foreach($agents as &$row){
                   $agrecharge=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid={$row['uniacid']} and (type=1 or type=5) and status=1");
                   $agrecharge=number_format($agrecharge,2);
                   $row['agrecharge']=$agrecharge;
                   $bizrecharge=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid={$row['uniacid']} and type=3 and status=1");
                   $bizrecharge=number_format($bizrecharge,2);
                   $row['bizrecharge']=$bizrecharge;
                   $agbalance=$agrecharge-$bizrecharge;
                   $row['agbalance']=$agbalance;
                   $qrexpend=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid={$row['uniacid']} and type=1");
                   $qrexpend=number_format($qrexpend,2);
                   $row['qrexpend']=$qrexpend;
                   $qrincome=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid={$row['uniacid']} and type=2");
                   $qrincome=number_format($qrincome,2);
                   $row['qrincome']=$qrincome;
                   $row['qrbalance']=$qrincome-$qrexpend;
                   $qr=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where uniacid={$row['uniacid']} and type=1");
                   $qr=intval($qr);
                   $qruse=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where uniacid={$row['uniacid']} and type=2");
                   $qruse=intval($qruse);
                   $row['qr']=$qr;
                   $row['qruse']=$qruse;
                   $row['totalqrbalance']=$qr-$qruse;
                   $uid=pdo_fetchcolumn('select uid from '.tablename('uni_account_users')." where uniacid={$row['uniacid']} and role='owner'");
                   $row['uid']=$uid;
               }
               unset($row);
               $pager = pagination($total, $page,30);
               include $this->template('main/agent');
       }
   }

   public function cate($op='display'){
       global $_W,$_GPC,$_M;
       if ($_W['role']!='founder'){
           message('没有权限');
       }
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('display','export','getuni','binduni','bindcate','checkcate','unbindcate','edit','saveadd','disable','setdistrict','undisable','getact','splitcate'))?$op:'display';
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
           $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
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
           $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	   $mintk=$_GPC['mintk'];
           $total=$_GPC['total'];
           $maxtk=$mintk+$total-1;
           $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where type=1 and (maxtk>=$mintk and mintk<=$mintk) or (maxtk>=$maxtk and mintk<=$maxtk) or (maxtk<$maxtk and mintk>$mintk) order by maxtk desc");
           if ($exists){
               die(json_encode(error(1,'号段已存在于'.$uniaccounts[$exists['uniacid']]['name'].'['.$exists['mintk'].'-'.$exists['maxtk'].']中')));
           }
       }
       if ($op=='edit'){
           if (checksubmit('submit')){
               $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	       $mintk=$_GPC['mintk'];
               $total=$_GPC['total'];
               $price=$_GPC['price'];
               if (!$mintk){
                   message('请填写起始码号');
               }
               if (!is_numeric($total)||$total<0){
                   message('请填写二维码数量');
               }
               if (!empty($price)){
                   if(!is_numeric($price)||$price<=0){
                   message('总价请填写数字');
                   }
               }else{
                   $price=$this->globalcfg['qrcode']['founder']*$total;
               }
               $maxtk=$mintk+$total-1;
               $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where type=1 and ((maxtk>=$mintk and mintk<=$mintk) or (maxtk>=$maxtk and mintk<=$maxtk) or (maxtk<$maxtk and mintk>$mintk))");
               if ($exists){
                   message('号段已存在于'.$uniaccounts[$exists['uniacid']]['name'].'['.$exists['mintk'].'-'.$exists['maxtk'].']中');
               }
               $record=array(
                   'type'=>1,
                   'uniacid'=>$_GPC['uniaccounts'],
                   'mintk'=>$mintk,
                   'total'=>$total,
                   'maxtk'=>$maxtk,
                   'price'=>$price,
                   'addtime'=>TIMESTAMP,
               );
               pdo_insert('hxq_newywym_cate',$record);
               message('更新成功',weburl('founder'),'success');
           }
           $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	   $mintk=pdo_fetchcolumn('select maxtk from '.tablename('hxq_newywym_cate')." where type=1  order by maxtk desc");
           $mintk=intval($mintk)+1;
           $price=$this->globalcfg['qrcode']['founder'];
           include $this->template('main/addcate');
           exit;
       }
       if ($op=='display'){
            $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	            //var_dump($uniaccounts);
           if ($_GPC['uniacid_']){
               $where=" and uniacid=".$_GPC['uniacid_'];
           }
           $pagesize=20;
           $page=$_GPC['page']?intval($_GPC['page']):1;
           $start=($page-1)*$pagesize;
           $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where type=1 $where order by id desc");
           $totalprice=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where type=1 $where order by id desc");
           $pager=pagination($total, $page, $pagesize);
           $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where  type=1 $where order by mintk desc limit {$start},{$pagesize}",array(),'id');
           foreach ($cates as &$cate){
               $cate['child']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where  pid={$cate['id']} and pid!=0 and type=2 order by id desc");
               $cate['childtotal']=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where  pid={$cate['id']} and pid!=0 and type=2 order by id desc");
               }
           unset($cate);/*
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>1));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>2));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>3));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>4)); */
           include $this->template('main/cate');
       }
   }

   public function bizcate(){
       global $_W,$_GPC,$_M;
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('display','export','getuni','binduni','bindcate','checkcate','unbindcate','edit','saveadd','disable','setdistrict','undisable','getact','splitcate'))?$op:'display';
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
            $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
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
           $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	        
           $mintk=$_GPC['mintk'];
           $total=$_GPC['total'];
           $maxtk=$mintk+$total-1;
           $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where type=1 and (maxtk>=$mintk and mintk<=$mintk) or (maxtk>=$maxtk and mintk<=$maxtk) order by maxtk desc");
           if ($exists){
               die(json_encode(error(1,'号段已存在于'.$uniaccounts[$exists['uniacid']]['name'].'['.$exists['mintk'].'-'.$exists['maxtk'].']中')));
           }else{
               die(json_encode(error(0,'可以')));
           }
       }
       if ($op=='edit'){
           if (checksubmit('submit')){
               $mintk=$_GPC['mintk'];
               $total=$_GPC['total'];
               $price=$_GPC['price'];
               if (!$mintk){
                   message('请填写起始码号');
               }
               if (!is_numeric($total)||$total<0){
                   message('请填写二维码数量');
               }
               if (!empty($price)){
                   if(!is_numeric($price)||$price<=0){
                       message('总价请填写数字');
                   }
               }else{
                   $price=$this->globalcfg['qrcode']['founder']*$total;
               }
               $maxtk=$mintk+$total-1;
               $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where (maxtk>=$mintk and mintk<=$mintk) or (maxtk>=$maxtk and mintk<=$maxtk)");
               if ($exists){
                   message('号段已存在于['.$exists['mintk'].'-'.$exists['maxtk'].']中');
               }
               $record=array(
                   'type'=>2,
                   'uniacid'=>$_GPC['uniaccounts'],
                   'mintk'=>$mintk,
                   'total'=>$total,
                   'maxtk'=>$maxtk,
                   'price'=>$price,
                   'addtime'=>TIMESTAMP,
               );
               pdo_insert('hxq_newywym_cate',$record);
               message('更新成功',weburl('cate'),'success');
           }
             $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	           $mintk=pdo_fetchcolumn('select maxtk from '.tablename('hxq_newywym_cate')." order by maxtk desc");
           $mintk=intval($mintk)+1;
           $price=$this->globalcfg['qrcode']['founder'];
           include $this->template('main/addcate');
           exit;
       }
       if ($op=='display'){
            $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	        //var_dump($uniaccounts);
           $actid=$_GPC['actid'];
           $bizid=$_GPC['bizid'];
           $uniacid_=$_GPC['uniacid_'];
           $pid=$_GPC['pid'];
           //echo ''.$pid;
           if($pid){
               $where=" and pid=".$pid;
               $pcate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where id={$pid} order by id desc");
               $pcates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where  type=1  order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')."  order by id desc",array(),'id');
               $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')."  order by id desc",array(),'id');
                //var_dump($pcates);
           }else{
           if ($actid){
               $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where id={$actid} order by id desc");
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$act['bizid']} order by id desc",array(),'id');
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$act['bizid']} order by id desc");
               $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$biz['uniacid']} order by id desc",array(),'id');
               $uniac=pdo_fetch('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 and a.uniacid={$biz['uniacid']} order by a.uniacid desc");
               $where.=" and actid=".$actid;
           }elseif($bizid){
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$bizid} order by id desc");
               $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$biz['uniacid']} order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where bizid={$biz['id']} order by id desc",array(),'id');
               $uniac=pdo_fetch('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 and a.uniacid={$biz['uniacid']} order by a.uniacid desc");
               $where.=" and bizid=".$bizid;
           }elseif ($uniacid_){
               $uniac=pdo_fetch('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 and a.uniacid={$uniacid_} order by a.uniacid desc");
               $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniac['uniacid']} order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid={$uniac['uniacid']} order by id desc",array(),'id');
               $where.=" and uniacid=".$uniacid_;
           }else{
               $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." order by id desc",array(),'id');
           }
           }
           $pagesize=20;
           $page=$_GPC['page']?intval($_GPC['page']):1;
           $start=($page-1)*$pagesize;
           $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where type=2 $where order by id desc");
           $totalprice=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where type=2 $where order by id desc");
            
           $pager=pagination($total, $page, $pagesize);
           $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where  type=2 $where order by mintk desc limit {$start},{$pagesize}",array(),'id');
           foreach ($cates as &$cate){
               $cate['totalused']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where  tk>={$cate['mintk']} and tk<={$cate['maxtk']} and isgrant=1 order by id desc");
           }
           unset($cate);/*
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>1));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>2));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>3));
           pdo_update('hxq_newywym_cate',array('uniacid'=>2),array('id'=>4)); */
           include $this->template('main/bizcate');
       }
   }
   public function recharge($op='display'){
       global $_W,$_GPC,$_M;
       include $this->template('main/main');
   }
   public function qrcode($op='display'){
       global $_W,$_GPC,$_M;
       if (checksubmit('excel')){
           if (empty($this->globalcfg['slat'])){
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
               $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
               $strtxt.=$i;
               $strtxt.="\t".$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk));
               $strtxt.="\r";
           }
           $strtxt=iconv('UTF-8',"GB2312",$strtxt);
           exit($strtxt);
       }
       if (checksubmit('txt')){
           if (empty($this->globalcfg['slat'])){
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
               $vtk=substr(md5($i.$this->globalcfg['slat']),0,8);
               $strtxt.=$i;
               $strtxt.="\t".$_W['siteroot'].'app/'.MobileUrl('index',array('tk'=>$i,'vtk'=>$vtk));
               $strtxt.="\r\n";
           }
           $strtxt=iconv('UTF-8',"GB2312",$strtxt);
           exit($strtxt);
       }
       include $this->template('main/exportqrcode');
   }
   
   public function rechargeagent(){
       global $_W,$_GPC;
       if ($_W['role']!='founder'){
           message('没有权限');
       }
        $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b inner join ".tablename('hxq_newywym_agent')." as c on a.acid=b.acid and a.uniacid=c.uniacid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
    	       if (checksubmit('submit')){
           $uniacid__=$_GPC['uniacid__'];
           $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid__");
           if (empty($agent)){
               message($uniaccounts[$uniacid__]['name'].'尚未注册成代理，请先注册成代理。');
           }
           $money=$_GPC['money'];
           $remark=$_GPC['remark'];
           if (!$money){
               message('请输入金额');
           }
           if (!$remark){
                message('请输入备注');
           }
           if (!$uniacid__){
               message('请选择代理商（公众号）');
           }
           $data=array(
               'uniacid'=>$uniacid__,
               'money'=>$money,
               'uid'=>$_W['uid'],
               'uname'=>$_W['username'],
               'abstract'=>$remark,
               'type'=>5,
               'status'=>1,
               'addtime'=>TIMESTAMP,
               'paytime'=>TIMESTAMP,
           );
           pdo_insert('hxq_newywym_agentpaylog',$data);
           pdo_update('hxq_newywym_agent',array('recharge'=>($agent['recharge']+$money)),array('uniacid'=>$uniacid__));
           message('您成功为'.$uniaccounts[$uniacid__]['name'].'充值'.$money.'元',weburl('agenter',array('uniacid_'=>$uniacid__,'op'=>'recharges.recharge')),'success');
       }
       include $this->template('main/rechargeagent');
   }

   public function setting($op='display'){
       global $_W,$_GPC,$site;
       $uniacid=$_W['uniacid'];
       $setting=pdo_get('hxq_newywym_setting',array('type'=>'global'),'setting');
       if (!empty($setting)){
           $config=iunserializer($setting['setting']);
       }else{
           $config=array();
       }
       
       if(checksubmit("savepay")) {
           //字段验证, 并获得正确的数据$dat
           $dat['paytype']=$_GPC['paytype'];
           $dat['appid']=$_GPC['appid'];
           $dat['apikey']=$_GPC['apikey'];
           $dat['mchid']=$_GPC['mchid'];
           $dat['send_name']=$_GPC['send_name'];
           $dat['act_name']=$_GPC['act_name'];
           $dat['wishing']=$_GPC['wishing'];
           $dat['remark']=$_GPC['remark'];
           $dat['sslcert']=$_GPC['sslcert'];
           $dat['sslkey']=$_GPC['sslkey'];
           $dat['rootca']=$_GPC['rootca'];
           if (empty($dat['appid'])){
               message('请输入appid！');
           }
           if (empty($dat['apikey'])){
               message('请输入apikey！');
           }
           if (empty($dat['mchid'])){
               message('请输入mchid！');
           }
           if (empty($dat['send_name'])){
               message('请输入send_name！');
           }
           if (empty($dat['wishing'])){
               message('请输入wishing！');
           }
           if (!empty($_GPC['subappid'])){
                
               $dat['subappid']=$_GPC['subappid'];
           }
           if (!empty($_GPC['submchid'])){
               $dat['submchid']=$_GPC['submchid'];
           }
           if (empty($dat['sslcert'])){
               message('请将微信支付证书(sslcert)文件打开后的内容粘贴到此');
           }
           if (empty($dat['sslkey'])){
               message('请将微信支付私钥(sslkey)文件打开后的内容粘贴到此');
           }
           if (empty($dat['rootca'])){
               message('请将CA证书(rootca)文件打开后的内容粘贴到此');
           }
           load()->func('file');
           if (!is_dir(MODULE_ROOT.'/cert/global')){
               mkdirs(MODULE_ROOT.'/cert/global');
           }
           $cert=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.apiclient_cert.pem','w');
           fwrite($cert,$dat['sslcert']);
           fclose($cert);
           $key=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.apiclient_key.pem','w');
           fwrite($key,$dat['sslkey']);
           fclose($key);
           $rootca=fopen(MODULE_ROOT.'/cert/global/'.$uniacid.'.rootca.pem','w');
           fwrite($rootca,$dat['rootca']);
           fclose($rootca);
           $config['pay']=$dat;
           $config=iserializer($config);
           if (!empty($setting)){
           pdo_update('hxq_newywym_setting',array('setting'=>$config),array('type'=>'global'));
           }else{
               pdo_insert('hxq_newywym_setting',array('setting'=>$config,'type'=>'global'));
           }
           message('保存支付参数成功！', 'referer', 'success');
       }
       if(checksubmit("savecollect")) {
           //字段验证, 并获得正确的数据$dat
           $dat['mchid']=$_GPC['mchid'];
           $dat['appid']=$_GPC['appid'];
           $dat['apikey']=$_GPC['apikey'];
           if (empty($dat['mchid'])){
               message('请输入mchid！');
           }
           if (empty($dat['appid'])){
               message('请输入appid！');
           }
           if (empty($dat['apikey'])){
               message('请输入密钥！');
           }
           $config['collect']=$dat;
           $config=iserializer($config);
           if (!empty($setting)){
               pdo_update('hxq_newywym_setting',array('setting'=>$config),array('type'=>'global'));
           }else{
               pdo_insert('hxq_newywym_setting',array('setting'=>$config,'type'=>'global'));
           }
           message('保存收款设置！', 'referer', 'success');
       }
       if(checksubmit("saveqrcode")) {
           //字段验证, 并获得正确的数据$dat
           $dat['founder']=$_GPC['founder'];
           $dat['agenter']=$_GPC['agenter'];
           if (empty($dat['founder'])){
               message('请输入公司名称！');
           }
           if (empty($dat['agenter'])){
               message('请输入电话！');
           }
           $config['qrcode']=$dat;
           $config=iserializer($config);
           if (!empty($setting)){
           pdo_update('hxq_newywym_setting',array('setting'=>$config),array('type'=>'global'));
           }else{
               pdo_insert('hxq_newywym_setting',array('setting'=>$config,'type'=>'global'));
           }
           message('保存公司成功！', 'referer', 'success');
       }
       if (checksubmit('savecorpright')){
           $dat['corpright']=$_GPC['corpright'];
           if (empty($dat['corpright'])){
               message('请输入版权信息！');
           }
           $config['corpright']=htmlspecialchars_decode($dat['corpright']);
           $config=iserializer($config);
           if (!empty($setting)){
           pdo_update('hxq_newywym_setting',array('setting'=>$config),array('type'=>'global'));
           }else{
               pdo_insert('hxq_newywym_setting',array('setting'=>$config,'type'=>'global'));
           }
           message('保存版权成功！', referer(), 'success');
       }
       
       if(checksubmit("savejump")) {
           $dat['redirect']=$_GPC['redirect'];
           $dat['display']=$_GPC['display'];/* 
           if (!empty($dat['redirect'])&&substr($dat['redirect'],-1)!='/'){
		        message('网址请以/结束');
		   }
		   if (!empty($dat['display'])){
		        if (strexists($dat['display'],"\r\n")){
		            $displays=explode("\r\n",$dat['display']);
		        }elseif(strexists($dat['display'],"\n")){
		            $displays=explode("\n",$dat['display']);
		        }elseif(strexists($dat['display'],"\r")){
		            $displays=explode("\r",$dat['display']);
		        }else{
		            $displays=array($dat['display']);
		        }
		        $displays=array_filter($displays);
		        foreach ($displays as $row){
		            if (substr($row,-1)!='/'){
		                message('网址请以/结束');
		            }
		        }
		   } */
           $config['jump']=$dat;
           $config=iserializer($config);
           if (!empty($setting)){
           pdo_update('hxq_newywym_setting',array('setting'=>$config),array('type'=>'global'));
           }else{
               pdo_insert('hxq_newywym_setting',array('setting'=>$config,'type'=>'global'));
           }
           message('保存防封成功！', referer(), 'success');
       }
       //这里来展示设置项表单/*
       load()->func('tpl');
       include $this->template('main/setting');
   }
   public function patch($op='display'){
       global $_W,$_GPC,$site;
       $uniacid=$_W['uniacid'];
       $patchs=array('20180209'=>'兼容原来的码段补丁');
       $op=in_array($op,array('display','dopatch'))?$op:'display';
       if ($op=='dopatch'){
           mkdirs(MODULE_ROOT.'/patchs/');
           switch ($_GPC['pid']){
           case '20180209':
           if (file_exists(MODULE_ROOT.'/patchs/'.$_GPC['pid'].'.txt')){
               message($_GPC['pid'].'补丁已经打过了。');
           }
           $cates=pdo_fetchall('select * from '.tablename('hxq_newywym_cate')." where type=0");
           foreach($cates as $key=>$row){
               $cf=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where mintk<={$row['mintk']} and maxtk>={$row['maxtk']} and id!={$row['id']}");
               if (!empty($cf)){
                   message("您有重复的码段id:{$row['id']},{$cf['id']},请删除重复的码段再打补丁。");
               }
           }
           foreach ($cates as $key=>$row){
               if ($row['type']==0){
                  $data=array(
                      'uniacid'=>$row['uniacid'],
                      'pid'=>0,
                      'mintk'=>$row['mintk'],
                      'maxtk'=>$row['maxtk'],
                      'actid'=>$row['actid'],
                      'bizid'=>$row['bizid'],
                      'addtime'=>TIMESTAMP,
                      'type'=>1,
                      'total'=>$row['total'],
                      'price'=>$this->globalcfg['qrcode']['founder']*$row['total'],
                  );
                  pdo_insert('hxq_newywym_cate',$data);
                  $pid=pdo_insertid();
                  pdo_update('hxq_newywym_cate',array('type'=>2,'pid'=>$pid),array('id'=>$row['id']));
               }
           }
           file_put_contents(MODULE_ROOT.'/patchs/'.$_GPC['pid'].'.txt','success');
           message('补丁成功',referer(),'success');
           break;
          }
       }
       if ($op=='display'){
           include $this->template('main/patch');
       }
   }
}
<?php

class biz extends page{
	  public function main($op='display'){
            global $_W,$_GPC,$_M;
    	    $uniacid=$_W['uniacid'];
    	   $op=in_array($op,array('display','edit','save','delete','disable','adduser','saveuser'))?$op:'display';
            //var_dump($cate);
           //var_dump($cateid);
        $types=array('房地产类','餐饮类','服饰类','食品类','电子产品类','服务类','运动器械类','文具书籍类','医药保健类','室内设计类','数码类');
        if ($op=='edit'){
            
            //var_dump($_W);
            //var_dump($act);
            $id=intval($_GPC['id']);
            if (!empty($id)){
                if ($_W['role']=='operator'){
                    if ($_W['uid']!=pdo_fetchcolumn("select uid from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['id']}")){
                        message('没有权限');
                    }
                }
                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where id={$id} and uniacid={$uniacid}");
            }else{
                if ($_W['role']=='operator'){
                    $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']}");
                    if ($total>0){
                        message('没有权限:最多只能添加一个商家！');
                    }
                }
            }
            //var_dump($award);
            load()->func('tpl');
            include $this->template('biz/bizedit'); 
            exit;
        }elseif ($op=='save'){
            $biz=$_GPC['biz'];
            
            if (empty($biz['type'])){
                message('请选分类');
            }
            if (empty($biz['name'])){
                message('请输入商家名称');
            }
            if (empty($biz['thumb'])){
                message('请上传缩略图');
            }
            if (empty($biz['biz_hours'])){
                message('请输入营业时间');
            }
            if (empty($biz['phone'])){
                message('请输入联系电话');
            }
            if (empty($biz['address'])){
                message('请输入商家地址');
            }
            if (empty($biz['description'])){
                message('请填写描述');
            }
            if (empty($biz['verifpwd'])){
                message('请填写核销密码');
            }
            if (!empty($biz['id'])){
                if ($_W['role']=='operator'){
                    if ($_W['uid']!=pdo_fetchcolumn("select uid from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$biz['id']}")){
                        message('没有权限');
                    }
                }
            }else{
                if ($_W['role']=='operator'){
                    if (pdo_fetchcolumn("select count(uid) from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and uid={$_W['uid']}")>0){
                        message('没有权限:最多只能添加一个商家！');
                    }
                    $biz['uid']=$_W['uid'];
                    $biz['uname']=$_W['username'];
                }
            }
            if (!empty($biz['id'])){
                $result=pdo_update('hxq_newywym_biz',$biz,array('id'=>$biz['id']));
            }else{
                $biz['addtime']=TIMESTAMP;
                $biz['uniacid']=$_W['uniacid'];
                $result=pdo_insert('hxq_newywym_biz',$biz);
            }
                message('更新成功',weburl('biz'),'success');
        }elseif($op=='delete'){
            $id=intval($_GPC['id']);
            if ($_W['role']=='operator'){
                if ($_W['uid']!=pdo_fetchcolumn("select uid from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} and id={$_GPC['id']}")){
                    message('没有权限');
                }
            }
            if (!empty($id)){
                pdo_delete('hxq_newywym_biz',array('id'=>$id));
                pdo_delete('hxq_newywym_activity',array('bizid'=>$id));
                pdo_delete('hxq_newywym_token',array('bizid'=>$id));
                pdo_delete('hxq_newywym_scan',array('bizid'=>$id));
                pdo_delete('hxq_newywym_coupon',array('bizid'=>$id));
                pdo_delete('hxq_newywym_coupongrant',array('bizid'=>$id));
                pdo_delete('hxq_newywym_paylog',array('bizid'=>$id));
                pdo_delete('hxq_newywym_recharge',array('bizid'=>$id));
                message('删除成功','referer','success');
            }
	    }elseif($op=='display'){
	        if ($_W['role']=='operator'){
	            $where=" and uid={$_W['uid']}";
	        }
            $page=$_GPC['page']>0?$_GPC['page']:1;
            $start=($page-1)*30;
            $pagesize=30;
            $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid}  $where order by id asc");
            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} $where order by id asc limit $start,$pagesize");
            foreach($bizs as &$row){
                $row['qrcode']=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_paylog')." where bizid={$row['id']} and type=1 order by id asc");
                $row['qrcode']=floatval($row['qrcode']);
                $row['qrcode']=number_format($row['qrcode'],2);
                $row['redpack']=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_paylog')." where bizid={$row['id']} and type=0 order by id asc");
                $row['redpack']=floatval($row['redpack']);
                $row['redpack']=number_format($row['redpack'],2);
            }
            unset($row);
            $pager = pagination($total, $page,30);
    	    include $this->template('biz/biz');
	    }
	    if ($op=='getusers'){
	        $users=pdo_fetchall("select b.id,b.name from ".tablename('uni_account_users')." left join ".tablename('user')." on a.uid=b.id where  uniacid={$uniacid} order by id asc");
            if (empty($user)){
                array(1,'没有找到');
            }else{
                
            }
	    }
	    if ($op=='saveuser'){
	         
	    }
	}
	public function recharge($op='display'){
	     global $_W,$_GPC,$_M;
	     //var_dump($_W);
    	    $uniacid=$_W['uniacid'];
            //var_dump($cate);
           //var_dump($cateid);
        $types=array('房地产类','餐饮类','服饰类','食品类','电子产品类','服务类','运动器械类','文具书籍类','医药保健类','室内设计类','数码类');
	    $op=in_array($op,array('display','recharge','saverecharge','expend'))?$op:'display';
	    $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid");
	    if ($op=='recharge'){
	        if ($_W['role']=='operator'){
	            message('您没有充值的权限');
	        }
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$_GPC['bizid']} order by id asc");
            include $this->template('biz/recharge');
	    }
	    if ($op=='saverecharge'){
	        $bizid=$_GPC['bizid'];
	        if ($_W['role']=='operator'){
	            message('您没有充值的权限');
	        }
	        $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$_GPC['bizid']} order by id asc");
	        $money=$_GPC['money'];
	        if ($agent['recharge']<$money){
	            message('您的代理账户余额不足 '.$money.' 元');
	        }
	        $abstract=$_GPC['abstract'];
	        $data=array(
	            'uniacid'=>$uniacid,
	            'bizid'=>$bizid,
	            'money'=>$money,
	            'uid'=>$_W['uid'],
	            'user'=>$_W['username'],
	            'abstract'=>$abstract,
	            'addtime'=>TIMESTAMP,
	            'type'=>3
	        );
	        $data_=array(
	            'uniacid'=>$uniacid,
	            'money'=>$money,
	            'uid'=>$_W['uid'],
	            'uname'=>$_W['username'],
	            'abstract'=>$abstract,
	            'addtime'=>TIMESTAMP,
	            'type'=>3,
	            'status'=>1,
	            'bizid'=>$bizid
	            
	        );
	        pdo_insert('hxq_newywym_recharge',$data);
	        $brid=pdo_insertid();
	        $data_['brid']=$brid;
	        pdo_insert('hxq_newywym_agentpaylog',$data_);
	        pdo_update('hxq_newywym_agent',array('recharge'=>($agent['recharge']-$money)),array('uniacid'=>$uniacid));
	        pdo_update('hxq_newywym_biz',array('recharge'=>$biz['recharge']+$money),array('id'=>$bizid));
	        message('您为'.$biz['name'].'成功充值'.$money.'元',weburl('biz',array('op'=>'recharges','bizid'=>$biz['id'])),'success');
	    }    
	}
	
	public function recharges($op='display'){
	    global $_W,$_GPC,$_M;
	    //var_dump($_W);
	    $uniacid=$_W['uniacid'];
	    $op=in_array($op,array('display','recharges','expends'))?$op:'recharges';
	    $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid");
	    $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid={$uniacid} order by id asc",array(),'id');
	    if ($op=='expends'){
	        if ($_W['role']=='operator'){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid = {$_W['uid']} order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id asc");
	            $where=" and bizid={$biz['id']}";
	        }elseif ($_GPC['bizid']){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$_GPC['bizid']} order by id asc");
	            $where=" and bizid={$biz['id']}";
	        }else{
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id desc",array(),'id');
	        }
	        $total=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_recharge')." where uniacid=$uniacid and type=4 $where");
	        $page=$_GPC['page']?intval($_GPC['page']):1;
	        $pagesize=30;
	        $pager=pagination($total,$page,$pagesize);
	        $totalvalue=pdo_fetchcolumn('select sum(money) from '.tablename('hxq_newywym_recharge')." where uniacid=$uniacid and type=4 $where");
	        $totalvalue=number_format($totalvalue,2);
	         
	        $expends=pdo_fetchall("select * from ".tablename('hxq_newywym_recharge')." where  uniacid={$uniacid} and type=4 $where order by id asc");
	        include $this->template('biz/expends');
	    }
	    if ($op=='recharges'){
	        if ($_W['role']=='operator'){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid = {$_W['uid']} order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id asc");
	            $where=" and bizid={$biz['id']}";
	        }elseif ($_GPC['bizid']){
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$_GPC['bizid']} order by id asc");
	            $where=" and bizid={$biz['id']}";
	        }else{
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id desc",array(),'id');
	        }
	        $total=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_recharge')." where uniacid=$uniacid and type=3 $where");
	        $page=$_GPC['page']?intval($_GPC['page']):1;
	        $pagesize=30;
	        $pager=pagination($total,$page,$pagesize);
	        $totalvalue=pdo_fetchcolumn('select sum(money) from '.tablename('hxq_newywym_recharge')." where uniacid=$uniacid and type=3 $where");
	        $totalvalue=number_format($totalvalue,2);
	    
	        $rhs=pdo_fetchall("select * from ".tablename('hxq_newywym_recharge')." where  uniacid={$uniacid} and type=3 $where order by id asc");
	        include $this->template('biz/recharges');
	    }
	}
	
	public function expend($op='display'){
	    global $_W,$_GPC,$_M;
	    //var_dump($_W);
	    $uniacid=$_W['uniacid'];
	    $op=in_array($op,array('display','recharges','expends'))?$op:'display';
	    $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid");
	    
	        $bizid=$_GPC['bizid'];
	        if ($_GPC['actid']){
	            $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where  uniacid={$uniacid} and id={$_GPC['actid']} order by id desc");
	            $where=" and actid={$_GPC['actid']}";
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where  uniacid={$uniacid} and bizid={$act['bizid']} order by id desc",array(),'id');
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$act['bizid']} order by id desc",array(),'id');
	            if ($_W['role']=='operator'&&$_W['uid']!=$biz['uid']){
	                message('没有权限');
	            }
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} order by id desc",array(),'id');
	    
	        }elseif ($bizid){
	            $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and id={$bizid} order by id asc");
	            if ($_W['role']=='operator'&&$_W['uid']!=$biz['uid']){
	                message('没有权限');
	            }
	            $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid}  order by id desc",array(),'id');
	            $where=" and bizid={$biz['id']}";
	            $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where  uniacid={$uniacid} $where order by id desc",array(),'id');
	            
	        }else{
	            if ($_W['role']=='operator'){
	                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id desc",array(),'id');
	                $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid} and uid={$_W['uid']} order by id desc");
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where  uniacid={$uniacid} and bizid={$biz['id']} order by id desc",array(),'id');
	                $where=" and bizid={$biz['id']}";
	            }else{
	                $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where  uniacid={$uniacid}  order by id desc",array(),'id');
	                $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where  uniacid={$uniacid}  order by id desc",array(),'id');
	            }
	        }
	        $total=pdo_fetchcolumn('select count(id) from '.tablename('hxq_newywym_paylog')." where uniacid=$uniacid and type=0 $where");
	        $page=$_GPC['page']?intval($_GPC['page']):1;
	        $pagesize=30;
	        $pager=pagination($total,$page,$pagesize);
	        $totalvalue=pdo_fetchcolumn('select sum(money) from '.tablename('hxq_newywym_paylog')." where uniacid=$uniacid and type=0 $where");
	        $expends=pdo_fetchall("select * from ".tablename('hxq_newywym_paylog')." where  uniacid={$uniacid} and type=0 $where order by id asc",array(),'id');
	        $totalvalue=number_format($totalvalue,2);
	        include $this->template('biz/expend');
	}
	
	public function user($op='display'){
	    global $_W,$_GPC,$_M;
	    //var_dump($_W);
	    $uniacid=$_W['uniacid'];
	    //var_dump($cate);
	    //var_dump($cateid);
	    $op=in_array($op,array('getuser','binduser','edit'))?$op:'display';
	    if ($op=='edit'){
	        if (checksubmit('submit')){
	            $user=$_GPC['user'];
	            if (empty($user['username'])){
	                message('请输入用户名');
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
    	                'uniacid'=>$uniacid,
    	                'uid'=>$uid,
    	                'role'=>'operator',
    	                'rank'=>0,
    	            );
    	            pdo_insert('uni_account_users',$unidata);
    	            $unipermission=array(
    	                'uniacid'=>$uniacid,
    	                'uid'=>$uid,
    	                'type'=>'hxq_newywym',
    	                'permission'=>MODULE_NAME.'_cover_index'.'|'.MODULE_NAME.'_menu_coupon'.'|'.MODULE_NAME.'_menu_biz|'.MODULE_NAME.'_menu_census|'.MODULE_NAME.'_menu_awarded|'.MODULE_NAME.'_menu_fan|'.MODULE_NAME.'_menu_cate|'.MODULE_NAME.'_menu_award|'.MODULE_NAME.'_menu_activity',
    	            );
    	            pdo_insert('users_permission',$unipermission);
    	            pdo_update('hxq_newywym_biz',array('uid'=>$uid,'uname'=>$user['username']),array('id'=>$_GPC['bizid']));
	            }else{
	                if (empty($user['password'])){
	                    message('请输入密码');
	                }
	                if (empty($user['repassword'])){
	                    message('请输入确认密码');
	                }
	                if ($user['password']!=$user['repassword']){
	                    message('两次输入的密码不一致');
	                }
	                $salt=pdo_fetchcolumn('select salt from '.tablename('users')." where uid ={$user['uid']}");
	                $password=sha1("{$user['password']}-{$salt}-{$_W['config']['setting']['authkey']}");
	                $data=array(
	                    'groupid'=>$user['groupid'],
	                    'password'=>$password,
	                );
	                pdo_update('users',$data,array('uid'=>$user['uid']));
	            }
	            message('更新成功',weburl('biz'),'success');
	        }
	        $usergroups=pdo_fetchall('select * from '.tablename('users_group')." where name='营销码商家组'");
	        if ($_GPC['uid']){
	            $user=pdo_fetch('select * from '.tablename('users')." where uid={$_GPC['uid']}");
	        }
	        include $this->template('biz/useredit');
	    }else{
	        
	    }
	    
	}
	
	public function verifer($op='display'){
	    global $_W,$_GPC,$_M;
	    $uniacid=$_W['uniacid'];
	    $op=in_array($op,array('display','delete'))?$op:'display';
	    //var_dump($cate);
	    //var_dump($cateid);
	    if($op=='delete'){
	        $id=intval($_GPC['id']);
	        if (!empty($id)){
	            pdo_delete('hxq_newywym_verifer',array('id'=>$id));
	            message('删除成功',referer(),'success');
	        }
	    }elseif($op=='display'){
	        $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$_GPC['bizid']}",array(),'id');
	        $where=" and bizid={$_GPC['bizid']}";
	        $page=$_GPC['page']>0?$_GPC['page']:1;
	        $start=($page-1)*30;
	        $pagesize=30;
	        $total=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_verifer')." where  uniacid={$uniacid}  $where order by id asc");
	        $verifers=pdo_fetchall("select * from ".tablename('hxq_newywym_verifer')." where uniacid={$uniacid} $where order by id asc limit $start,$pagesize");
	        foreach($verifers as &$row){
	            $a=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_token')." where veriferid={$row['id']} order by id asc");
	            $b=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_coupongrant')." where veriferid={$row['id']} order by id asc");
	            $row['totalverif']=$a+$b;
	            $row['totalverif']=intval($row['totalverif']);
	        }
	        unset($row);
	        $pager = pagination($total, $page,30);
	        include $this->template('biz/verifer');
	    }	        
	}
}

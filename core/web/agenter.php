<?php
class agenter extends page{
   public function main($op='display'){
       global $_W,$_GPC,$_M;
       if ($_W['role']=='operator'){
           message('没有权限');
       }
       $uniacid=$_W['uniacid'];
       
       $op=in_array($op,array('display','edit','save'))?$op:'display';
       if ($op=='edit'){
           $agent=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid");
           include $this->template('agent/edit');
       }
       if ($op=='save'){
           $agent=$_GPC['agent'];
           if (!$agent['uname']){
               message('请输入姓名');
           }
           if (!$agent['phone']){
               message('请输入电话');
           }
           if (!$agent['address']){
               message('请输入地址');
           }
           $exists=pdo_fetch('select * from '.tablename('hxq_newywym_agent')." where uniacid=$uniacid");
           if (!$exists){
               $agent['addtime']=TIMESTAMP;
               $agent['uniacid']=$_W['uniacid'];
               $agent['addip']=getip();
               $result=pdo_insert('hxq_newywym_agent',$agent);
           }else{
               $result=pdo_update('hxq_newywym_agent',$agent,array('uniacid'=>$uniacid));
           }
           message('更新成功',weburl('agenter'),'success');
       }
       if ($op=='display'){
           $agent=pdo_fetch('select  * from '.tablename('hxq_newywym_agent')." where uniacid={$uniacid}");
	    
       $totalbiz=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid ");
       $totalact=pdo_fetchcolumn("select count(*) from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid ");
       $totalqrexpend=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1");
       $totalbizqrincome=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2");
       $totalqr=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1");
       $totalqruse=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2");
       $totalqrleft=$totalqr-$totalqruse;
       $totalagrecharge=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid and (type=1 or type=5) and status=1");
       $totalbizrecharge=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid and type=3 and status=1");
       $totalagrecharge=floatval($totalagrecharge);
       $totalbizrecharge=floatval($totalbizrecharge);
       $totalrecharge=$totalagrecharge-$totalbizrecharge;
       $totalrecharge=number_format($totalrecharge,2);
       include $this->template('agent/display');
       }
   }
   
   
   public function recharge($op='display'){
       global $_W,$_GPC,$_M;
       if ($_W['role']=='operator'){
           message('没有权限');
       }
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('display','getqr','checkorder'))?$op:'display';
       
       $agent=pdo_fetch('select  * from '.tablename('hxq_newywym_agent')." where uniacid={$uniacid}");
       if (empty($agent)){
           message('请先填写代理信息再进行充值');
       }
       if ($op=='getqr'){
           if (empty($_GPC['money'])||!is_numeric($_GPC['money'])){
               response(error(1,'请输入充值的金额'));
           }
           if (!$_GPC['remark']){
               response(error(1,'请输入充值备注'));
           }
           $order=array(
               'uniacid'=>$uniacid,
               'uid'=>$_W['uid'],
               'uname'=>$_W['username'],
               'oauth_openid'=>'',
               'money'=>$_GPC['money'],
               'abstract'=>$_GPC['remark'],
               'type'=>1,
               'orderid'=>MODULE_NAME.date('YmdHis').random(4),
               'status'=>0,
               'addtime'=>TIMESTAMP
           );
           pdo_insert('hxq_newywym_agentpaylog',$order);
           $rdid=pdo_insertid();
           $product_id=1;
           load()->classs('pay');
           $pay=Pay::create();
           //var_dump($this->globalcfg);
           $pay->wxpay['mch_id']=$this->globalcfg['collect']['mchid'];
           $pay->wxpay['appid']=$this->globalcfg['collect']['appid'];
           $pay->wxpay['key']=$this->globalcfg['collect']['apikey'];
           $pay->wxpay['notify_url']=MODULE_URL.'notify.php';
           $data = array(
			'body' => '营销码-代理商充值',
			'out_trade_no' => $order['orderid'],
			'total_fee' => $_GPC['money'] * 100,
			'trade_type' => 'NATIVE',
			'product_id' => $product_id,
			'attach' => $order['uniacid'],
    	   );
    	   $result = $pay->buildUnifiedOrder($data);
    	   if (is_error($result)){
    	       response($result);
    	   }
    	   pdo_update('hxq_newywym_agentpaylog',array('prepay_id'=>$result['prepay_id']),array('id'=>$rdid));
    	   //var_dump($result);
           require MODULE_ROOT.'/inc/mfunctions.php';
           $fileurl=qrcode($result['code_url']);
           response(array('errno'=>0,'message'=>'cg','data'=>$fileurl,'id'=>$rdid));
       }
       if ($op=='checkorder'){
           $id=$_GPC['id'];
           $order=pdo_fetch('select * from '.tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid and id=$id");
           if (!$order['status']){
               response(error(1,'未支付'));
           }else{
               response(array('errno'=>0,'message'=>'已支付','money'=>$order['money']));
           }
       }
       if ($op=='display'){
           include $this->template('agent/recharge');
       }
   }

   public function recharges($op='display'){
       global $_W,$_GPC,$_M;
       if ($_W['role']=='operator'){
           message('没有权限');
       }
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('expend','recharge'))?$op:'recharge';
       $agent=pdo_fetch('select  * from '.tablename('hxq_newywym_agent')." where uniacid={$uniacid}");
       $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid={$uniacid}",array(),'id');
       if ($op=='expend'){
           $pagesize=20;
           $page=$_GPC['page']?intval($_GPC['page']):1;
           $start=($page-1)*$pagesize;
           $totalvalue=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid  and type=3 order by id desc");
           $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid  and type=3 order by id desc");
           $pager=pagination($total, $page, $pagesize);
           $expends=pdo_fetchall("select * from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid and type=3 order by id desc limit {$start},{$pagesize}",array(),'id');
           include $this->template('agent/expend');
       }
       if ($op=='recharge'){
           $pagesize=20;
           $page=$_GPC['page']?intval($_GPC['page']):1;
           $start=($page-1)*$pagesize;
           $totalvalue=pdo_fetchcolumn("select sum(money) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid  and (type=1 or type=5) and status=1 order by id desc");
           $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid  and (type=1 or type=5) and status=1 order by id desc");
           $pager=pagination($total, $page, $pagesize);
           $rhs=pdo_fetchall("select * from ".tablename('hxq_newywym_agentpaylog')." where uniacid=$uniacid and (type=1 or type=5) and status=1 order by id desc limit {$start},{$pagesize}",array(),'id');
           include $this->template('agent/recharges');
       }
   }
    
   public function redpack($op='display'){
       global $_W,$_GPC,$_M;
       if ($_W['role']=='operator'){
           message('没有权限');
       }
       include $this->template('agent/redpack');
   }

   public function cate($op='display'){
       global $_W,$_GPC,$_M;
       $uniacid=$_W['uniacid'];
       $op=in_array($op,array('display','export','getact','getbiz','bindbiz','bindact','checkcate','unbindact','edit','saveadd','disable','setdistrict','undisable','splitcate'))?$op:'display';
       if ($op=='export'){
           if (checksubmit('excel')){
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
           include $this->template('agent/exportqrcode');
       }
       if ($op=='getact'){
           $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$_GPC['bizid']} order by id desc");
   
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
           if($maxtk>$t0exists['maxtk']){
               $pretotal=$t0exists['maxtk']-$mintk+1;
               $aftertotal=$total-$pretotal;
               $t0exists_=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1  and mintk>{$t0exists['mintk']} order by mintk asc");
               if (!$t0exists_){
                   die(json_encode(error(1,'剩余的二维码最多只能再生成'.$pretotal.'个二维码的码段')));
               }
               if ($aftertotal>$t0exists_['total']){
                   die(json_encode(error(1,'剩余的二维码不够您需要 的数量')));
               }
           }
           die(json_encode(error(0,'可以')));
       }
       if ($op=='edit'){
           if (checksubmit('submit')){
               $mintk=$_GPC['mintk'];
               $total=$_GPC['total'];
               $price=$_GPC['price'];
               if (!is_numeric($total)||$total<0){
                   message('请填写二维码数量');
               }
               $t0exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 and (maxtk>=$mintk and mintk<=$mintk)");
               if (!$t0exists){
                   message('起始码不存在');
               }
               $t1exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2 and (maxtk>=$mintk and mintk<=$mintk)");
               if ($t1exists){
                   message('起始码已经被使用');
               }
               if($mintk+$total-1<=$t0exists['maxtk']){
                   $maxtk=$mintk+$total-1;
                   $record=array(
                       'type'=>2,
                       'pid'=>$t0exists['id'],
                       'uniacid'=>$uniacid,
                       'bizid'=>$_GPC['bizid'],
                       'mintk'=>$mintk,
                       'total'=>$total,
                       'maxtk'=>$maxtk,
                       'addtime'=>TIMESTAMP,
                       'price'=>$price,
                   );
                   pdo_insert('hxq_newywym_cate',$record);
                   message('更新成功',weburl('agenter',array('op'=>'cate')),'success');
               }else{
                   $pretotal=$t0exists['maxtk']-$mintk+1;
                   $aftertotal=$total-$pretotal;
                   $t0exists_=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 and mintk>{$t0exists['mintk']}");
                   if (!$t0exists_){
                       message('剩余的二维码最多只能再生成'.$pretotal.'个二维码的码段');
                   }
                   if ($aftertotal>$t0exists_['total']){
                       message('剩余的二维码不够您需要 的数量');
                   }
                   $preprice=$price*($pretotal/$total);
                   $preprice=intval($preprice);
                   $record=array(
                       'type'=>2,
                       'pid'=>$t0exists['id'],
                       'uniacid'=>$uniacid,
                       'bizid'=>$_GPC['bizid'],
                       'mintk'=>$mintk,
                       'total'=>$pretotal,
                       'maxtk'=>$t0exists['maxtk'],
                       'addtime'=>TIMESTAMP,
                       'price'=>$preprice,
                   );
                   pdo_insert('hxq_newywym_cate',$record);
                   $afterprice=$price*($aftertotal/$total);
                   $afterprice=intval($afterprice);
                   $record=array(
                       'type'=>2,
                       'pid'=>$t0exists_['id'],
                       'uniacid'=>$uniacid,
                       'bizid'=>$_GPC['bizid'],
                       'mintk'=>$t0exists_['mintk'],
                       'total'=>$aftertotal,
                       'maxtk'=>$t0exists_['mintk']+$aftertotal-1,
                       'addtime'=>TIMESTAMP,
                       'price'=>$afterprice,
                   );
                   pdo_insert('hxq_newywym_cate',$record);
                   message('更新成功',weburl('agenter',array('op'=>'cate.display')),'success');
               }
           }
           $bizs=pdo_fetchall('select * from '.tablename('hxq_newywym_biz')." where uniacid=$uniacid",array(),'id');
           $maxtk=pdo_fetchcolumn('select maxtk from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2 order by maxtk desc");
           if (empty($maxtk)){
               $mintk=pdo_fetchcolumn('select mintk from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 order by mintk asc");
               if (empty($mintk)){
                   message('您还没有申请码段，请向总部新申请码段');
               }
           }else{
               $mintk=intval($maxtk)+1;
               $exists=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 and maxtk>=$mintk and mintk<=$mintk");
               if (!$exists){
                   $mintk=pdo_fetchcolumn('select mintk from '.tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 and  mintk>$mintk order by mintk asc");
                   if (!$mintk){
                       message('码段已用完，请向总部新申请码段');
                   }
               }
           }
           if ($this->cfg['qrcode']){
               $price=$this->cfg['qrcode']['agenter'];
           }else{
               $price=$this->globalcfg['qrcode']['agenter'];
           }
           //echo 4;
           //var_dump($mintk);
           include $this->template('agent/addcate');
           exit;
       }
       if ($op=='display'){
           $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
           $bizs=pdo_fetchall("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid order by id desc",array(),'id');
           $actid=$_GPC['actid'];
           $bizid=$_GPC['bizid'];
           $pid=$_GPC['pid'];
           if ($_GPC['actid']){
               $where=" and actid=".$_GPC['actid'];
               $act=pdo_fetch("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$actid} order by id desc");
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$act['bizid']} order by id desc");
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and bizid={$biz['id']} order by id desc",array(),'id');
           }elseif ($bizid){
               $where=" and bizid=".$bizid;
               $biz=pdo_fetch("select * from ".tablename('hxq_newywym_biz')." where uniacid=$uniacid and id={$bizid} order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid and bizid={$bizid} order by id desc",array(),'id');
           }elseif ($pid){
               $where=" and pid=".$pid;
               $pcate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and id={$pid} and type=1 order by id desc");
               $pcates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1  order by id desc",array(),'id');
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid order by id desc",array(),'id');
          
           }else{
               $acts=pdo_fetchall("select * from ".tablename('hxq_newywym_activity')." where uniacid=$uniacid  order by id desc",array(),'id');
                
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
           include $this->template('agent/cate');
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
           $cate=pdo_fetch("select * from ".tablename('hxq_newywym_cate')." where  id={$_GPC['cateid']} order by mintk asc");
   
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
               'qrurl'=>WebUrl('agenter',array('op'=>'showqrcode.qrcode','cateid'=>$cate['id'],'tk'=>$i,'vtk'=>$vtk)),
               'province'=>$token['province'],
               'city'=>$token['city'],
               'district'=>$token['district'],
               'usedtime'=>$token['addtime'],
               'enable'=>$token['enable']
           );
       }
       $pager = pagination($total, $page, $pagesize);
       include $this->template('agent/tokenmanage');
   }
    
   public function my($op='display'){
       global $_W,$_GPC,$_M;
       $uniacid=$_W['uniacid'];
       $uniaccounts=pdo_fetchall('select b.* from '.tablename('account')." a inner join ".tablename('account_wechats')." b on a.acid=b.acid and a.isdeleted=0 order by a.uniacid desc",array(),'uniacid');
   
       $pagesize=20;
       $page=$_GPC['page']?intval($_GPC['page']):1;
       $start=($page-1)*$pagesize;
       $totalprice=pdo_fetchcolumn("select sum(price) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1 order by id desc");
       $total=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid  and type=1 order by id desc");
       $pager=pagination($total, $page, $pagesize);
       $cates=pdo_fetchall("select * from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=1  order by mintk desc limit {$start},{$pagesize}",array(),'id');
       foreach ($cates as &$cate){
           $cate['totalused']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_token')." where uniacid=$uniacid and  cateid={$cate['id']} order by id desc");
           $cate['child']=pdo_fetchcolumn("select count(id) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2 and pid={$cate['id']}  order by id desc");
           $cate['childtotal']=pdo_fetchcolumn("select sum(total) from ".tablename('hxq_newywym_cate')." where uniacid=$uniacid and type=2 and pid={$cate['id']}  order by id desc");
           $cate['childtotal']=intval($cate['childtotal']);
       }
       unset($cate);
       include $this->template('agent/mycate');
   }
    
}

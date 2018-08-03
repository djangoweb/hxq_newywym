<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_NAME','hxq_newywym');
require_once IA_ROOT.'/addons/hxq_newywym/inc/functions.php';
require_once IA_ROOT.'/addons/hxq_newywym/com/sendmessage.php';
require_once IA_ROOT.'/addons/hxq_newywym/inc/route.php';

class hxq_newywymModuleSite extends WeModuleSite {
	public function __construct(){
	   /*  $total=pdo_fetchcolumn('select count(*) from '.tablename('core_sessions')."");
	    echo $total; */
	}
	
	public function doWebWeb($fname){
	    Global $_W,$_GPC;
	    $controller=substr($fname,5);
	    route($controller);
	}
	
	public function doMobileMobile($fname){
	    Global $_W,$_GPC;
	    $controller=substr($fname,8);
	    route($controller,false);
	}
	public function doweblogin(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebfounder(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	
	public function doWebactivity(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	
	public function doWebaward(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebawarded(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebverif(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebbiz(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebcensus(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebcoupon(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebfan(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebtoken(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebcate(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	public function doWebagenter(){
	    Global $_W,$_GPC;
	    $this->dowebweb(__FUNCTION__);
	}
	
	public function doMobileawardedrcd(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	
	public function doMobilebiz(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function doMobilecoupon(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	
	public function doMobilegrant(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	
	public function doMobileindex(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function doMobilemy(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	
	public function doMobilecensus(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function doMobileverif(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function doMobilecstmrapply(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function doMobilefindscan(){
	    Global $_W,$_GPC;
	    $this->doMobilemobile(__FUNCTION__);
	}
	public function payresult($ret=array()){
	    Global $_W,$_GPC;
	    $uniacid=$_W['uniacid'];
	    $order=pdo_fetch('select  * from '.tablename('hxq_newywym_agentpaylog')." where orderid='{$ret['out_trade_no']}'");
	    pdo_update('hxq_newywym_agentpaylog',array('status'=>1,'oauth_openid'=>$ret['user'],'transaction_id'=>$ret['transaction_id'],'paytime'=>$ret['paytime']),array('id'=>$order['id']));
	    $agent=pdo_fetch('select  * from '.tablename('hxq_newywym_agent')." where uniacid={$order['uniacid']}");
	    pdo_update('hxq_newywym_agent',array('recharge'=>($agent['recharge']+$order['money'])),array('uniacid'=>$order['uniacid']));
	    
	}
}
   ?>
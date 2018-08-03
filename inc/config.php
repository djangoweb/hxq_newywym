<?php 
global $_M;
$indstc_=array(
           array('学历教育','出过留学','婴幼儿教育','在线教育','培训机构','教育信息服务'),
           array('健康咨询服务','公立医疗机构','医疗保健信息服务'),
           array('网络信息借贷中介(p2p)','非金融机构自营小额贷款'),
           array('物业管理','房地产开发经营','房地产'),
           array('生活缴费','家政','丽人','宠物','摄影','婚庆','洗浴/保健','休闲娱乐','线下超市'),
           array('线下餐饮'),
           array('出行','酒店','旅行社','景区服务')
       );
$indstc=array();
       foreach($indstc_ as $k=>$item){
           $item__=array();
           foreach($item as $k_=>$item_){
               $item__[$k_+1]=$item_;
           }
           $indstc[$k+1]=$item__;
       }
$indstp=array();
$indstp_=array('教育','医疗','金融','房地产','生活服务','餐饮','旅游');
foreach($indstp_ as $k=>$item){
   $indstp[$k+1]=$item;
}
$_M['indstp']=$indstp;
$_M['indstc']=$indstc;
$_M['coupontypes']=array(
    1=>'代金券',
    2=>'折扣券',
    3=>'兑换券',
);
$_M['couponstyles']=array(1=>'#55bd47',2=>'#3d78da',3=>'#f08500',4=>'#cf3e36');
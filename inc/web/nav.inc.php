<?php 
require(MODULE_ROOT.'/inc/menus.php');
global $_W,$_GPC,$_M;
$uniacid=$_W['uniacid'];
$op=in_array($_GPC['op'],array('display','savenav'))?$_GPC['op']:'display';
if($op=='savenav'){
   $navs=$_GPC['navs'];
   $text='<?php $menus='.var_export($navs,true).';';
   if(false!==fopen(MODULE_ROOT.'/inc/menus_'.$uniacid.'.php','w+')){
       file_put_contents(MODULE_ROOT.'/inc/menus_'.$uniacid.'.php',$text);
       message('保存成功',referer(),'succes');
   }else{
       message('保存失败');
   };
}
if($op=='display'){
   if (is_file(MODULE_ROOT.'/inc/menus_'.$uniacid.'.php')){
       require MODULE_ROOT.'/inc/menus_'.$uniacid.'.php';
   }else{
       require MODULE_ROOT.'/inc/menus.php';
   }
   $sortarr=array_map('array_shift',$menus);
   array_multisort($sortarr,SORT_DESC,$menus);
   include $this->template('nav');
}
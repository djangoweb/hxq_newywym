<?php $menus=array (
    0 => 
    array (
      'sort' => 3,
      'name' => '活动详情',
      'type' => 'url',
      'action' => './index.php?i=[i]&c=entry&do=index&m=hxq_newywym&actid=[actid]',
      'icon' => 'icon iconhome',
      'disable' =>0,
    ),
    1 => 
    array (
      'sort' => 2,
      'name' => '扫码',
      'type' => 'js',
      'action' => 'wx.scanQRCode({needResult: 0})',
      'icon' => 'icon iconscan',
      'disable' => 0,
    ),
    2 => 
    array (
      'sort' => 1,
      'name' => '公众号',
      'type' => 'js',
      'action' => '$(\'#followlayer\').showlayer(\'\',\'\',false,true,\'middle\');',
      'icon' => 'icon iconmp',
      'disable' => 0,
    )
);
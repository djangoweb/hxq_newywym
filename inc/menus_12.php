<?php $menus=array (
  0 => 
  array (
    'sort' => '3',
    'icon' => 'icon iconhome',
    'name' => '活动详情',
    'type' => 'url',
    'action' => './index.php?i=[i]&c=entry&do=index&m=hxq_newywym&actid=[actid]',
  ),
  1 => 
  array (
    'sort' => '2',
    'icon' => 'icon iconscan',
    'name' => '扫码',
    'type' => 'js',
    'action' => 'require([&#039;weixin&#039;],function(wx){wx.config({debug:jssdkconfig.debug,appId:jssdkconfig.appId,timestamp:jssdkconfig.timestamp,nonceStr:jssdkconfig.nonceStr,signature:jssdkconfig.signature,jsApiList:jssdkconfig.jsApiList});wx.ready(function(){wx.scanQRCode({needResult: 0})})})',
  ),
  2 => 
  array (
    'sort' => '1',
    'icon' => 'icon iconmp',
    'name' => '公众号',
    'type' => 'js',
    'action' => 'require([&#039;main&#039;],function(main){$(&#039;#followlayer&#039;).showlayer(&#039;&#039;,&#039;&#039;,false,true,&#039;middle&#039;);})',
  ),
);
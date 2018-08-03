<?php
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_award')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(5) unsigned NOT NULL,
  `actid` int(10) unsigned NOT NULL,
  `sort` tinyint(1) unsigned NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `type` int(5) unsigned NOT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `redpack` decimal(10,2) NOT NULL,
  `credit1` int(5) unsigned NOT NULL DEFAULT '0',
  `credit2` int(5) unsigned NOT NULL DEFAULT '0',
  `rate` int(5) unsigned NOT NULL DEFAULT '0',
  `total` int(5) unsigned not NULL DEFAULT '0',
  `daytotal`  int(5) unsigned NOT NULL DEFAULT '0',
  `dayawarded`  varchar(1000) NOT NULL DEFAULT '',
  `awarded`  int(10) unsigned NOT NULL DEFAULT '0',
  `verifway`  tinyint(1) unsigned NOT NULL DEFAULT '0',
  `disable`  tinyint(1) unsigned NOT NULL DEFAULT '0',
  `showtitle`  tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_cate')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `actid` int(10) NOT NULL default '0',
  `pdtid` int(10) not NULL DEFAULT '0',
  `name`  varchar(200) NOT NULL DEFAULT '0',
  `qdid` int(10) unsigned NOT NULL default '0',
  `total` int(10) unsigned NOT NULL default '0',
  `mintk` int(10)  unsigned not NULL DEFAULT '0',
  `maxtk` int(10) unsigned not NULL DEFAULT '0',
  `slat` varchar(50)  not NULL DEFAULT '',
  `status` tinyint(10) unsigned not NULL DEFAULT '0',
  `addtime` int(10) unsigned not NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_activity')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `starttime` int(10) not NULL DEFAULT '0',
  `endtime`  int(10) NOT NULL DEFAULT '0',
  `daymaxawarded` int(10) unsigned NOT NULL default '0',
  `maxawarded` int(10) unsigned NOT NULL default '0',
  `jj` varchar(500)  not NULL DEFAULT '',
  `status` tinyint(1) unsigned not NULL DEFAULT '0',
  `addtime` int(10) unsigned not NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_fan')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `oauth_openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `sex` tinyint(1) NOT NULL default '0',
  `province` varchar(50) NOT NULL,
  `country` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `faninfoarr`  varchar(500) NOT NULL DEFAULT '',
  `daylottery`  varchar(1000) NOT NULL DEFAULT '',
  `lottery`  int(10) NOT NULL DEFAULT '0',
  `dayawarded`  varchar(1000) NOT NULL DEFAULT '',
  `awarded`  int(10) NOT NULL DEFAULT '0',
  `daycredit`  varchar(1000) NOT NULL DEFAULT '',
  `credit`  varchar(1000) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");


pdo_query('CREATE TABLE if not exists '.tablename('hxq_newywym_token')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pdtid` int(10) not null default '0',
  `cateid` int(10) not null default '0',
  `awardid` int(10) not null default '0',
  `redpack` int(10) not null default '0',
  `openid` varchar(50)   not null default '',
  `oauth_openid` varchar(50)   not null default '',
  `nickname` varchar(50) not null default '',
  `avatar` varchar(200) not null default '',
  `tk` varchar(50) not null default '',
  `isgrant` tinyint(1) not null default '0',
  `addtime` int(10)   not null default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");


if(!pdo_indexexists('hxq_newywym_fan', 'uniacid_actid_oauth_openid')) {
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add index uniacid_actid_oauth_openid (uniacid,actid,oauth_openid)");
}
if (!pdo_fieldexists('hxq_newywym_award','description')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  description  varchar(2000)  not null default ''");
}
if(!pdo_fieldexists('hxq_newywym_award', 'verifers')) {
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add verifers  varchar(500) NOT NULL DEFAULT ''");
}
if(!pdo_fieldexists('hxq_newywym_award', 'couponstarttime')) {
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add couponstarttime  int(10) NOT NULL DEFAULT '0'");
}
if(!pdo_fieldexists('hxq_newywym_award', 'couponendtime')) {
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add couponendtime  int(10) NOT NULL DEFAULT '0'");
}

if (!pdo_fieldexists('hxq_newywym_award','couponcardid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  couponcardid  varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','cateid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  cateid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_fan','cateid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add cateid  int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','starttime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  starttime   varchar(500)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','endtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  endtime   varchar(500)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','addtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  addtime   varchar(500)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','status')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  status   varchar(500)  not null default ''");
}
if (pdo_fieldexists('hxq_newywym_cate','desc')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." change `desc`  description   text not null default ''");
}

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_product')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(5) unsigned NOT NULL,
  `title` varchar(50) not NULL DEFAULT '',
  `bzlx` varchar(50) not NULL DEFAULT '',
  `thumbs` varchar(500) not NULL DEFAULT '',
  `description`  varchar(2000) not NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if (pdo_fieldexists('hxq_newywym_product','title')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." change `title` `name` varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_product','thumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `thumb`  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_product','price')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add price  decimal(7,2) not null default '0.0'");
}
if (!pdo_fieldexists('hxq_newywym_product','showprice')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add showprice  decimal(7,2) not null default '0.0'");
}

if (!pdo_fieldexists('hxq_newywym_product','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add bizid int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_product','bizname')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add bizname varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_product','isshow')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add isshow  tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_fan','productid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add `productid`  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_fan','pdtid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add `pdtid`  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_fan','batid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add `batid`  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_award','pdtid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add `pdtid`  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','pdtid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add `pdtid`  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_cate','mintk')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  mintk   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','maxtk')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  maxtk   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','total')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  total   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','totalused')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  totalused   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','slat')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  slat   varchar(10)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  actid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  actid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  actid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_fan','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fan')." add  actid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','thumbs')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  thumbs   varchar(2000)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','sharethumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  sharethumb   varchar(200)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','sharetitle')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  sharetitle   varchar(200)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','sharedesc')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  sharedesc   varchar(200)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','focusfollow')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  focusfollow   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','getfaninfo')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  getfaninfo   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','faninfoarr')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  faninfoarr   varchar(500)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','total_num')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  total_num   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','starttime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  starttime   int(10) unsigned  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','endtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  endtime   int(10) unsigned not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','thumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  thumb   varchar(200) not null default ''");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_cash')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL default '0',
  `cateid` int(10) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `oauth_openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `awardedids` tinyint(1) NOT NULL default '0',
  `totalredpack` varchar(50) NOT NULL,
  `addtime` varchar(10) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_token','addip')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  addip  varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','total_num')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  total_num  int(10)  not null default '0'");
}
if (pdo_fieldexists('hxq_newywym_token','redpack')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." change  redpack redpack decimal(5,2)  not null default '0.0'");
}
if (!pdo_fieldexists('hxq_newywym_token','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add type  int(10) unsigned not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','err_code')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add err_code  varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','send_listid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add send_listid  varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','cashtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add cashtime  int(10) unsigned not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','gcid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add gcid  int(10) unsigned not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_product','hits')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add hits  int(10) unsigned not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','ucthumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add ucthumb  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','province')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  province   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','city')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  city   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','district')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  district   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','sex')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  sex   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','scantimes')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add  scantimes   int(50)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','rptype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  rptype   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','rptype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  rptype   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','gcredit1')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  gcredit1   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','credit1')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  credit1   int(10)  not null default '0'");
}
pdo_query('CREATE TABLE if not exists '.tablename('hxq_newywym_give')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pdtid` int(10) not null default '0',
  `cateid` int(10) not null default '0',
  `redpack` decimal(6,2) not null default '0.0',
  `credit1` int(10) not null default '0',
  `openid` varchar(50)   not null default '',
  `oauth_openid` varchar(50)   not null default '',
  `nickname` varchar(50) not null default '',
  `avatar` varchar(200) not null default '',
  `tk` varchar(50) not null default '',
  `isgrant` tinyint(1) not null default '0',
  `addtime` int(10)   not null default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_give','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  actid   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_give','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  type   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_give','cashtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  cashtime   int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_give','sex')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  sex   tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_give','province')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  province   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','city')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  city   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','district')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  district   varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','status')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add  status tinyint(1)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','district')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  district varchar(50)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','createtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add  createtime int(10)  not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','err_code_des')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add err_code_des varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','credit1')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add credit1 int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','description')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  description text not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','dthumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  dthumb varchar(200)  not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','faq')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  faq text not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','fthumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  fthumb varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','hpctnt')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add hpctnt text not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','hpthumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add hpthumb varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','isgrant')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add isgrant tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','credit1')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add  credit1 int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','maxtoken')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  maxtoken int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','granttype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add  granttype tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','enable')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add   enable  tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','street')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add   street  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','street')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add   street  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','streetnum')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add   streetnum  varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_give','streetnum')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_give')." add   streetnum  varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','ltype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   ltype  tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','lnum')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   lnum  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','title')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add   title  varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','dscan')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add   dscan  tinyint(1) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_cate','dscanwarning')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add   dscanwarning  tinyint(1) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_setting')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL default '0',
  `type` varchar(50)  NOT NULL default '',
  `replytitle`  varchar(200) NOT NULL DEFAULT '',
  `replythumb`  varchar(200) NOT NULL DEFAULT '',
  `replydescription`  varchar(200) NOT NULL DEFAULT '',
  `updatetime` int(10) unsigned not NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_setting','rid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_setting')." add   rid int(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','shareoutlink')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   shareoutlink varchar(500) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','blacklist')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   blacklist tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','blacklistgroups')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   blacklistgroups varchar(500) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_cate','forcegetlocation')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add   forcegetlocation tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','weixincouponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add   weixincouponid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','weixincouponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add   weixincouponid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','mycloselinks')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add   mycloselinks varchar(500) not null default ''");
}

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_ch')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL default '0',
  `actid` int(10)  NOT NULL default '0',
  `actname`  varchar(200) NOT NULL DEFAULT '',
  `cateid` int(10)  NOT NULL default '0',
  `catename`  varchar(200) NOT NULL DEFAULT '',
  `district`  varchar(200) NOT NULL DEFAULT '',
  `scandistrict`  varchar(200) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned not NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_ch','oauth_openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_ch')." add   oauth_openid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_ch','nickname')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_ch')." add   nickname varchar(500) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','verif')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add verif tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','veriftime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add veriftime int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','veriferid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add veriferid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','verifer_oauth_openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add verifer_oauth_openid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','verifernickname')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add verifernickname varchar(50) not null default ''");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_verifer')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL default '0',
  `actid` int(10)  NOT NULL default '0',
  `name`  varchar(200) NOT NULL DEFAULT '',
  `mobile`  varchar(200) NOT NULL DEFAULT '',
  `nickname`  varchar(200) NOT NULL DEFAULT '',
  `oauth_openid`  varchar(200) NOT NULL DEFAULT '',
  `disable`  tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned not NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if (!pdo_fieldexists('hxq_newywym_verifer','disable')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_verifer')." add disable tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','swprice')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add swprice int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','swprice')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add swprice int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','cashtip')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add cashtip varchar(500) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','page')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add page varchar(8000) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','tokenids')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add tokenids varchar(500) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_activity','qdid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add qdid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','district')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add district varchar(500) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','createtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add createtime int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','dscanwarning')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add dscanwarning tinyint(1) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_slat')." (
  `slat` varchar(50)  NOT NULL default '') ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if (!pdo_fieldexists('hxq_newywym_award','cateid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add cateid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_ch','tk')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_ch')." add tk varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','getcashleast')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add getcashleast int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','isget')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add isget tinyint(1) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_click')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
    `actid` int(10) NOT NULL DEFAULT '0',
    `type` tinyint(10) NOT NULL DEFAULT '0',
    `cateid` int(10) NOT NULL DEFAULT '0',
    `oauth_openid` varchar(200) NOT NULL DEFAULT '',
    `total` int(10) NOT NULL DEFAULT '0',
    `nickname` varchar(200) NOT NULL DEFAULT '',
    `addtime` int(10) NOT NULL DEFAULT '0',
    `addip` varchar(200) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
pdo_query("CREATE TABLE if not exists".tablename('hxq_newywym_share')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
 `actid` int(10) NOT NULL DEFAULT '0',
 `cateid` int(10) NOT NULL DEFAULT '0',
 `oauth_openid` varchar(200) NOT NULL DEFAULT '',
 `nickname` varchar(200) NOT NULL DEFAULT '',
 `total` int(10) NOT NULL DEFAULT '0',
 `addtime` int(10) NOT NULL DEFAULT '0',
 `addip` varchar(200) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_click','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_click')." add type tinyint(1) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_coupon')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
    `actid` int(10) NOT NULL DEFAULT '0',
    `type` int(10) NOT NULL DEFAULT '0',
    `name` varchar(50) NOT NULL DEFAULT '',
    `style` varchar(50) NOT NULL DEFAULT '',
    `total` int(10) NOT NULL DEFAULT '0',
    `addtime` int(10) NOT NULL DEFAULT '0',
    `addip` varchar(200) NOT NULL DEFAULT '0',
    `coupontype` varchar(200) NOT NULL DEFAULT '',
    `starttime` int(10) NOT NULL DEFAULT '0',
    `endtime` int(10) NOT NULL DEFAULT '0',
    `couponid` varchar(500) NOT NULL DEFAULT '',
    `requirement` varchar(200) NOT NULL DEFAULT '',
    `thumb` varchar(200) NOT NULL DEFAULT '',
    `perfan` int(10) NOT NULL DEFAULT '0',
    `disabled` tinyint(1) NOT NULL DEFAULT '0',
    `biz` varchar(50) NOT NULL DEFAULT '',
    `description` varchar(200) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_coupongrant')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
    `actid` int(10) NOT NULL DEFAULT '0',
    `cid` varchar(200) NOT NULL DEFAULT '',
    `oauth_openid` varchar(50) NOT NULL DEFAULT '',
    `nickname` varchar(200) NOT NULL DEFAULT '',
    `verifid` int(10) NOT NULL DEFAULT '0',
    `veriftime` int(10) NOT NULL DEFAULT '0',
    `verifer` varchar(50) NOT NULL DEFAULT '',
    `addtime` int(10) NOT NULL DEFAULT '0',
    `addip` varchar(200) NOT NULL DEFAULT '0',
    `avatar` varchar(200) NOT NULL DEFAULT '',
    `cateid` int(50) NOT NULL DEFAULT '0',
    `couponid` varchar(50) NOT NULL DEFAULT '',
    `code` varchar(50) NOT NULL DEFAULT '',
    `openid` varchar(50) NOT NULL DEFAULT '',
    `deleted` tinyint(1) NOT NULL DEFAULT '0',
    `type` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_award','requirement')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add requirement varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','biz')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add biz varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','code')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add code varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','verifer')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add verifer varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','veriftime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add veriftime int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_award','style')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add style varchar(10) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','weixincouponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add weixincouponid varchar(5) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','deleted')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add deleted tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add openid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','istx')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add istx decimal(6,2) not null default '0.0'");
}
if (pdo_fieldexists('hxq_newywym_token','istx')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." change istx istx tinyint(1) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_biz')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
    `type` varchar(200) NOT NULL DEFAULT '',
    `name` varchar(50) NOT NULL DEFAULT '',
    `thumb` varchar(200) NOT NULL DEFAULT '',
    `logo` varchar(200) NOT NULL DEFAULT '',
    `address` varchar(200) NOT NULL DEFAULT '',
    `phone` varchar(20) NOT NULL DEFAULT '',
    `special_service` varchar(200) NOT NULL DEFAULT '',
    `biz_hours` varchar(20) NOT NULL DEFAULT '',
    `description` varchar(200) NOT NULL DEFAULT '',
    `addtime` int(10) NOT NULL DEFAULT '0',
    `addip` varchar(200) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_coupon','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_activity','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_coupongrant','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_biz','verifpwd')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add verifpwd varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','couponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add couponid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_coupongrant','come')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add come tinyint(1) not null default '1'");
}
if (!pdo_fieldexists('hxq_newywym_coupongrant','weixincouponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add weixincouponid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_award','couponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_award')." add couponid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_coupon','address')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add address varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_coupon','logo')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add logo varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_coupon','phone')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add phone varchar(50) not null default ''");
}
if (pdo_fieldexists('hxq_newywym_coupon','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." change type type varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_coupon','value')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add value varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_coupongrant','tokenid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add tokenid int(10) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_lock')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `tk` int(10) unsigned NOT NULL default '0',
        `islock` tinyint(1) unsigned NOT NULL default '0',
    `addtime` int(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
pdo_query('CREATE TABLE if not exists '.tablename('hxq_newywym_scan')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL default '0',
  `actid` int(10) unsigned NOT NULL default '0',
  `cateid` int(10) not null default '0',
  `awardid` int(10) not null default '0',
  `openid` varchar(50)   not null default '',
  `oauth_openid` varchar(50)   not null default '',
  `type` int(10) not null default '0',
  `title` varchar(50) not null default '',
  `redpack` decimal(6,2) not null default '0.0',
  `credit1` int(10) not null default '0',
  `couponid`   int(10) not null default '0',
   `total_num` int(10) not null default '0',
  `nickname` varchar(50) not null default '',
  `avatar` varchar(250) not null default '',
  `tk` varchar(50) not null default '', 
  `isgrant` tinyint(1) not null default '0',
    `thumb` varchar(200)   not null default '',
  `addip` varchar(50) not null default '',
  `addtime` int(10)   not null default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_scan','uniacid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add uniacid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','actid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add actid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','cateid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add cateid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','awardid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add awardid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add openid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_scan','oauth_openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add oauth_openid varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_scan','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add type int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','title')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add title varchar(50) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','redpack')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add redpack decimal(6,2) not null default '0.0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','credit1')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add credit1  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','total_num')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add total_num int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','nickname')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add nickname  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_scan','tk')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add tk  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','avatar')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add avatar  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_scan','couponid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add couponid  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','isgrant')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add isgrant  tinyint(1) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','thumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add thumb  varchar(200) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_scan','addip')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add addip  varchar(50) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_scan','addtime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add addtime  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_scan','err_code')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add err_code  varchar(200) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_scan','err_code_des')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add err_code_des  varchar(200) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_scan','send_listid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add send_listid  varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_token','scanid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add scanid  int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_token','thumb')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add thumb  varchar(200) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_activity','maxperfanscan')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add maxperfanscan  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_activity','titleshowtype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_activity')." add titleshowtype  tinyint(1) not null default '0'");
}


if (pdo_fieldexists('hxq_newywym_coupongrant','oauth_openid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." change oauth_openid oauth_openid varchar(50) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_coupongrant','name')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add name  varchar(50) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_coupon','isshow')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupon')." add `isshow`  tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_product','isshow')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `isshow`  tinyint(1) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_scan','updatetime')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add `updatetime` int(10) not null default '0'");
}

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_reply')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned not null DEFAULT '0',
  `rid` int(10) unsigned not null DEFAULT '0',
  `actid` int(10) unsigned not null DEFAULT '0',
  `addtime` int(10)  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if (!pdo_fieldexists('hxq_newywym_reply','keyword')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_reply')." add `keyword` varchar(20) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_biz','indstp')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add indstp  int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_biz','indstc')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add indstc int(10) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_biz','veriftype')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add veriftype tinyint(1) not null default '0'");
}

load()->func('file');
if (!file_exists(IA_ROOT.'/ywym_scan.php')){
    file_copy(IA_ROOT.'/addons/hxq_newywym/ywym_scan.php', IA_ROOT.'/ywym_scan.php');
}
if (!pdo_fieldexists('hxq_newywym_biz','thumbs')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add `thumbs`  varchar(2000) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_product','price')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `price`  decimal(6,2) not null default '0.0'");
}
if (!pdo_fieldexists('hxq_newywym_product','goodsprice')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `goodsprice`  decimal(7,2) not null default '0.0'");
}
if (!pdo_fieldexists('hxq_newywym_product','contents')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `contents`  text not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_product','click')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add `click`  int(10) not null default '0'");
}

if (pdo_fieldexists('hxq_newywym_product','description')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." change description description  text not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_token','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_token')." add bizid int(10) not null default '0'");
}


if (!pdo_fieldexists('hxq_newywym_scan','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_scan')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_coupongrant','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_coupongrant')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_share','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_share')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_click','bizid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_click')." add bizid int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_biz','lng')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add lng varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_biz','lat')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_biz')." add lat varchar(20) not null default ''");
}
if (!pdo_fieldexists('hxq_newywym_product','disabled')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_product')." add disabled tinyint(1) not null default '0'");
}
$slat=pdo_fetch('select * from '.tablename('hxq_newywym_slat'));
if (empty($slat)){
    $strslat=random(32);
    pdo_insert('hxq_newywym_slat',array('slat'=>$strslat));
}

pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_noticelog')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned not null DEFAULT '0',
  `actid` int(10) unsigned not null DEFAULT '0',
  `bizid` int(10) unsigned not null DEFAULT '0',
  `openid` varchar(50) not null DEFAULT '',
  `oauth_openid` varchar(50) not null DEFAULT '',
  `nickname` varchar(20) not null DEFAULT '',
  `content` varchar(200) not null DEFAULT '',
  `isread` tinyint(1) unsigned not null DEFAULT '0',
  `addtime` int(10)  unsigned not null DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
if (!pdo_fieldexists('hxq_newywym_noticelog','gid')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_noticelog')." add gid int(10) not null default '0'");
}
pdo_query("CREATE TABLE if not exists ".tablename('hxq_newywym_fisnaward')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(5) unsigned NOT NULL,
  `cateid` int(10) unsigned NOT NULL,
    `actid` int(10) unsigned NOT NULL,
    `bizid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `type` int(5) unsigned NOT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `redpack` decimal(10,2) NOT NULL,
  `credit1` int(5) unsigned NOT NULL DEFAULT '0',
  `credit2` int(5) unsigned NOT NULL DEFAULT '0',
  `total` int(5) unsigned not NULL DEFAULT '0',
  `couponid` int(10) unsigned NOT NULL default '0',
  `disable`  tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

if (!pdo_fieldexists('hxq_newywym_cate','fission')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add fission tinyint(1) not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_cate','fissionnum')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add fissionnum int(10) not null default '0'");
}
if (!pdo_fieldexists('hxq_newywym_cate','area')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add area varchar(500) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_cate','iparea')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_cate')." add iparea varchar(500) not null default ''");
}

if (!pdo_fieldexists('hxq_newywym_fisnaward','tokenids')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fisnaward')." add tokenids varchar(200) not null default ''");
}
pdo_query("CREATE TABLE ".tablename('hxq_newywym_fission')." (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
    `actid` int(10) NOT NULL DEFAULT '0',
    `cateid` int(10) NOT NULL DEFAULT '0',
    `oauth_openid` varchar(200) NOT NULL DEFAULT '',
    `nickname` varchar(200) NOT NULL DEFAULT '',
    `total` int(10) NOT NULL DEFAULT '0',
    `addtime` int(10) NOT NULL DEFAULT '0',
    `addip` varchar(200) NOT NULL DEFAULT '0',
    `bizid` int(10) NOT NULL DEFAULT '0',
    `token` int(10) NOT NULL DEFAULT '0',
    `enable` tinyint(1) NOT NULL DEFAULT '0',
    `isgrant` tinyint(1) NOT NULL DEFAULT '0',
    `pid` int(10) NOT NULL DEFAULT '0',
    `depth` int(10) NOT NULL DEFAULT '0',
    `awardid` int(10) NOT NULL DEFAULT '0',
    `couponid` int(10) NOT NULL DEFAULT '0',
    `redpack` decimal(6,2) NOT NULL DEFAULT '0.0',
    `credit1` int(10) NOT NULL DEFAULT '0'
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if (!pdo_fieldexists('hxq_newywym_fission','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_fission')." add type tinyint(1) unsigned not null default '0'");
}

if (!pdo_fieldexists('hxq_newywym_reply','type')){
    pdo_query("ALTER TABLE ".tablename('hxq_newywym_reply')." add type tinyint(1) unsigned not null default '0'");
}

pdo_query('CREATE TABLE if not exists '.tablename('hxq_newywym_fisnawarded')." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `actid` int(10) not null default '0',
  `cateid` int(10) not null default '0',
  `bizid` int(10) not null default '0',
  `fid` int(10) not null default '',
  `awardid` int(10) not null default '0',
  `redpack` int(10) not null default '0',
  `couponid` int(10) not null default '',
  `credit1` int(10) not null default '',
  `openid` varchar(50)   not null default '',
  `oauth_openid` varchar(50)   not null default '',
  `nickname` varchar(50) not null default '',
  `avatar` varchar(200) not null default '',
  `addtime` int(10)   not null default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

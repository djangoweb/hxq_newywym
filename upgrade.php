<?php 
$sql="CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `daymaxawarded` int(10) unsigned NOT NULL DEFAULT '0',
  `maxawarded` int(10) unsigned NOT NULL DEFAULT '0',
  `jj` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `thumbs` varchar(2000) NOT NULL DEFAULT '',
  `sharethumb` varchar(200) NOT NULL DEFAULT '',
  `sharetitle` varchar(200) NOT NULL DEFAULT '',
  `sharedesc` varchar(200) NOT NULL DEFAULT '',
  `focusfollow` tinyint(1) NOT NULL DEFAULT '0',
  `getfaninfo` tinyint(1) NOT NULL DEFAULT '0',
  `faninfoarr` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `ucthumb` varchar(200) NOT NULL DEFAULT '',
  `rptype` tinyint(1) NOT NULL DEFAULT '0',
  `gcredit1` tinyint(1) NOT NULL DEFAULT '0',
  `credit1` int(10) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `dthumb` varchar(200) NOT NULL DEFAULT '',
  `faq` text NOT NULL,
  `fthumb` varchar(200) NOT NULL DEFAULT '',
  `hpctnt` text NOT NULL,
  `hpthumb` varchar(200) NOT NULL DEFAULT '',
  `maxtoken` int(10) NOT NULL DEFAULT '0',
  `granttype` tinyint(1) NOT NULL DEFAULT '0',
  `ltype` tinyint(1) NOT NULL DEFAULT '0',
  `lnum` int(10) NOT NULL DEFAULT '0',
  `shareoutlink` varchar(500) NOT NULL DEFAULT '',
  `blacklist` tinyint(1) NOT NULL DEFAULT '0',
  `blacklistgroups` varchar(500) NOT NULL DEFAULT '',
  `mycloselinks` varchar(500) NOT NULL DEFAULT '',
  `cashtip` varchar(500) NOT NULL DEFAULT '',
  `page` varchar(8000) NOT NULL DEFAULT '',
  `qdid` int(10) NOT NULL DEFAULT '0',
  `district` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `dscanwarning` tinyint(1) NOT NULL DEFAULT '0',
  `getcashleast` int(10) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `maxperfanscan` int(10) NOT NULL DEFAULT '0',
  `titleshowtype` tinyint(1) NOT NULL DEFAULT '0',
  `fission` tinyint(1) NOT NULL DEFAULT '0',
  `fisnawardtype` tinyint(1) NOT NULL DEFAULT '0',
  `fissionnum` int(10) NOT NULL DEFAULT '0',
  `fisnarea` varchar(200) NOT NULL DEFAULT '',
  `keeptimeline` tinyint(1) NOT NULL DEFAULT '0',
  `keepprice` varchar(20) NOT NULL DEFAULT '',
  `keepclicktimes` int(10) NOT NULL DEFAULT '0',
  `plusability` tinyint(1) NOT NULL DEFAULT '0',
  `keeparea` varchar(50) NOT NULL DEFAULT '',
  `fisniparea` varchar(500) NOT NULL DEFAULT '',
  `keepiparea` varchar(500) NOT NULL DEFAULT '',
  `fissiontype` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_award` (
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
  `total` int(5) unsigned NOT NULL DEFAULT '0',
  `daytotal` int(5) unsigned NOT NULL DEFAULT '0',
  `dayawarded` varchar(1000) NOT NULL DEFAULT '',
  `awarded` int(10) unsigned NOT NULL DEFAULT '0',
  `verifway` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  `description` varchar(2000) NOT NULL DEFAULT '',
  `verifers` varchar(500) NOT NULL DEFAULT '',
  `couponstarttime` int(10) NOT NULL DEFAULT '0',
  `couponendtime` int(10) NOT NULL DEFAULT '0',
  `couponcardid` varchar(50) NOT NULL DEFAULT '',
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `total_num` tinyint(1) NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `scantimes` int(50) NOT NULL DEFAULT '0',
  `weixincouponid` varchar(50) NOT NULL DEFAULT '',
  `swprice` int(10) NOT NULL DEFAULT '0',
  `tokenids` varchar(500) NOT NULL DEFAULT '',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `requirement` varchar(50) NOT NULL DEFAULT '',
  `biz` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(10) NOT NULL DEFAULT '',
  `couponid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=365 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_biz` (
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
  `verifpwd` varchar(20) NOT NULL DEFAULT '',
  `indstp` int(10) NOT NULL DEFAULT '0',
  `indstc` int(10) NOT NULL DEFAULT '0',
  `veriftype` tinyint(1) NOT NULL DEFAULT '0',
  `thumbs` varchar(2000) NOT NULL DEFAULT '',
  `lng` varchar(20) NOT NULL DEFAULT '',
  `lat` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `cateid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `oauth_openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `awardedids` tinyint(1) NOT NULL DEFAULT '0',
  `totalredpack` varchar(50) NOT NULL,
  `addtime` varchar(10) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `actid` int(10) NOT NULL DEFAULT '0',
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '0',
  `qdid` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `mintk` int(10) unsigned NOT NULL DEFAULT '0',
  `maxtk` int(10) unsigned NOT NULL DEFAULT '0',
  `slat` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` varchar(500) NOT NULL DEFAULT '',
  `endtime` varchar(500) NOT NULL DEFAULT '',
  `totalused` int(10) NOT NULL DEFAULT '0',
  `district` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `dscan` tinyint(1) NOT NULL DEFAULT '0',
  `dscanwarning` tinyint(1) NOT NULL DEFAULT '0',
  `forcegetlocation` tinyint(1) NOT NULL DEFAULT '0',
  `fission` tinyint(1) NOT NULL DEFAULT '0',
  `fissionnum` int(10) NOT NULL DEFAULT '0',
  `area` varchar(500) NOT NULL DEFAULT '',
  `iparea` varchar(500) NOT NULL DEFAULT '',
  `abstract` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_ch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `actname` varchar(200) NOT NULL DEFAULT '',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `catename` varchar(200) NOT NULL DEFAULT '',
  `district` varchar(200) NOT NULL DEFAULT '',
  `scandistrict` varchar(200) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(500) NOT NULL DEFAULT '',
  `tk` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_click` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `oauth_openid` varchar(200) NOT NULL DEFAULT '',
  `total` int(10) NOT NULL DEFAULT '0',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `addip` varchar(200) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `sharefrom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47740 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `total` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `addip` varchar(200) NOT NULL DEFAULT '0',
  `coupontype` varchar(200) NOT NULL DEFAULT '',
  `requirement` varchar(200) NOT NULL DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `couponid` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `perfan` int(10) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `biz` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `address` varchar(200) NOT NULL DEFAULT '',
  `logo` varchar(200) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_coupongrant` (
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
  `name` varchar(50) NOT NULL DEFAULT '',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `come` tinyint(1) NOT NULL DEFAULT '1',
  `weixincouponid` varchar(50) NOT NULL DEFAULT '',
  `tokenid` int(10) NOT NULL DEFAULT '0',
  `veriferid` int(10) NOT NULL DEFAULT '0',
  `fid` int(10) NOT NULL DEFAULT '0',
  `scanid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6844 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_fan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `oauth_openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL,
  `country` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `faninfoarr` varchar(500) NOT NULL DEFAULT '',
  `daylottery` varchar(1000) NOT NULL DEFAULT '',
  `lottery` int(10) NOT NULL DEFAULT '0',
  `dayawarded` varchar(1000) NOT NULL DEFAULT '',
  `awarded` int(10) NOT NULL DEFAULT '0',
  `daycredit` varchar(1000) NOT NULL DEFAULT '',
  `credit` varchar(1000) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `productid` int(10) NOT NULL DEFAULT '0',
  `scan` int(10) NOT NULL DEFAULT '0',
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `batid` int(10) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid_actid_oauth_openid` (`uniacid`,`actid`,`oauth_openid`)
) ENGINE=InnoDB AUTO_INCREMENT=19201 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_fisnaward` (
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
  `total` int(5) unsigned NOT NULL DEFAULT '0',
  `couponid` int(10) unsigned NOT NULL DEFAULT '0',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL,
  `tokenids` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_fission` (
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
  `redpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `credit1` int(10) NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `idstr` varchar(50) NOT NULL DEFAULT '',
  `scanid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `err_code` varchar(50) NOT NULL DEFAULT '',
  `err_code_des` varchar(50) NOT NULL DEFAULT '',
  `checkarea` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_give` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `redpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `credit1` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `tk` varchar(50) NOT NULL DEFAULT '',
  `isgrant` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `type` int(10) NOT NULL DEFAULT '0',
  `cashtime` int(10) NOT NULL DEFAULT '0',
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `district` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `street` varchar(200) NOT NULL DEFAULT '',
  `streetnum` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_lock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tk` int(10) unsigned NOT NULL DEFAULT '0',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18008 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_noticelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `bizid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `content` varchar(200) NOT NULL DEFAULT '',
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `gid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3705 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `bzlx` varchar(50) NOT NULL DEFAULT '',
  `thumbs` varchar(500) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL DEFAULT '0',
  `showprice` tinyint(10) unsigned NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `bizname` varchar(50) NOT NULL DEFAULT '',
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  `goodsprice` decimal(7,2) NOT NULL DEFAULT '0.00',
  `contents` text NOT NULL,
  `click` int(10) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) DEFAULT '0',
  `keyword` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_scan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `actid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `tk` varchar(50) NOT NULL DEFAULT '',
  `sex` varchar(50) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `district` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `lotterytime` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `street` varchar(200) NOT NULL DEFAULT '',
  `streetnum` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `awardid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `couponid` int(10) NOT NULL DEFAULT '0',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `err_code` varchar(50) NOT NULL DEFAULT '',
  `err_code_des` varchar(200) NOT NULL DEFAULT '',
  `send_listid` varchar(50) NOT NULL DEFAULT '',
  `redpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `credit1` int(10) NOT NULL DEFAULT '0',
  `total_num` int(10) NOT NULL DEFAULT '0',
  `isgrant` tinyint(1) NOT NULL DEFAULT '0',
  `addip` varchar(50) NOT NULL DEFAULT '',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22284 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '',
  `replytitle` varchar(200) NOT NULL DEFAULT '',
  `replythumb` varchar(200) NOT NULL DEFAULT '',
  `replydescription` varchar(200) NOT NULL DEFAULT '',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_share` (
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
  `sharefrom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8992 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_shtmline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `oauth_openid` varchar(200) NOT NULL DEFAULT '',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `addip` varchar(200) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `isgrant` tinyint(1) NOT NULL DEFAULT '0',
  `redpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `clickredpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `scanid` int(10) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  `err_code` varchar(50) NOT NULL DEFAULT '',
  `err_code_desc` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `err_code_des` varchar(50) NOT NULL DEFAULT '',
  `checkarea` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_shtmlineclick` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `oauth_openid` varchar(200) NOT NULL DEFAULT '',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `sid` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `addip` varchar(200) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `redpack` decimal(6,2) NOT NULL DEFAULT '0.00',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `checkarea` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_slat` (
  `slat` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pdtid` int(10) NOT NULL DEFAULT '0',
  `cateid` int(10) NOT NULL DEFAULT '0',
  `awardid` int(10) NOT NULL DEFAULT '0',
  `redpack` decimal(5,2) NOT NULL DEFAULT '0.00',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `tk` varchar(50) NOT NULL DEFAULT '',
  `isgrant` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `addip` varchar(50) NOT NULL DEFAULT '',
  `total_num` int(10) NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `err_code` varchar(50) NOT NULL DEFAULT '',
  `send_listid` varchar(50) NOT NULL DEFAULT '',
  `cashtime` int(10) unsigned NOT NULL DEFAULT '0',
  `gcid` int(10) unsigned NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `district` varchar(50) NOT NULL DEFAULT '',
  `sex` varchar(50) NOT NULL DEFAULT '',
  `rptype` tinyint(1) NOT NULL DEFAULT '0',
  `err_code_des` varchar(200) NOT NULL DEFAULT '',
  `credit1` int(10) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  `street` varchar(200) NOT NULL DEFAULT '',
  `streetnum` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `weixincouponid` varchar(50) NOT NULL DEFAULT '',
  `verif` tinyint(1) NOT NULL DEFAULT '0',
  `veriftime` int(10) NOT NULL DEFAULT '0',
  `veriferid` int(10) NOT NULL DEFAULT '0',
  `verifer_oauth_openid` varchar(50) NOT NULL DEFAULT '',
  `verifernickname` varchar(50) NOT NULL DEFAULT '',
  `swprice` int(10) NOT NULL DEFAULT '0',
  `isget` tinyint(1) NOT NULL DEFAULT '0',
  `code` varchar(50) NOT NULL DEFAULT '',
  `verifer` varchar(50) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `istx` tinyint(1) NOT NULL DEFAULT '0',
  `islock` tinyint(1) NOT NULL DEFAULT '0',
  `bizid` int(10) NOT NULL DEFAULT '0',
  `couponid` int(10) NOT NULL DEFAULT '0',
  `scanid` int(10) NOT NULL DEFAULT '0',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19786 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hxq_newywym_verifer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `actid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `mobile` varchar(200) NOT NULL DEFAULT '',
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `oauth_openid` varchar(200) NOT NULL DEFAULT '',
  `disable` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(50) NOT NULL DEFAULT '',
  `bizid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists("hxq_newywym_activity", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_activity", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_activity", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `starttime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `endtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "daymaxawarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `daymaxawarded` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "maxawarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `maxawarded` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "jj")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `jj` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "status")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `status` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "thumbs")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `thumbs` varchar(2000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "sharethumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `sharethumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "sharetitle")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `sharetitle` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "sharedesc")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `sharedesc` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "focusfollow")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `focusfollow` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "getfaninfo")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `getfaninfo` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "faninfoarr")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `faninfoarr` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "ucthumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `ucthumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "rptype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `rptype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "gcredit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `gcredit1` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `credit1` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "description")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `description` text NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_activity", "dthumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `dthumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "faq")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `faq` text NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fthumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fthumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "hpctnt")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `hpctnt` text NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_activity", "hpthumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `hpthumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "maxtoken")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `maxtoken` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "granttype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `granttype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "ltype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `ltype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "lnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `lnum` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "shareoutlink")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `shareoutlink` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "blacklist")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `blacklist` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "blacklistgroups")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `blacklistgroups` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "mycloselinks")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `mycloselinks` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "cashtip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `cashtip` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "page")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `page` varchar(8000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "qdid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `qdid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `district` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "dscanwarning")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `dscanwarning` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "getcashleast")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `getcashleast` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "maxperfanscan")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `maxperfanscan` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "titleshowtype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `titleshowtype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fission")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fission` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fisnawardtype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fisnawardtype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fissionnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fissionnum` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fisnarea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fisnarea` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "keeptimeline")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `keeptimeline` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "keepprice")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `keepprice` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "keepclicktimes")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `keepclicktimes` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "plusability")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `plusability` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "keeparea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `keeparea` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fisniparea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fisniparea` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "keepiparea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `keepiparea` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_activity", "fissiontype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_activity")." ADD   `fissiontype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_award", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `uniacid` int(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `actid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "sort")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `sort` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "title")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `type` int(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `redpack` decimal(10;");
}
if(!pdo_fieldexists("hxq_newywym_award", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD 2) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `credit1` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "credit2")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `credit2` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "rate")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `rate` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `total` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "daytotal")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `daytotal` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "dayawarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `dayawarded` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "awarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `awarded` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "verifway")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `verifway` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "disable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `disable` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "showtitle")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `showtitle` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_award", "description")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `description` varchar(2000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "verifers")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `verifers` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "couponstarttime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `couponstarttime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "couponendtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `couponendtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "couponcardid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `couponcardid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "total_num")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `total_num` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `starttime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "scantimes")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `scantimes` int(50) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "weixincouponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `weixincouponid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "swprice")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `swprice` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "tokenids")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `tokenids` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_award", "requirement")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `requirement` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "biz")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `biz` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "style")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `style` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_award", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_award")." ADD   `couponid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_biz", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `type` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "logo")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `logo` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "address")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `address` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "phone")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `phone` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "special_service")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `special_service` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "biz_hours")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `biz_hours` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "description")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `addip` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "verifpwd")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `verifpwd` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "indstp")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `indstp` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "indstc")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `indstc` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "veriftype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `veriftype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "thumbs")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `thumbs` varchar(2000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "lng")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `lng` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_biz", "lat")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_biz")." ADD   `lat` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `actid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `cateid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "uid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `avatar` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "awardedids")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `awardedids` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cash", "totalredpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `totalredpack` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `addtime` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cash", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cash")." ADD   `addip` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cate", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_cate", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_cate", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `name` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "qdid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `qdid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `total` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "mintk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `mintk` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "maxtk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `maxtk` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "slat")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `slat` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "status")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `status` tinyint(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `starttime` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `endtime` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "totalused")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `totalused` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `district` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "dscan")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `dscan` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "dscanwarning")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `dscanwarning` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "forcegetlocation")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `forcegetlocation` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "fission")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `fission` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "fissionnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `fissionnum` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "area")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `area` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "iparea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `iparea` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_cate", "abstract")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_cate")." ADD   `abstract` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_ch", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "actname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `actname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "catename")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `catename` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `district` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "scandistrict")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `scandistrict` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `nickname` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_ch", "tk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_ch")." ADD   `tk` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_click", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_click", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_click", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_click", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_click", "sharefrom")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_click")." ADD   `sharefrom` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `type` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "style")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `style` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "coupontype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `coupontype` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "requirement")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `requirement` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `starttime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `endtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `couponid` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "perfan")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `perfan` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "disabled")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `disabled` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "biz")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `biz` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "description")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "address")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `address` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "logo")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `logo` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "phone")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `phone` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "isshow")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `isshow` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "value")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `value` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupon", "enable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupon")." ADD   `enable` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "cid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `cid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "verifid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `verifid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "veriftime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `veriftime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "verifer")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `verifer` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `cateid` int(50) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `couponid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "deleted")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `deleted` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "come")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `come` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "weixincouponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `weixincouponid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "tokenid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `tokenid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "veriferid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `veriferid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "fid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `fid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_coupongrant", "scanid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_coupongrant")." ADD   `scanid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `actid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "uid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `avatar` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "sex")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `sex` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "province")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "country")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `country` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "city")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "faninfoarr")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `faninfoarr` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "daylottery")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `daylottery` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "lottery")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `lottery` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "dayawarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `dayawarded` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "awarded")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `awarded` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "daycredit")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `daycredit` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "credit")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `credit` varchar(1000) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "productid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `productid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "scan")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `scan` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "batid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `batid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fan", "uniacid_actid_oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD   KEY `uniacid_actid_oauth_openid` (`uniacid`;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD `actid`;");
}
if(!pdo_fieldexists("hxq_newywym_fan", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fan")." ADD `oauth_openid`);");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `uniacid` int(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `cateid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `actid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `bizid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "title")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `type` int(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `redpack` decimal(10;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD 2) NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `credit1` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "credit2")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `credit2` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `total` int(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `couponid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "disable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `disable` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_fisnaward", "tokenids")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fisnaward")." ADD   `tokenids` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_fission", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "token")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `token` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "enable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `enable` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "isgrant")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `isgrant` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "pid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `pid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "depth")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `depth` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "awardid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `awardid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `couponid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `redpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_fission", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `credit1` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `type` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "idstr")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `idstr` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "scanid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `scanid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "title")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `title` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "err_code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `err_code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "err_code_des")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `err_code_des` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_fission", "checkarea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_fission")." ADD   `checkarea` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_give", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_give", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `redpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_give", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_give", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `credit1` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `nickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "tk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `tk` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "isgrant")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `isgrant` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `type` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "cashtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `cashtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "sex")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `sex` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "province")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `province` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "city")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `city` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `district` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "status")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_give", "street")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `street` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_give", "streetnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_give")." ADD   `streetnum` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_lock", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_lock")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_lock", "tk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_lock")." ADD   `tk` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_lock", "islock")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_lock")." ADD   `islock` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_lock", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_lock")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `actid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `bizid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `nickname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "content")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `content` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "isread")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `isread` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "gid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `gid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_noticelog", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_noticelog")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_product", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `uniacid` int(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_product", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_product", "bzlx")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `bzlx` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_product", "thumbs")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `thumbs` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_product", "description")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `description` text NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_product", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_product", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_product", "hits")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `hits` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "price")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `price` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "showprice")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `showprice` tinyint(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "bizname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `bizname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_product", "isshow")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `isshow` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "goodsprice")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `goodsprice` decimal(7;");
}
if(!pdo_fieldexists("hxq_newywym_product", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_product", "contents")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `contents` text NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_product", "click")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `click` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_product", "disabled")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_product")." ADD   `disabled` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_reply", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "rid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `actid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `addtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "keyword")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `keyword` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_reply", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_reply")." ADD   `type` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_scan", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_scan", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `nickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "tk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `tk` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "sex")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `sex` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "province")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `province` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "city")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `city` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `district` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "status")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "lotterytime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `lotterytime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "street")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `street` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "streetnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `streetnum` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "awardid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `awardid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "title")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `title` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `couponid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "err_code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `err_code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "err_code_des")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `err_code_des` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "send_listid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `send_listid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `redpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_scan", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `credit1` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "total_num")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `total_num` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "isgrant")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `isgrant` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `addip` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "updatetime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `updatetime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_scan", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_scan")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_setting", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `type` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "replytitle")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `replytitle` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "replythumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `replythumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "replydescription")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `replydescription` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "updatetime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_setting", "rid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_setting")." ADD   `rid` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_share", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_share", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_share", "total")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_share", "sharefrom")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_share")." ADD   `sharefrom` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "isgrant")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `isgrant` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `redpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "clickredpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `clickredpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "scanid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `scanid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "enable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `enable` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "err_code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `err_code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "err_code_desc")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `err_code_desc` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "err_code_des")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `err_code_des` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmline", "checkarea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmline")." ADD   `checkarea` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "sid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `sid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `addip` varchar(200) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `redpack` decimal(6;");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_shtmlineclick", "checkarea")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_shtmlineclick")." ADD   `checkarea` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_slat", "slat")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_slat")." ADD   `slat` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_token", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("hxq_newywym_token", "pdtid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `pdtid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "cateid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `cateid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "awardid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `awardid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "redpack")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `redpack` decimal(5;");
}
if(!pdo_fieldexists("hxq_newywym_token", "")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD 2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists("hxq_newywym_token", "openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `nickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "avatar")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `avatar` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "tk")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `tk` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "isgrant")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `isgrant` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "addip")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `addip` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "total_num")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `total_num` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "type")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `type` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "err_code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `err_code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "send_listid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `send_listid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "cashtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `cashtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "gcid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `gcid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "province")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `province` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "city")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `city` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "district")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `district` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "sex")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `sex` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "rptype")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `rptype` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "err_code_des")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `err_code_des` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "credit1")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `credit1` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "enable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `enable` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "street")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `street` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "streetnum")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `streetnum` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "title")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `title` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "weixincouponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `weixincouponid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "verif")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `verif` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "veriftime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `veriftime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "veriferid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `veriferid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "verifer_oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `verifer_oauth_openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "verifernickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `verifernickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "swprice")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `swprice` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "isget")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `isget` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "code")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `code` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "verifer")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `verifer` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_token", "deleted")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `deleted` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "istx")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `istx` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "islock")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `islock` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "couponid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `couponid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "scanid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `scanid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_token", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_token")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "id")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "uniacid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "actid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `actid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "name")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `mobile` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "nickname")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `nickname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "oauth_openid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `oauth_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "disable")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `disable` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "addtime")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "password")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `password` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("hxq_newywym_verifer", "bizid")) {
 pdo_query("ALTER TABLE ".tablename("hxq_newywym_verifer")." ADD   `bizid` int(10) NOT NULL DEFAULT '0';");
}

 ?>
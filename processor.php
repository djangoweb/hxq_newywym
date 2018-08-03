<?php
/**
 * 
 *
 * @author ysw
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class hxq_newywymModuleProcessor extends WeModuleProcessor {
	public function respond() {  
        global $_W,$GPC;
        $uniacid=$_W['uniacid'];
        $openid=$this->message['from'];
    	$type = $this->message['type'];
    	$content=$this->message['content'];
    	$message=$this->message;
    	$modulename=$this->modulename;
    	$slat=pdo_fetchcolumn('select slat from '.tablename('hxq_newywym_slat'));
    	if ($type=='text'){
            if ($_W['account']['level']==4){
            $preg="/^{$modulename}[0-9]+$/";
            $pregfission="/^{$modulename}__[0-9]+$/";
            if (preg_match($preg,$content)){
                $tk=str_replace($modulename,'',$content);
                $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and mintk<='{$tk}' and maxtk>='{$tk}' ");
                if (empty($cate)){
                    return $this->respText('批次不存在或者已经删除！');
                }
                $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']} ");
                if (empty($act)){
                    return $this->respText('活动不存在或者已经删除！');
                }
                $token=pdo_fetch('select * from '.tablename('hxq_newywym_token')." where uniacid={$_W['uniacid']} and isgrant=1 and tk='$tk'");
                if (!empty($token)){
                    return $this->respText('此二维码已经使用过了！');
                }
                $scene_base=100000000;
                $qrcid=$scene_base+$tk;
                pdo_query('delete from '.tablename('qrcode')." where uniacid={$_W['uniacid']} and model=1 and qrcid={$qrcid}");
                $vtk=substr(md5($tk.$slat),0,8);
                $data[]=array(
                    'title'=>$act['name'],
                    'description'=>'请点击进入领奖页面！',
                    'picurl'=>$_W['attachurl'].$act['sharethumb'],
                    'url'=>$this->buildSiteUrl($this->createmobileurl('index',array('tk'=>$tk,'vtk'=>$vtk))),
                );
                return $this->respNews($data);
            }elseif(preg_match($pregfission,$content)){
                $fid=str_replace($modulename."__",'',$content);
                $fission=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and id={$fid} ");
                if (empty($fission)){
                    return $this->respText('领取记录不存在！');
                }
                if (!empty($fission['isgrant'])){
                    return $this->respText('奖品已被领取！');
                }
                $cate=pdo_fetch('select * from '.tablename('hxq_newywym_cate')." where uniacid={$_W['uniacid']} and id={$fission['cateid']} ");
                if (empty($cate)){
                    return $this->respText('批次不存在或者已经删除！');
                }
                $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid={$_W['uniacid']} and id={$cate['actid']} ");
                if (empty($act)){
                    return $this->respText('活动不存在或者已经删除！');
                }
                $scene_base=200000000;
                $qrcid=$scene_base+$tk;
                pdo_query('delete from '.tablename('qrcode')." where uniacid={$_W['uniacid']} and model=1 and qrcid={$qrcid}");
                $vtk=substr(md5($tk.$slat),0,8);
                $data[]=array(
                    'title'=>$act['name'],
                    'description'=>'请点击进入领奖页面！',
                    'picurl'=>$_W['attachurl'].$act['sharethumb'],
                    'url'=>$this->buildSiteUrl($this->createmobileurl('fission',array('fid'=>$fission['idstr'],'cateid'=>$cate['id']))),
                );
                return $this->respNews($data);
            }
            else{
                return $this->respText('未找到关键字！');
            }
        	}else{
        	    $reply=pdo_fetch('select * from '.tablename('hxq_newywym_reply')." where uniacid=$uniacid and keyword='$content'");
        	    if ($reply){
        	        $act=pdo_fetch('select * from '.tablename('hxq_newywym_activity')." where uniacid=$uniacid and id={$reply['actid']}");
        	        if ($reply['type']==1){
        	            $data[]=array(
        	                'title'=>$act['name'],
        	                'description'=>'请点击进入领奖页面！',
        	                'picurl'=>$_W['attachurl'].$act['sharethumb'],
        	                'url'=>$this->buildSiteUrl($this->createMobileUrl('findscan',array('actid'=>$reply['actid']))),
        	            );
        	        }else{
                        $isawarded=pdo_fetch('select * from '.tablename('hxq_newywym_fission')." where uniacid={$_W['uniacid']} and actid={$reply['actid']} and isgrant=1 and openid='{$openid}'");
        	            if ($isawarded){
        	                return $this->respText('您已经领过奖了，每人限领一次');
        	            }
        	            $data[]=array(
        	                'title'=>$act['name'],
        	                'description'=>'请点击进入领奖页面！',
        	                'picurl'=>$_W['attachurl'].$act['sharethumb'],
        	                'url'=>$this->buildSiteUrl($this->createMobileUrl('findfisn',array('actid'=>$reply['actid']))),
        	            );
        	        }
        	        return $this->respNews($data);
        	    }else{
        	        return $this->respText('关键字不存在');
        	    }
        	}
        }
    	//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	}
}
<?php 	    
function sendtplmessage($data,$tplid,$openids){
    global $_W,$_GPC,$_M;
    load()->classs('account');
    $acnt=Weaccount::create($_W['uniacid']);
    foreach($openids as $openid){
        $r=$acnt->sendTplNotice($openid, $tplid,$data , $url = '', $topcolor = '#FF683F');
        //var_dump($r);
    }
    
}

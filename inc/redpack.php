<?php 
class redpack {
    protected $apiparams=array();
    public $sign;
    public function send($arr) {
			global $_W,$_GPC;
			$data['wxappid'] =$arr['appid'];
			$data['mch_id'] = $arr['mchid'];
			$data['mch_billno'] = $data['mch_id'].date("Ymd",time()).date("His",time()).rand(1111,9999);
			$data['nonce_str'] = $this->createNoncestr();		
			//$data['partner_trade_no'] = random(10). date('Ymd') . random(3);
			$data['send_name'] = $arr['send_name'];
			$data['total_num'] = $arr['total_num'];
			$data['total_amount'] = $arr['total_amount'];
			$data['wishing'] = $arr['wishing'];
			$data['client_ip'] = $arr['client_ip'];
			$data['act_name'] = $arr['act_name'];
			$data['remark'] = $arr['remark'];
			$data['re_openid']= $arr['re_openid'];
			if (!empty($arr['scene_id'])){
			    $data['scene_id']= $arr['scene_id'];
			}
			if ($arr['type']==1){
			    $url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
			}else{
			    $data['amt_type']= $arr['amt_type'];
			    $url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
			}
			unset($arr['type']);/* 
			var_dump($arr);
			exit; */
			$this->apiparams=$arr;
			//var_dump($data);
			$data['sign'] = $this->getSign($data);
			$xml = $this->arrayToXml($data);
			$re = $this->wxHttpsRequestPem($xml,$url);
			if (is_error($re)){
			    return $re;
			}
			$rearr = $this->xmlToArray($re);/* 
			$rearr['sign']=$data['sign'];
			$rearr['data']=$data; */
			return $rearr;
	}
	
	function trimString($value) {
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	
	public function createNoncestr( $length = 32 ) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ ) 
		{
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	
	function formatBizQueryParaMap($paraMap, $urlencode) {
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v) 
		{
			if($urlencode) 
			{
				$v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	public function getSign($Obj) {
	    $apiparams=$this->apiparams;
		foreach ($Obj as $k => $v) 
		{
			$Parameters[$k] = $v;
		}
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		$String = $String."&key=".$apiparams['apikey'];
		$String = md5($String);
		$result_ = strtoupper($String);
		$this->sign=$result_;
		return $result_;
	}
	
	public function arrayToXml($arr) {
		$xml = "<xml>";
		foreach ($arr as $key=>$val) 
		{
			if (is_numeric($val)) 
			{
				$xml.="<".$key.">".$val."</".$key.">";
			}
			else $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		$xml.="</xml>";
		return $xml;
	}
	public function xmlToArray($xml) {
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	
	public function wxHttpsRequestPem( $vars,$url, $second=30,$aHeader=array()) {
		global $_W;
		$apiparams=$this->apiparams;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,MODULE_ROOT.'/cert/'.$_W['uniacid'].'.apiclient_cert.pem');
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,MODULE_ROOT.'/cert/'.$_W['uniacid'].'.apiclient_key.pem');
		if ($apiparams['rootca']){
		   curl_setopt($ch,CURLOPT_CAINFO,MODULE_ROOT.'/cert/'.$_W['uniacid'].'.rootca.pem');
		}
		if( count($aHeader) >= 1 )
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else 
		{
			$error = curl_errno($ch);
			//echo "call faild, errorCode:$error\n";
			curl_close($ch);
			return error(1,"call faild, errorCode:$error");
		}
	}
}
<?php 	    

	    

 	    function zip($files, $file_path, $out_dir, $des_name) {

	        $zip = new ZipArchive;

	        if (file_exists($out_dir . '/' . $des_name)) {

	            @unlink($out_dir . '/' . $des_name);

	        }

	        // 打包操作

	        $result = $zip->open($out_dir . '/' . $des_name, ZipArchive::CREATE);

	        if (true !== $result) {

	            return false;

	        }

	    

	        foreach ($files as $file) {

	            // 添加文件到zip包中

	            $zip->addFile($file_path . '/' . $file, basename($file));

	        }

	        $zip->close();

	    

	        return true;

	    } 

	    function get_shorturl_charset() {

	        static $charset = null;

	        if( $charset !== null )

	            return $charset;

	    

	        if( defined('YOURLS_URL_CONVERT') && in_array( YOURLS_URL_CONVERT, array( 62, 64 ) ) ) {

	            $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	        } else {

	            // defined to 36, or wrongly defined

	            $charset = '0123456789abcdefghijklmnopqrstuvwxyz';

	        }

	    

	        return $charset;

	    }

	    

	    function int2string( $num, $chars = null ) {

	        if( $chars == null )

	            $chars = get_shorturl_charset();

	        $string = '';

	        $len = strlen( $chars );

	        while( $num >= $len ) {

	            $mod = bcmod( $num, $len );

	            $num = bcdiv( $num, $len );

	            $string = $chars[ $mod ] . $string;

	        }

	        $string = $chars[ intval( $num ) ] . $string;

	    

	        return $string;

	    }

	    function string2int( $string, $chars = null ) {

	        if( $chars == null )

	            $chars = get_shorturl_charset();

	        $integer = 0;

	        $string = strrev( $string  );

	        $baselen = strlen( $chars );

	        $inputlen = strlen( $string );

	        for ($i = 0; $i < $inputlen; $i++) {

	            $index = strpos( $chars, $string[$i] );

	            $integer = bcadd( $integer, bcmul( $index, bcpow( $baselen, $i ) ) );

	        }

	    

	        return $integer;

	    }
	    
	    function tag_replace($str) {
	        Global $_GPC;
	       
	        if (preg_match_all("/(\[.+?\])/",$str,$match)){
	            if (is_array($match[1])){
	                foreach($match[1] as $item){
	                    $param=str_replace(array('[',']'),'',$item);
	                    $str=str_replace($item,$_GPC[$param],$str);
	                }
	            }
	            return $str;
	        }
	        return $str;
	    }
	    
	    function js_unescape($str){
	        $ret = '';
	        $len = strlen($str);
	        for ($i = 0; $i < $len; $i++)
	        {
	            if ($str[$i] == '%' && $str[$i+1] == 'u')
	            {
	                $val = hexdec(substr($str, $i+2, 4));
	                if ($val < 0x7f) $ret .= chr($val);
	                else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));                        else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
	                $i += 5;
	            }
	            else if ($str[$i] == '%')
	            {
	                $ret .= urldecode(substr($str, $i, 3));
	                $i += 2;
	            }
	            else $ret .= $str[$i];
	        }
	        return $ret;
	    }


	    function MobileUrl($do, $query = array(), $noredirect = true) {
	        global $_W;
	        $query['do'] = $do;
	        $query['m'] = MODULE_NAME;
	        return murl('entry', $query, $noredirect);
	    }
	    
	    
	    function WebUrl($do, $query = array()) {
	        $query['do'] = $do;
	        $query['m'] = MODULE_NAME;
	        return wurl('site/entry', $query);
	    }
	    
	    function response($params=array()){
	            if (empty($params)){
	                die(json_encode(array(1,'未知的返回')));
	            }else{
	                die(json_encode($params));
	            }
	    }
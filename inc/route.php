<?php 
function route($controller,$isweb=true){
        Global $_W,$_GPC;
        $root=MODULE_ROOT;
		$root .= $isweb ? '/core/web/' :'/core/mobile/';
		include MODULE_ROOT.'/inc/page.php';
		if (is_file($root.$controller.'.php')){
		    $file=$root.$controller.'.php';
		    include $file;
		    $class=ucfirst($controller);
    		$instance = new $class();
    		$r = str_replace("//", "/", trim($_GPC['op'], "/"));
    		$routes = explode('.', $r);
    		if (empty($routes[0])){
    		    $segs=0;
    		}else{
    		    $segs = count($routes);
    		}
    		switch ($segs) {
    			case 0: {
                		$method = "main";
                		$op='display';
                		break;
    				}
    			case 1: {
    			        $method = $routes[0];
    				    break;
    				}
    			case 2: {  
        			    $method = $routes[0];
        			    $op=$routes[1];
    					break;
    				}
    		}
    		if (!method_exists($instance, $method)){
    		    message('找不到方法'.$method);
    		}
    		$instance->$method($op);
    		exit;
		}elseif(is_dir($root.$controller)){ 
		    if ($_W['role']=='founder'){
    	          $file= $root.$controller.'/founder.php';
    	          if (!is_file($file)){
    	              $file= $root.$controller.'/agenter.php';
    	          }
	        }elseif ($_W['role']=='manager'){
    	          $file= $root.$controller.'/agenter.php';
	        }elseif ($_W['role']=='operator'){
    	          $file= $root.$controller.'/operator.php';
	        }
	        if (!is_file($file)){
	            message('找不到控制器');
	        } 
	        include $file;
		    $class=ucfirst($controller);
		    $instance = new $class();
		    $r = str_replace("//", "/", trim($_GPC['op'], "/"));
		    $routes = explode('.', $r);
		    if (empty($routes[0])){
		        $segs=0;
		    }else{
		        $segs = count($routes);
		    }
		    switch ($segs) {
		        case 0: {
		            $method = "main";
		            $op='display';
		            break;
		        }
		        case 1: {
		            $method = $routes[0];
		            break;
		        }
		        case 2: {
		            $method = $routes[0];
		            $op=$routes[1];
		            break;
		        }
		    }
		    if (!method_exists($instance, $method)){
		        message('找不到方法'.$method);
		    }
		    $instance->$method($op);
		    exit;
		}else{
		    message("未找到控制器 {$controller}");
		}
 }
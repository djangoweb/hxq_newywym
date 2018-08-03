<?php
	    require_once MODULE_ROOT.'/inc/mobileinit.php';$this->heckact();
	    $cateid=$_GPC['cateid'];
	    $config=$this->module['config'];
	    $config['company']['description']=htmlspecialchars_decode($config['company']['description']);
	    include $this->template('company');
	


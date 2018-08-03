<?php

class lottery {
	  private $percent=1;
	  private $awards;
	  private $totalrate;
	  public function __construct($awards){
	      foreach ($awards as $key=>$award){
	          $this->totalrate+=$award['total'];
	      }
	  }
	  public function doaward($awards){
		   $rand=mt_rand(1,$this->totalrate);
		   foreach ($awards as $key=>$award){
				if (empty($curv)){$curv=0;}
		        $prevv=$curv;
		        $curv+=$award['total'];
				    //echo $prevv,'-',$curv.' ';
				if ($prevv<$rand&&$rand<=$curv){
					    //echo '{'.$rand.'}';
					$result=$key;
					break;
				}
		   }
		   return $result;
	  }
	  public function saveaward($awards){
	          if (empty($awards)){
	              return array('type'=>100,'title'=>'奖品已被抽完','thumb'=>'images/hxq_newywym/unawarded.png','id'=>0);
	          }
		  	  $result=$this->doaward($awards);
		  	  return $awards[$result];
			  //var_dump($awards[$rownum]);
	  }
}

?>
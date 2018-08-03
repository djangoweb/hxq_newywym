define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
	$.extend(modal.params,params||{})
		 	$('.verif').each(function(){
		 	            $(this).on('click',function(){
                          $('#veriflayer').showlayer('adf','',false)
                  })
	}
	}
	return modal;
})
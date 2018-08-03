define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
	$.extend(modal.params,params||{})
	$('.mp').click(function(){
	$('#followlayer').showlayer('','',false,true,'middle');
	})
	}
	return modal;
})
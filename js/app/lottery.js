define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
		$.extend(modal.params,params||{})
	}
return modal;
})
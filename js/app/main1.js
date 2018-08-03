var currentlayer,timeout,redirect,g_interval,l_interval,layer;
$.ajaxPrefilter(function (options){options.global = true;});
$.ajaxSetup({ cache: false,beforeSend:function(){$('body').append('<div id="layer_ajaxloading" class=layer></div>');$('#layer_ajaxloading').append($('#ajaxloading').clone().show());},complete:function(){$('#layer_ajaxloading').remove();}});
$.fn.extend({showlayer:function(message,redirect,timeout,dark,postion,callback){
	var layerid;window.redirect=redirect,callback?window.layercallback=callback:'';
						if ($("#layer_"+$(this).attr('id')).size()>0){
							if (message&&$(window.currentlayer).find('.message').size()>0)
							{
								$(window.currentlayer).find('.message').html(message);
							}
							if (timeout&&window.redirect){
							$(window.currentlayer).html(message+'<br>.');
							}
							if (timeout>0){
								interval_l(3)
							}
							if (window.currentlayer.find('.img>img').size()>0||window.currentlayer.find('.img>img').attr('src')!=''){
								window.currentlayer.find('.img>img').load(function(){
										window.currentlayer.show();
										if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
										}
								})
								window.currentlayer.find('.img>img').error(function(){
										window.currentlayer.show();
										if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
										}
								})
							}else{
								window.currentlayer.show();
								if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
								}
							}
							if (postion=='middle'){
							window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
							}else if(typeof postion === 'number'){
							window.currentlayer.css('top',postion+'px')
							}else{
							window.currentlayer.css('top',postion)
							}
							layerid=window.currentlayer.attr('id');
							$(window.currentlayer).parent().find('.close_,.submit').off().on('click',function(e){
																			e.preventDefault();
																			if ($(this).hasClass('submit')&&callback){
																				callback(e)
																			}
																			if ($(this).hasClass('close_')){
																					    $('#layer_'+layerid).remove();
																					    if (redirect)
																						location.href=redirect;
																			}
																			})
							if (timeout>0){
							window.timeoutlayer=window.currentlayer;
							}
							return;
						}
						window.currentlayer=this.clone(true)
						if (timeout>0){
							window.timeoutlayer=window.currentlayer;
						}
						if (dark){
							$('body').append('<div id='+"layer_"+window.currentlayer.attr('id')+' class=layerdark></div>')
						}else{
							$('body').append('<div id='+"layer_"+window.currentlayer.attr('id')+' class=layer></div>')
						}
						if (message&&$(window.currentlayer).find('.message').size()>0)
						{
							$(window.currentlayer).find('.message').html(message);
						}
						if (timeout&&window.redirect){
							$(window.currentlayer).html(message+'<br>.');
						}
						if (timeout>0){
							interval_l(3)
						}
						$('#layer_'+window.currentlayer.attr('id')).append(window.currentlayer);
						if (window.currentlayer.find('.img>img').size()>0||(window.currentlayer.find('.img>img').attr('src')!=''&&typeof window.currentlayer.find('.img>img').attr('src')!='undefined')){
								window.currentlayer.find('.img>img').load(function(){
										window.currentlayer.show();
										if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
										}
								})
								window.currentlayer.find('.img>img').error(function(){
										window.currentlayer.show();
										if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
										}
								})
							}else{
								window.currentlayer.show();
								if (postion=='middle'){
										window.currentlayer.css('top','50%').css('margin-top',-window.currentlayer.height()/2)
										}else if(typeof postion === 'number'){
										window.currentlayer.css('top',postion+'px')
										}else{
										window.currentlayer.css('top',postion)
								}
							}
						layerid=window.currentlayer.attr('id');
						$(window.currentlayer).parent().find('.close_,.submit').off().on('click',function(e){
																		e.preventDefault();
																		if ($(this).hasClass('submit')&&callback){
																			callback(e)
																		}
																		if ($(this).hasClass('close_')){
																					window.currentlayer=undefined;
																				    $('#layer_'+layerid).remove();
																				    if (redirect)
																					location.href=redirect;
																		}
																		})
						}
	})
$.fn.extend({hidelayer:function(){
	var layercallback
						if (window.layercallback){layercallback=window.layercallback;window.layercallback=undefined;layercallback();clearInterval(l_interval)}else{
						$('#layer_'+$(this).attr('id')).remove();window.timeoutlayer=undefined;clearInterval(l_interval);}
						}})
$.fn.hasAttr=function(attr){
						return !(typeof $(this).attr(attr)=='undefined')
				 }
function interval_l(timeout_){
	    if (!g_interval&&window.redirect)
		g_interval=setInterval("$(window.currentlayer).html($(window.currentlayer).html()+'.')",500)
		timeout_--
		window.timeout=timeout_;
		if (timeout_==0){
			clearInterval(g_interval);
		   if (window.redirect){location.href=window.redirect}else{window.currentlayer.hidelayer();}
		   return
		}
		l_interval=setTimeout('interval_l(window.timeout)',1000)
}

$.fn.hasAttr=function(attr){
						return !(typeof $(this).attr(attr)=='undefined')
				 }

function setinterval(){
		var datetime=new Date()
		var timestamp=datetime.getTime()
		var timestamp=timestamp.toString().substring(0,10);
		//console.log(timestamp)
		var minute=60
		var hour=60*minute
		var day=24*hour
		if (timestamp<starttime){
			$('.timeout>.tipname').html('距离活动开始还有')
			rm=starttime-timestamp

		}else if(timestamp>=starttime&&timestamp<endtime){
			$('.timeout>.tipname').html('距离活动结束还有')
			rm=endtime-timestamp
		}else{
			$('.timeout>.tipname').html('活动已结束')
			rm=endtime-timestamp
		}
			if (rm>0){
					if (rm>day){
				rmday=Math.floor(rm/day)
				rm=rm-rmday*day
			}else{
				rmday=0;
			}
			if (rm>hour){
				rmhour=Math.floor(rm/hour)
				rm=rm-rmhour*hour
			}else{
				rmhour=0
				}
			if (rm>minute){
				rmminute=Math.floor(rm/minute)
				rm=rm-rmminute*minute
				
			}else{
				rmminute=0
			}
			rmsecond=rm
			if (!rmsecond){
				rmsecond=0
			}
				$('.timeout>.day').html(rmday+'天').show()
				$('.timeout>.hour').html(rmhour+'时').show()
				$('.timeout>.minute').html(rmminute+'分').show()
				$('.timeout>.second').html(rmsecond+'秒').show()
			setTimeout('setinterval()',1000)
			}
}
    function autoScroll(obj){ 

        $(obj).find(".fanrow").animate({ 

            marginTop : "-48px" 

        },500,function(){ 

            $(this).css({marginTop : "0px"}).find("li:first").appendTo(this); 

        }) 

    }

function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
} 
function setCookie(name,value)
{
var Days = 30;
var exp = new Date();
exp.setTime(exp.getTime() +60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function delCookie(name)
{
var exp = new Date();
exp.setTime(exp.getTime() - 1);
var cval=getCookie(name);
if(cval!=null)
document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
function jsonlength(jsonData){  
  
    var jsonLength = 0;  
  
    for(var item in jsonData){  
  
        jsonLength++;  
  
    }  
  
    return jsonLength;  }

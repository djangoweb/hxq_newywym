var l_interval,layers=new Array();
$.ajaxPrefilter(function (options){options.global = true;});
$.ajaxSetup({ /*cache: false,beforeSend:function(){$('body').append('<div id="layer_ajaxloading" class=layer></div>');$('#layer_ajaxloading').append($('#ajaxloading').clone().show());},complete:function(){$('#layer_ajaxloading').remove();}*/});
$.fn.extend({showlayer:function(message,redirect,timeout,dark,position,callback){
	var layerid=$(this).attr('id')
	var templayer;
						if (layers[layerid]){
							templayer=layers[layerid];
							if (message&&$(templayer).find('.message').size()>0)
							{
								templayer.find('.message').html(message);
							}
							templayer.repositionlayer()
							if (timeout>0){
							layers[layerid].interval=setInterval('interval_l("'+layerid+'",'+timeout+')',1000);
							}
							templayer.parent().find('.close_,.submit').off().on('click',function(e){
																			e.preventDefault();
																			if ($(this).hasClass('submit')&&callback){
																				callback(e)
																			}
																			if ($(this).hasClass('close_')){
																					    $('#layer_'+layers[layerid].attr('id')).remove();
																				    layers[layerid]=null
																			}
																			})
							return;
						}
						layers[layerid]=$(this).clone(true)
						layerattr={timeout:timeout,redirect:redirect,dark:dark,callback:callback,position:position};
						$.extend(layers[layerid],layerattr);
						templayer=layers[layerid]
						if (dark){
							$('body').append('<div id='+"layer_"+templayer.attr('id')+' class=layerdark></div>')
						}else{
							$('body').append('<div id='+"layer_"+templayer.attr('id')+' class=layer></div>')
						}
						if (message&&$(templayer).find('.message').size()>0)
						{
							$(templayer).find('.message').html(message);
						}
						$('#layer_'+templayer.attr('id')).append(templayer);
						templayer.repositionlayer()
						if (timeout>0){
							layers[layerid].interval=setInterval('interval_l("'+layerid+'",'+timeout+')',1000);
						}
						templayer.parent().find('.close_,.submit').off().on('click',function(e){
																		e.preventDefault();
																		if ($(this).hasClass('submit')&&callback){
																			callback(e)
																		}
																		if ($(this).hasClass('close_')){
																				    $('#layer_'+layers[layerid].attr('id')).remove();
																				    layers[layerid]=null
																		}
																		})
						}
	})
$.fn.extend({hidelayer:function(){
	var layerid=$(this).attr('id');
						if (layers[layerid].callback){clearInterval(layers[layerid].interval);layers[layerid].callback();layers[layerid]=null}else{
						$('#layer_'+layers[layerid].attr('id')).remove();clearInterval(layers[layerid].interval);layers[layerid]=null}
						}})
$.fn.extend({repositionlayer:function(){
	var layerid=$(this).attr('id');
	if (layers[layerid].find('img').size()>0||(layers[layerid].find('img').attr('src')!=''&&typeof layers[layerid].find('img').attr('src')!='undefined')){
								layers[layerid].find('img').load(function(){
										layers[layerid].show();
										if (layers[layerid].position=='middle'){
										layers[layerid].css('top','50%').css('margin-top',-layers[layerid].height()/2)
										}else if(typeof layers[layerid].position === 'number'){
										layers[layerid].css('top',layers[layerid].position+'px')
										}else{
										layers[layerid].css('top',layers[layerid].position)
										}
								})
								layers[layerid].find('img').error(function(){
										layers[layerid].show();
										if (layers[layerid].position=='middle'){
										layers[layerid].css('top','50%').css('margin-top',-layers[layerid].height()/2)
										}else if(typeof layers[layerid].position === 'number'){
										layers[layerid].css('top',layers[layerid].position+'px')
										}else{
										layers[layerid].css('top',layers[layerid].position)
										}
								})
							}else{
								layers[layerid].show();
								if (layers[layerid].position=='middle'){
										layers[layerid].css('top','50%').css('margin-top',-layers[layerid].height()/2)
										}else if(typeof layers[layerid].position === 'number'){
										layers[layerid].css('top',layers[layerid].position+'px')
										}else{
										layers[layerid].css('top',layers[layerid].position)
								}
							}
}})
$.fn.hasAttr=function(attr){
						return !(typeof $(this).attr(attr)=='undefined')
				 }
function interval_l(layerid){
	layers[layerid].timeout--
		if (layers[layerid].timeout==0){
			clearInterval(layers[layerid].interval)
			layers[layerid].hidelayer();
		    return false
		}
}

$.fn.hasAttr=function(attr){
						return !(typeof $(this).attr(attr)=='undefined')
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
    if (typeof jsonData!='object'){
      return jsonLength;
    }
    for(var item in jsonData){ 
        jsonLength++;  
    }  
    return jsonLength;  
  
}  

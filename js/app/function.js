function interval_l(timeout_){
	    if (!g_interval&&window.redirect)
		g_interval=setInterval("$(window.currentlayer).html($(window.currentlayer).html()+'.')",500)
		timeout_--
		window.timeout=timeout_;
		if (timeout_==0){
			clearInterval(g_interval);
		   if (window.redirect){location.href=window.redirect}else{$('#layer_'+window.currentlayer.attr('id')).remove();}
		   return
		}
		l_interval=setTimeout('interval_l(window.timeout)',1000)
}


function interval_l(timeout_){
	    if (!g_interval&&window.redirect)
		g_interval=setInterval("$(window.currentlayer).html($(window.currentlayer).html()+'.')",500)
		timeout_--
		window.timeout=timeout_;
		if (timeout_==0){
			clearInterval(g_interval);
		   if (window.redirect){location.href=window.redirect}else{$('#layer_'+window.currentlayer.attr('id')).remove();}
		   return
		}
		l_interval=setTimeout('interval_l(window.timeout)',1000)
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
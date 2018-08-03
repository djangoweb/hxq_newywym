define(['jquery','main'],function($,main){
	var modal={}
	modal.params={};
modal.init=function(params){
	$.extend(modal.params,params||{});
	$(".sharebt").click(function(){
								$("#sharelayer").showlayer('','','',true,'0px');
								})
	$(".more").click(function(){
	$("#jjlayer").showlayer('','','',false,'120px')
	})
	$("form[name='lottery']").on('submit',function(e){
		var check=true
		e.preventDefault();
		if ($(this).find('input').size()>1){
			$(this).find('input').each(function(){
				if ($(this).attr('name')!='submit'){
					if ($(this).attr('name')=='mobile'){
						re=/^\d{11}$/;
						if (!re.test($(this).val())){
							layer.open({
							    content: '请正确填写您的手机号码！'
							    ,skin: 'msg'
							    ,time: 2 //2秒后自动关闭
							  });
							check=false;
						}
					}else if($(this).attr('name')=='name'){
						if ($(this).val().length<2){
							layer.open({
							    content: '请填写您的姓名！'
							    ,skin: 'msg'
							    ,time: 2 //2秒后自动关闭
							  });
						    check=false;
						}
					}else{
						if ($(this).val()=='') {e.preventDefault();check=false;}
					}
				}
			})
			if (!check) return;
			url=$(this).attr('action')+'&'+$(this).serialize()
			window.location.href=url;
			return;
		}
		window.location.href=$(this).attr('action');
		return
	})
}
return modal;
});
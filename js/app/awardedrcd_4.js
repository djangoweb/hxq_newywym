define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
	$.extend(modal.params,params||{})
			callback=function(e){
				data=window.currentlayer.find('form').serialize()
				$.post(params.verifurl,data,function(data){
					if (data.errno==0){
						$('.smbtn[data-id='+window.currentlayer.find('input[name=id]').val()+']').off().removeClass('danger').addClass('disable').html('已核销')
						window.currentlayer.hidelayer()
						$('#timeoutlayer').showlayer(data.message,'',true,false,'middle')
					}else{
						window.currentlayer.find('.message').html(data.message)
					}
				},'json')
			}
		 	$('.verif.danger').each(function(){
		 	            $(this).on('click',function(){
		 	              $('#veriflayer').find('.header').html('核销'+$(this).data('title'))
		 	              $('#veriflayer').find('input[name=id]').val($(this).data('id'))
                          $('#veriflayer').showlayer('','',false,true,'middle',callback);
                  })
			})
	}
	return modal;
})
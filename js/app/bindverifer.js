define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
			$('form[name=bj]').on('submit',function(e){
		        e.preventDefault();
				if ($('input[name=name]').val()==''){

		                $('#timeoutlayer').showlayer('请输入您的称呼','',3,false,'middle')
					return
				}
				re=/[0-9]{11}/;
			    if (!re.test($('input[name=mobile]').val())){

		                $('#timeoutlayer').showlayer('请输入正确的手机号','',3,false,'middle')
					return
				}
				$.ajax({
					url:params.url,
					data:$(this).serialize(),
					type:'post',
		            dataType:'json',
					success:function(data){
		                $('#timeoutlayer').showlayer(data.message,'',3,false,'middle')
		            },
					fail:function(){}
				})
			})
	}
	return modal;
})
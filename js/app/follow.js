define(['jquery','main','clipboard'],function($,main,Clipboard ){
	var modal={};
	modal.params={};
	modal.init=function(params){
		$.extend(modal.params,params||{})
		var clipboard = new Clipboard('.button',{
		 	text:function () {
		          return $("#bar").html();
		    }
		 });  
		 clipboard.on('success', function(e) {
		 	layer.open({
								    content: '复制成功！'
								    ,skin: 'msg'
								    ,time: 2 //2秒后自动关闭
								  });
		    e.clearSelection();
		});
		clipboard.on('error', function(e) {
			layer.open({
								    content: '复制失败，请长按口令手动复制！'
								    ,skin: 'msg'
								    ,time: 2 //2秒后自动关闭
								  });
		});
	}
	return modal;
})
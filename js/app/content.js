define(['jquery','main'],function($,main){
	var modal={};
	modal.params={};
	modal.init=function(params){
	$.extend(modal.params,params||{})
		wx.scanQRCode({
	    needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
	    success: function (res) {
	    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
		}
	});
	}
	return modal;
})
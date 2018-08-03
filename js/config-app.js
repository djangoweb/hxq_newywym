var version = +new Date();
require.config({
    urlArgs: 'v=' + version, 
    baseUrl: '../addons/hxq_newywym/js/app',
    paths: {
        'jquery': '../dist/jquery-1.11.1.min',
        'swiper':'../dist/swiper.min',
        'clipboard':'../dist/clipboard.min',
        "jquery.jplayer":"../dist/jplayer/jquery.jplayer.min",
        "SuperSlide":"../dist/jquery.SuperSlide.2.1.1",
        "weixin":(document.location.protocol=='http'?"http://res.wx.qq.com/open/js/jweixin-1.2.0":"https://res.wx.qq.com/open/js/jweixin-1.2.0"),
    },
    shim: {
        'foxui.picker': {
            exports: "foxui",
            deps: ['foxui','foxui.citydata']
        },
        'main': {
            exports: "main",
            deps: ['jquery']
        },
        'jquery.jplayer': {
            exports: "$",
            deps: ['jquery']
        },
        'SuperSlide':{
            deps:['jquery']
        },
        'script.min':{
            exports:'$script',
            deps:['jquery'],
        }
    },
    waitSeconds: 0
});
